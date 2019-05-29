<?php


// Add School Option Field
function bp_lp_insert_school_field($id, $parent_id, $field_name, $description, $type){
    // Preset field config template
    $bp = buddypress();
    global $wpdb;
    $table_name = $bp->profile->table_name_fields;
    if($type=='created'){
        $wpdb->insert($table_name, array(
                'id' => $id,
                'group_id' => 1,
                'parent_id' => $parent_id,
                'type' => 'option',
                'name' => $field_name,
                'description' => $description,
                'is_required' => false,
                'order_by' => '',
                'field_order' => '',
                'option_order' => '',
                'can_delete' => 0,
                'is_default_option' => false
            )
        );
    }else{
        $wpdb->update($table_name, array(
                'group_id' => 1,
                'parent_id' => $parent_id,
                'type' => 'option',
                'name' => $field_name,
                'description' => $description,
                'is_required' => false,
                'order_by' => '',
                'field_order' => '',
                'option_order' => '',
                'can_delete' => 0,
                'is_default_option' => false
            ), array('id' => $id)
        );
    }
}

function bp_lp_custom_school_taxonomy(){
    // Add Class taxonomy, make it hierarchical (like categories)
    $args = array(
        'label'             => __( 'Schools', 'buddypress-lms-integration' ),
					'labels'            => array(
        'name'          => __( 'Schools', 'buddypress-lms-integration' ),
        'menu_name'     => __( 'School', 'buddypress-lms-integration' ),
        'singular_name' => __( 'School', 'buddypress-lms-integration' ),
        'add_new_item'  => __( 'Add New School', 'buddypress-lms-integration' ),
        'search_items'  => __( 'Search Schools', 'buddypress-lms-integration' ),
        'edit_item'     => __( 'Edit School', 'buddypress-lms-integration' ),
        'all_items'     => __( 'All Schools', 'buddypress-lms-integration' )
    ),
					'query_var'         => true,
					'public'            => true,
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_in_menu'      => 'buddypress-lms-integration',
					'show_admin_column' => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
        'slug'         => 'schools',
        'hierarchical' => true,
        'with_front'   => false
    ),
				);
    register_taxonomy( 'course_school', array( LP_COURSE_CPT ), $args );
}

function bp_lp_field_settings($taxonomy) {
    $url = add_query_arg( array(
        'page'      => 'buddypress-lms-integration',
        'action'    => 'field_settings',
        'post_type' => 'lp_course'
    ), admin_url('admin.php') );
    echo "<a href='$url' class='button action'>Field Settings</a>";
}

add_action('after-course_school-table','bp_lp_field_settings');

function watch_create_course_school($term_id, $tt_id, $taxonomy){
    if($taxonomy==='course_school'){
        $data = get_term($term_id);
        $school_id = xprofile_get_field_id_from_name("School");
        bp_lp_insert_school_field(
            $data->term_id,
            $school_id,
            $data->name,
            $data->description,
            'created'
        );
    }
}

function watch_edit_course_school($term_id, $tt_id, $taxonomy){
    if($taxonomy==='course_school'){
        $data = get_term($term_id);
        $school_id = xprofile_get_field_id_from_name("School");
        bp_lp_insert_school_field(
            $data->term_id,
            $school_id,
            $data->name,
            $data->description,
            'edit'
        );
    }
}

function watch_delete_course_school($term_id, $tt_id, $taxonomy){
    if($taxonomy==='course_school'){
        $bp = buddypress();
        global $wpdb;
        $table_name = $bp->profile->table_name_fields;
        $wpdb->delete( $table_name, array( 'id' => $term_id ) );
    }
}

add_action( 'created_term', 'watch_create_course_school', 10, 3 );

add_action( 'edited_term', 'watch_edit_course_school', 10, 3 );

add_action( 'delete_term', 'watch_delete_course_school', 10, 3 );
