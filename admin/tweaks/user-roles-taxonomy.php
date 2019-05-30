<?php

// Add User Role Option Field
function bp_lp_insert_user_roles_field($id, $parent_id, $field_name, $description, $type){
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

function bp_lp_custom_user_roles_taxonomy(){
    // Add Class taxonomy, make it hierarchical (like categories)
    $args = array(
        'label'             => __( 'User Roles', 'buddypress-lms-integration' ),
        'has_archive' => true,
        'labels'            => array(
            'name'          => __( 'User Roles', 'buddypress-lms-integration' ),
            'menu_name'     => __( 'User Role', 'buddypress-lms-integration' ),
            'singular_name' => __( 'User Role', 'buddypress-lms-integration' ),
            'add_new_item'  => __( 'Add New User Role', 'buddypress-lms-integration' ),
            'search_items'  => __( 'Search User Roles', 'buddypress-lms-integration' ),
            'edit_item'     => __( 'Edit User Role', 'buddypress-lms-integration' ),
            'all_items'     => __( 'All User Roles', 'buddypress-lms-integration' )
        ),
        'query_var'         => true,
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => 'buddypress-lms-integration',
        'show_admin_column' => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true
    );
    register_taxonomy( 'user_role', array( LP_COURSE_CPT ), $args );
}

function bp_lp_field_user_role_settings($taxonomy) {
    $url = add_query_arg( array(
        'page'      => 'buddypress-lms-integration',
        'action'    => 'field_settings',
        'post_type' => 'lp_course'
    ), admin_url('admin.php') );
    echo "<a href='$url' class='button action'>Field Settings</a>";
}

add_action('after-user_role-table','bp_lp_field_user_role_settings');

function watch_create_user_role($term_id, $tt_id, $taxonomy){
    if($taxonomy==='user_role'){
        $data = get_term($term_id);
        $school_id = xprofile_get_field_id_from_name("User Role");
        bp_lp_insert_user_roles_field(
            $data->term_id,
            $school_id,
            $data->name,
            $data->description,
            'created'
        );
    }
}

function watch_edit_user_role($term_id, $tt_id, $taxonomy){
    if($taxonomy==='user_role'){
        $data = get_term($term_id);
        $school_id = xprofile_get_field_id_from_name("User Role");
        bp_lp_insert_user_roles_field(
            $data->term_id,
            $school_id,
            $data->name,
            $data->description,
            'edit'
        );
    }
}

function watch_delete_user_role($term_id, $tt_id, $taxonomy){
    if($taxonomy==='user_role'){
        $bp = buddypress();
        global $wpdb;
        $table_name = $bp->profile->table_name_fields;
        $wpdb->delete( $table_name, array( 'id' => $term_id ) );
    }
}

add_action( 'created_term', 'watch_create_user_role', 10, 3 );

add_action( 'edited_term', 'watch_edit_user_role', 10, 3 );

add_action( 'delete_term', 'watch_delete_user_role', 10, 3 );
