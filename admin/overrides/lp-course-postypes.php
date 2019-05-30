<?php

function bp_lp_course_post_type(){
    $settings         = LP_Settings::instance();
    $labels           = array(
        'name'               => _x( 'Courses', 'Post Type General Name', 'learnpress' ),
        'singular_name'      => _x( 'Course', 'Post Type Singular Name', 'learnpress' ),
        'menu_name'          => __( 'Courses', 'learnpress' ),
        'parent_item_colon'  => __( 'Parent Item:', 'learnpress' ),
        'all_items'          => __( 'Courses', 'learnpress' ),
        'view_item'          => __( 'View Course', 'learnpress' ),
        'add_new_item'       => __( 'Add New Course', 'learnpress' ),
        'add_new'            => __( 'Add New', 'learnpress' ),
        'edit_item'          => __( 'Edit Course', 'learnpress' ),
        'update_item'        => __( 'Update Course', 'learnpress' ),
        'search_items'       => __( 'Search Courses', 'learnpress' ),
        'not_found'          => sprintf( __( 'You haven\'t had any courses yet. Click <a href="%s">Add new</a> to start', 'buddypress-lms-integration' ), admin_url( 'post-new.php?post_type=lp_course' ) ),
        'not_found_in_trash' => __( 'No course found in Trash', 'learnpress' )
    );
    $course_base      = $settings->get( 'course_base' );
    $course_permalink = empty( $course_base ) ? _x( 'courses', 'slug', 'learnpress' ) : $course_base;

    //$course_permalink = '';

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'query_var'          => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'has_archive'        => 'courses',
        //( $page_id = learn_press_get_page_id( 'courses' ) ) && get_post( $page_id ) ? get_page_uri( $page_id ) : 'courses',
        'capability_type'    => LP_COURSE_CPT,
        'map_meta_cap'       => true,
        'show_in_menu'       => 'learn_press',
        'show_in_admin_bar'  => true,
        'show_in_nav_menus'  => true,
        'taxonomies'         => array( 'course_category', 'course_tag' ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'excerpt' ),
        'hierarchical'       => false,
        'rewrite'            => $course_permalink ? array(
            'slug'       => untrailingslashit( $course_permalink ),
            'with_front' => false
        ) : false
    );
    unregister_post_type( 'lp_course' );
    register_post_type( "lp_course", $args);
}