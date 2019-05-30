<?php

function bp_lp_admin_course_tabs() {
    if ( ! is_admin() ) {
        return;
    }
    if(!isset($_GET['post_type'])){
        return;
    }else{
        if($_GET['post_type']==='lp_course'){
            $admin_tabs = apply_filters(
                'learn_press_admin_tabs_info',
                array(


                    20 => array(
                        "link" => "edit.php?post_type=lp_course",
                        "name" => __( "Courses", "buddypress-lms-integration" ),
                        "id"   => "edit-lp_course",
                        "rel-id" => "",
                    ),

                    30 => array(
                        "link" => "edit-tags.php?taxonomy=course_category&post_type=lp_course",
                        "name" => __( "Course Categories", "buddypress-lms-integration" ),
                        "id"   => "edit-course_category",
                        "rel-id" => "",
                    ),

                    40 => array(
                        "link" => "edit-tags.php?taxonomy=course_tag&post_type=lp_course",
                        "name" => __( "Tags", "buddypress-lms-integration" ),
                        "id"   => "edit-course_tag",
                        "rel-id" => "",
                    ),

                    50 => array(
                        "link" => "edit-tags.php?taxonomy=course_school&post_type=lp_course",
                        "name" => __( "Schools", "buddypress-lms-integration" ),
                        "id"   => "edit-course_school",
                        "rel-id" => "toplevel_page_buddypress-lms-integration",
                    ),

                    60 => array(
                        "link" => "edit-tags.php?taxonomy=user_role&post_type=lp_course",
                        "name" => __( "Use Roles", "buddypress-lms-integration" ),
                        "id"   => "edit-user_role",
                        "rel-id" => "toplevel_page_buddypress-lms-integration",
                    ),

                )
            );
            ksort( $admin_tabs );
            $tabs = array();
            foreach ( $admin_tabs as $key => $value ) {
                array_push( $tabs, $key );
            }
            $pages              = apply_filters(
                'learn_press_admin_tabs_on_pages',
                array(
                    'edit-lp_course',
                    'edit-course_category',
                    'edit-course_school',
                    'edit-course_tag',
                    'lp_course',
                    'edit-user_role',
                    'toplevel_page_buddypress-lms-integration' )
            );
            $admin_tabs_on_page = array();
            foreach ( $pages as $page ) {
                $admin_tabs_on_page[ $page ] = $tabs;
            }
            $current_page_id = get_current_screen()->id;
            $current_user    = wp_get_current_user();
            if ( ! in_array( 'administrator', $current_user->roles ) ) {
                return;
            }
            if ( ! empty( $admin_tabs_on_page[ $current_page_id ] ) && count( $admin_tabs_on_page[ $current_page_id ] ) ) {
                include 'lp-course-header.php';
                echo '<h2 class="nav-tab-wrapper lp-nav-tab-wrapper">';
                foreach ( $admin_tabs_on_page[ $current_page_id ] as $admin_tab_id ) {

                    $class = ( $admin_tabs[ $admin_tab_id ]["id"] == $current_page_id ) ? "nav-tab nav-tab-active" :
                        ( $admin_tabs[ $admin_tab_id ]["rel-id"] == $current_page_id ) ? "nav-tab nav-tab-active" : "nav-tab";
                    echo '<a href="' . admin_url( $admin_tabs[ $admin_tab_id ]["link"] ) . '" class="' . $class . ' nav-tab-' . $admin_tabs[ $admin_tab_id ]["id"] . '">' . $admin_tabs[ $admin_tab_id ]["name"] . '</a>';
                }
                echo '</h2>';
            }
        }
    }
}
