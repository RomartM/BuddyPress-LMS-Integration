<?php

$GLOBALS['hook_suffix'] = 'bp_lms';

require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once(ABSPATH . 'wp-admin/includes/screen.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');
require_once(ABSPATH . 'wp-admin/includes/template.php');

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once(BP_LMS_BASE_PATH.'/admin/tweaks/subsubsub.php');
require_once( BP_LMS_BASE_PATH.'/admin/tweaks/BP_UserRole_List_Table.php' );


function user_dashboard_assets(){
    global $posts;
    foreach ($posts as $post) {
        // Only Load Short Code Assets if Post has shortcode
        if ( $post->post_content=="[user_dashboard]" )
            wp_enqueue_style('bp-lms-style', plugins_url('css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('bp-lms-dashboard', plugins_url('css/bp-lms-dashboard.css', __FILE__));
            wp_enqueue_script('bp-lms-registration-script', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
        break;
    }
}

function get_dashboard_tab_content($type, $filename){
    return BP_LMS_BASE_PATH . '/client/templates/dashboard-tabs/'.$type.'/'.$filename.'.php';
}

function user_dashboard_shortcode() {
    $user = wp_get_current_user();
    if(empty($user->roles)){
        return;
    }
    $role_data = '';
    foreach ($user->roles as $role){
        $role_data = $role;
        break;
    }
    $eligible_users = array("student", "lp_teacher", "parent", "administrator");
    if(in_array($role_data, $eligible_users)){
        include (BP_LMS_BASE_PATH.'/client/templates/dashboard-'.$role_data.'.php');
    }
}

add_action('wp_enqueue_scripts', 'user_dashboard_assets');

function check_for_shortcode($posts) {

    if ( empty($posts) )

        return $posts;

// false because we have to search through the posts first

    $found = false;


// search through each post

    foreach ($posts as $post) {

// check the post content for the short code

        if ( stripos($post->post_content, '[user_dashboard]') )

// we have found a post with the short code

            $found = true;

// stop the search

        break;

    }


    if ($found){

// $url contains the path to your plugin folder

        wp_enqueue_style('bp-lms-style', plugins_url('css/bootstrap.min.css', __FILE__));
        wp_enqueue_script('bp-lms-registration-script', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
    }

    return $posts;

}

// perform the check when the_posts() function is called

//add_action('the_posts', 'check_for_shortcode');