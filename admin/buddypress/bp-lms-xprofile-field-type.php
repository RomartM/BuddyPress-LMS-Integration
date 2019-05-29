<?php

/**
 * Custom BuddyPress XProfile Classes.
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class to help set up XProfile fields.
 *
 * @since 1.0.0
 */
class BP_LMS_XProfile_Field_Type extends BP_XProfile_Field_Type {

    public function __construct() {

        parent::__construct();
    }

    public function edit_field_html(array $raw_properties = array()){
        return $raw_properties;
    }

    public function admin_field_html(array $raw_properties = array()){
        return $raw_properties;
    }

    public function tab_new_field_html( BP_XProfile_Field $current_field, $control_type = 'radio' ) {
        $type = array_search( get_class( $this ), bp_lms_xprofile_get_field_types() );
        if ( false === $type ) {
            return;
        }

        $class            = $current_field->type != $type ? 'display: none;' : '';
        $current_type_obj = bp_lms_xprofile_create_field_type( $type );
        ?>

        <div id="<?php echo esc_attr( $type ); ?>" class="postbox bp-options-box" style="<?php echo esc_attr( $class ); ?> margin-top: 15px;">
            <h3><?php esc_html_e( 'Rearrange School field values' ); ?></h3>
            <div class="inside" aria-live="polite" aria-atomic="true" aria-relevant="all">
                <p>
                    <label for="sort_order_<?php echo esc_attr( $type ); ?>"><?php esc_html_e( 'Sort Order:', 'buddypress' ); ?></label>
                    <select name="sort_order_<?php echo esc_attr( $type ); ?>" id="sort_order_<?php echo esc_attr( $type ); ?>" >
                        <option value="custom" <?php selected( 'custom', $current_field->order_by ); ?>><?php esc_html_e( 'Custom',     'buddypress' ); ?></option>
                        <option value="asc"    <?php selected( 'asc',    $current_field->order_by ); ?>><?php esc_html_e( 'Ascending',  'buddypress' ); ?></option>
                        <option value="desc"   <?php selected( 'desc',   $current_field->order_by ); ?>><?php esc_html_e( 'Descending', 'buddypress' ); ?></option>
                    </select>
                </p>

                <?php

                // Does option have children?
                $options = $current_field->get_children( true );

                // If no children options exists for this field, check in $_POST
                // for a submitted form (e.g. on the "new field" screen).
                if ( empty( $options ) ) {

                    $options = array();
                    $i       = 1;

                    while ( isset( $_POST[$type . '_option'][$i] ) ) {

                        // Multiselectbox and checkboxes support MULTIPLE default options; all other core types support only ONE.
                        if ( $current_type_obj->supports_options && ! $current_type_obj->supports_multiple_defaults && isset( $_POST["isDefault_{$type}_option"][$i] ) && (int) $_POST["isDefault_{$type}_option"] === $i ) {
                            $is_default_option = true;
                        } elseif ( isset( $_POST["isDefault_{$type}_option"][$i] ) ) {
                            $is_default_option = (bool) $_POST["isDefault_{$type}_option"][$i];
                        } else {
                            $is_default_option = false;
                        }

                        // Grab the values from $_POST to use as the form's options.
                        $options[] = (object) array(
                            'id'                => -1,
                            'is_default_option' => $is_default_option,
                            'name'              => sanitize_text_field( stripslashes( $_POST[$type . '_option'][$i] ) ),
                        );

                        ++$i;
                    }

                    // If there are still no children options set, this must be the "new field" screen, so add one new/empty option.
                    if ( empty( $options ) ) {
                        $options[] = (object) array(
                            'id'                => -1,
                            'is_default_option' => false,
                            'name'              => '',
                        );
                    }
                }

                // Render the markup for the children options.
                if ( ! empty( $options ) ) {
                    $default_name = '';

                    for ( $i = 0, $count = count( $options ); $i < $count; ++$i ) :
                        $j = $i + 1;

                        // Multiselectbox and checkboxes support MULTIPLE default options; all other core types support only ONE.
                        if ( $current_type_obj->supports_options && $current_type_obj->supports_multiple_defaults ) {
                            $default_name = '[' . $j . ']';
                        }
                        ?>

                        <div id="<?php echo esc_attr( "{$type}_div{$j}" ); ?>" class="bp-option sortable">
                            <span class="bp-option-icon grabber"></span>
                            <label for="<?php echo esc_attr( "{$type}_option{$j}" ); ?>" class="screen-reader-text"><?php
                                /* translators: accessibility text */
                                esc_html_e( 'Add an option', 'buddypress' );
                                ?></label>
                            <input type="text" readonly name="<?php echo esc_attr( "{$type}_option[{$j}]" ); ?>" id="<?php echo esc_attr( "{$type}_option{$j}" ); ?>" value="<?php echo esc_attr( stripslashes( $options[$i]->name ) ); ?>" />
                            <label for="<?php echo esc_attr( "{$type}_option{$default_name}" ); ?>">
                                <input type="<?php echo esc_attr( $control_type ); ?>" id="<?php echo esc_attr( "{$type}_option{$default_name}" ); ?>" name="<?php echo esc_attr( "isDefault_{$type}_option{$default_name}" ); ?>" <?php checked( $options[$i]->is_default_option, true ); ?> value="<?php echo esc_attr( $j ); ?>" />
                                <?php _e( 'Default Value', 'buddypress' ); ?>
                            </label>
                        </div>

                    <?php endfor; ?>

                    <input type="hidden" name="<?php echo esc_attr( "{$type}_option_number" ); ?>" id="<?php echo esc_attr( "{$type}_option_number" ); ?>" value="<?php echo esc_attr( $j + 1 ); ?>" />
                <?php } ?>
                <div id="<?php echo esc_attr( "{$type}_more" ); ?>"></div>
                <?php

                /**
                 * Fires at the end of the new field additional settings area.
                 *
                 * @since 2.3.0
                 *
                 * @param BP_XProfile_Field $current_field Current field being rendered.
                 */
                do_action( 'bp_xprofile_admin_new_field_additional_settings', $current_field ) ?>
            </div>
        </div>

        <?php
    }

}
