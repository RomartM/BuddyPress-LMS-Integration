<?php
// Admin Dashboard Assets
function bp_lms_load_admin_stylesheet()
{
    wp_enqueue_style('jquery-ui-css', plugins_url('css/lib/jquery-ui.css', __FILE__));
    wp_enqueue_style('snackbar-css', plugins_url('css/lib/snackbar.css', __FILE__));
    wp_enqueue_style('material-icon-css', plugins_url('css/lib/icon.css', __FILE__));
    wp_enqueue_style('bpe-admin-dashboard-styles', plugins_url('css/bp-lms-admin-dashboard.css', __FILE__));
}

function bp_lms_load_admin_javascript()
{
    wp_enqueue_script('jquery-ui-js', plugins_url('js/lib/jquery-ui.js', __FILE__), array('jquery'));
    // Load SnackBar Plugin lib
    wp_enqueue_script('bpe-admin-dashboard-snackbar', plugins_url('js/lib/snackbar.js', __FILE__), array('jquery'));
    // Load BMI Dashboard script
    wp_enqueue_script('bpe-admin-dashboard-script', plugins_url('js/bp-lms-admin-dashboard.js', __FILE__), array('jquery'));
}


function bp_lms_admin_dashboard(){
    add_menu_page('BuddyPress LMS Integration Dashboard', 'BuddyPress LMS', 'manage_options', 'buddypress-lms-integration', 'bp_lms_admin_dashboard_init', '', 55);
}

// Plugin Admin Dashboard Initiator
function bp_lms_admin_dashboard_init(){
    require_once('dashboard.php');
}

// Add Admin Dashboard assets
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_javascript');
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_stylesheet');