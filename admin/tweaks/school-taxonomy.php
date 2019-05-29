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
        'label'         => __( 'Schools', 'buddypress-lms-integration' ),
        'has_archive' => true,
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
        'with_front'   => true
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

add_action('admin_menu', 'bcw_init');

function bcw_init() {
    add_filter('course_school_row_actions','bcw_action', 10, 2);
}

function bcw_action($actions, $object) {
    $url = add_query_arg(array('post_type'  => 'lp_course',
        'course_school'  => $object->slug), admin_url("edit.php"));
    $action = "<a href=\"$url\">Manage Courses</a>";
    $actions['manage_course'] = $action;
    return $actions;
}

add_action( 'created_term', 'watch_create_course_school', 10, 3 );

add_action( 'edited_term', 'watch_edit_course_school', 10, 3 );

add_action( 'delete_term', 'watch_delete_course_school', 10, 3 );


function check_is_user() {
    $user = wp_get_current_user();
    if ( ! $user->ID || in_array('student', $user->roles) ) {
        // user is not logged or is a subscriber
        return false;
    }
    return true;
}

function exclude_category( $query ) {

    if(check_is_user()){
        $user = wp_get_current_user();
        $user_school = get_user_school( $user->ID );

        $query->set( 'tax_query', array(
            array(
                'taxonomy' => 'course_school',
                'field' => 'slug',
                'terms' => $user_school
            )
        ) );

        return $query;
    }
}
add_action( 'pre_get_posts', 'exclude_category' );













//add_filter('template_include', 'restict_by_category');

 function get_user_school($user_id ) {

    $r = bp_parse_args( [], array(
        'profile_group_id' => 0,
        'user_id'          =>  $user_id
    ), 'bp_xprofile_user_admin_profile_loop_args' );

    $i = 0;

    if ( bp_has_profile( $r ) ) {

        while ( bp_profile_groups() ) {

            bp_the_profile_group();

            while ( bp_profile_fields() ) {

                bp_the_profile_field();
                $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                if(bp_get_the_profile_field_name()==='School'){
                    return bp_get_the_profile_field_edit_value();
                }
                $i++;
            }
        }
    }
    return '';
}


function restict_by_category( $template ) {
    $allow = true;
    $user = wp_get_current_user();
    $user_school = get_user_school( $user->ID );
    $private_categories = array($user_school);

    if ( is_single() ) {
        $cats = wp_get_object_terms( get_queried_object()->ID, 'course_school', array('fields' => 'slugs') ); // get the categories associated to the required post

        if ( array_intersect( $private_categories, $cats ) ) {
            // post has a reserved category, let's check user
            $allow = check_user();
        }

    } elseif ( is_tax('course_school', $private_categories) ) {
        // the archive for one of private categories is required, let's check user
        $allow = check_user();
    }

    // if allowed include the required template, otherwise include the 'not-allowed' one
    return $allow ? $template : get_template_directory() . '/not-allowed.php';
}
