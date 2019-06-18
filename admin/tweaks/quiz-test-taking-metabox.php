<?php

//
// Create Metabox
//

function bp_lms_test_taking_create_metabox() {
    add_meta_box(
        'bp_lms_test_taking_metabox',
        'Test Info',
        'bp_lms_test_taking_render_metabox',
        'lp_quiz',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'bp_lms_test_taking_create_metabox' );


function bp_lms_test_taking_defaults(){
    $grade = (array) [
        array('displayText'=>'Kinder 1', 'value'=>'k1'),
        array('displayText'=>'Kinder 2', 'value'=>'k2'),
        array('displayText'=>'Grade 1', 'value'=>'g1'),
        array('displayText'=>'Grade 2', 'value'=>'g2'),
        array('displayText'=>'Grade 3', 'value'=>'g3'),
        array('displayText'=>'Grade 4', 'value'=>'g4'),
        array('displayText'=>'Grade 5', 'value'=>'g5'),
        array('displayText'=>'Grade 6', 'value'=>'g6'),
        array('displayText'=>'Grade 7', 'value'=>'g7'),
        array('displayText'=>'Grade 8', 'value'=>'g8'),
        array('displayText'=>'Grade 9', 'value'=>'g9'),
        array('displayText'=>'Grade 10', 'value'=>'g10'),
        array('displayText'=>'Grade 11', 'value'=>'g11'),
        array('displayText'=>'Grade 12', 'value'=>'g12'),

    ];

    $quarter = (array) [
        1,2,3,4
    ];
    return (object) [
            "grade"     => $grade,
            "quarter"   => $quarter
    ];
}

/**
 * Create the metabox default values
 * This allows us to save multiple values in an array, reducing the size of our database.
 * Setting defaults helps avoid "array key doesn't exit" issues.
 * @todo
 */
function bp_lms_test_taking_metabox_defaults() {
    $grade = bp_lms_test_taking_defaults()->grade;
    $quarter = bp_lms_test_taking_defaults()->quarter;
    return array(
        'subject'   => '',
        'grade'     => $grade[0]['value'],
        'quarter'   => $quarter[0],
        'isLongTest'  => 'on'
    );
}

/**
 * Render the metabox markup
 * This is the function called in `bp_lms_test_taking_create_metabox()`
 */
function bp_lms_test_taking_render_metabox() {
    // Variables
    global $post; // Get the current post data
    $saved = get_post_meta( $post->ID, 'bp_lms_test_taking', true ); // Get the saved values
    $defaults = bp_lms_test_taking_metabox_defaults(); // Get the default values
    $details = wp_parse_args( $saved, $defaults ); // Merge the two in case any fields don't exist in the saved data
    
    ?>

    <fieldset>
        <div>
            <label for="bp_lms_test_taking_custom_metabox_subject">
                <?php
                // This runs the text through a translation and echoes it (for internationalization)
                _e( 'Subject', 'bp_lms_test_taking' );
                ?>
            </label>
            <input
                type="text"
                name="bp_lms_test_taking_custom_metabox[subject]"
                placeholder="Filipino"
                id="bp_lms_test_taking_custom_metabox_subject"
                value="<?php echo esc_attr( $details['subject'] ); ?>"
            >
        </div>

        <div>
            <label>
                <?php _e( 'Grade', 'bp_lms_test_taking' ); ?>
                <select name="bp_lms_test_taking_custom_metabox[grade]">
                    <?php
                    $grade_levels = bp_lms_test_taking_defaults()->grade;
                    $i = 0;
                    foreach ($grade_levels as $level):
                    ?>
                    <option value="<?php echo $level['value'];?>" <?php echo $details['grade']==$level['value']? 'selected' :'';?>>
                        <?php echo $level['displayText'];?></option>
                    <?php
                    $i++;
                    endforeach; ?>
                </select>
            </label>
        </div>

        <div>
            <label>
                <?php _e( 'Quarter', 'bp_lms_test_taking' ); ?>
                <select name="bp_lms_test_taking_custom_metabox[quarter]">
                    <?php
                    $quarter_levels = bp_lms_test_taking_defaults()->quarter;
                    $i = 0;
                    foreach ($quarter_levels as $level):
                        ?>
                        <option value="<?php echo $level;?>" <?php echo $details['quarter']==$level? 'selected' :'';?>>
                            <?php echo sprintf("Quarter %s", $level);?></option>
                        <?php
                        $i++;
                    endforeach; ?>
                </select>
            </label>
        </div>

        <div>
            <label>
                <?php _e( 'Long Test', 'bp_lms_test_taking' ); ?>
                <input
                        type="checkbox"
                        name="bp_lms_test_taking_custom_metabox[isLongTest]"
                    <?php
                    checked( $details['isLongTest'], 'on' );
                    ?>
                >
            </label>
        </div>



    </fieldset>

    <?php
    // Security field
    // This validates that submission came from the
    // actual dashboard and not the front end or
    // a remote server.
    wp_nonce_field( 'bp_lms_test_taking_form_metabox_nonce', 'bp_lms_test_taking_form_metabox_process' );
}
//
// Save our data
//
/**
 * Save the metabox
 * @param  Number $post_id The post ID
 * @param  Array  $post    The post data
 */
function bp_lms_test_taking_save_metabox( $post_id, $post ) {
    // Verify that our security field exists. If not, bail.
    if ( !isset( $_POST['bp_lms_test_taking_form_metabox_process'] ) ) return;
    // Verify data came from edit/dashboard screen
    if ( !wp_verify_nonce( $_POST['bp_lms_test_taking_form_metabox_process'], 'bp_lms_test_taking_form_metabox_nonce' ) ) {
        return $post->ID;
    }
    // Verify user has permission to edit post
    if ( !current_user_can( 'edit_post', $post->ID )) {
        return $post->ID;
    }
    // Check that our custom fields are being passed along
    // This is the `name` value array. We can grab all
    // of the fields and their values at once.
    if ( !isset( $_POST['bp_lms_test_taking_custom_metabox'] ) ) {
        return $post->ID;
    }
    /**
     * Sanitize all data
     * This keeps malicious code out of our database.
     */
    // Set up an empty array
    $sanitized = array();

    $data = $_POST['bp_lms_test_taking_custom_metabox'];
    if(!array_key_exists('isLongTest', $data)){
        $sanitized['isLongTest'] = wp_filter_post_kses( 'off' );
    }
    // Loop through each of our fields
    foreach ($data  as $key => $detail ) {
        // Sanitize the data and push it to our new array
        // `wp_filter_post_kses` strips our dangerous server values
        // and allows through anything you can include a post.
        $sanitized[$key] = wp_filter_post_kses( $detail );
    }
    // Save our submissions to the database
    update_post_meta( $post->ID, 'bp_lms_test_taking', $sanitized );
}
add_action( 'save_post', 'bp_lms_test_taking_save_metabox', 1, 2 );
//
// Save a copy to our revision history
// This is optional, and potentially undesireable for certain data types.
// Restoring a a post to an old version will also update the metabox.
/**
 * Save events data to revisions
 * @param  Number $post_id The post ID
 */
function bp_lms_test_taking_save_revisions( $post_id ) {
    // Check if it's a revision
    $parent_id = wp_is_post_revision( $post_id );
    // If is revision
    if ( $parent_id ) {
        // Get the saved data
        $parent = get_post( $parent_id );
        $details = get_post_meta( $parent->ID, 'bp_lms_test_taking', true );
        // If data exists and is an array, add to revision
        if ( !empty( $details ) && is_array( $details ) ) {
            // Get the defaults
            $defaults = bp_lms_test_taking_metabox_defaults();
            // For each default item
            foreach ( $defaults as $key => $value ) {
                // If there's a saved value for the field, save it to the version history
                if ( array_key_exists( $key, $details ) ) {
                    add_metadata( 'post', $post_id, 'bp_lms_test_taking_' . $key, $details[$key] );
                }
            }
        }
    }
}
add_action( 'save_post', 'bp_lms_test_taking_save_revisions' );
/**
 * Restore events data with post revisions
 * @param  Number $post_id     The post ID
 * @param  Number $revision_id The revision ID
 */
function bp_lms_test_taking_restore_revisions( $post_id, $revision_id ) {
    // Variables
    $post = get_post( $post_id ); // The post
    $revision = get_post( $revision_id ); // The revision
    $defaults = bp_lms_test_taking_metabox_defaults(); // The default values
    $details = array(); // An empty array for our new metadata values
    // Update content
    // For each field
    foreach ( $defaults as $key => $value ) {
        // Get the revision history version
        $detail_revision = get_metadata( 'post', $revision->ID, 'bp_lms_test_taking_' . $key, true );
        // If a historic version exists, add it to our new data
        if ( isset( $detail_revision ) ) {
            $details[$key] = $detail_revision;
        }
    }
    // Replace our saved data with the old version
    update_post_meta( $post_id, 'bp_lms_test_taking', $details );
}
add_action( 'wp_restore_post_revision', 'bp_lms_test_taking_restore_revisions', 10, 2 );

/**
 * Get the data to display on the revisions page
 * @param  Array $fields The fields
 * @return Array The fields
 */
function bp_lms_test_taking_get_revisions_fields( $fields ) {
    // Get our default values
    $defaults = bp_lms_test_taking_metabox_defaults();
    // For each field, use the key as the title
    foreach ( $defaults as $key => $value ) {
        $fields['bp_lms_test_taking_' . $key] = ucfirst( $key );
    }
    return $fields;
}
add_filter( '_wp_post_revision_fields', 'bp_lms_test_taking_get_revisions_fields' );

/**
 * Display the data on the revisions page
 * @param  String|Array $value The field value
 * @param  Array        $field The field
 * @return Array Data
 */
function bp_lms_test_taking_display_revisions_fields( $value, $field ) {
    global $revision;
    return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_my_meta', 'bp_lms_test_taking_display_revisions_fields', 10, 2 );