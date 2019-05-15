<?php

$bp_lms_plugin_info = array(
    'name'=>'BuddyPress LMS Integration',
    'description' => 'Add custom registration fields and Manage User Information'
);

$bp_lms_tabs_registered = array("school", "user-role");

$bp_lms_tabs_nav_object = (object) [
    [
        'href'=>'school',
        'title'=>'Schools'
    ],
    [
        'href'=>'user-role',
        'title'=>'User Role'
    ]
];

define('TAB_DEFAULT_VALUE', $bp_lms_tabs_registered[0]);


function bp_lms_get_tab_page($filename){
    return __DIR__."/tabs/tab-".$filename.".php";
}

function bp_lms_get_template($filename){
    return __DIR__."/templates/".$filename.".tmpl.php";
}

// Get current page url
function bp_lms_get_current_url() {
    global $wp;
    return add_query_arg( $_SERVER['QUERY_STRING'], '', admin_url( 'admin.php', $wp->request ) );
}

// Generate Tab URL path
function bp_lms_generate_tab_url($tab_name){
    return add_query_arg( array(
        'page'  => 'buddypress-lms-integration',
        'tab'   => $tab_name,
    ), admin_url('admin.php') );
}

// Get default selected tab
function bp_lms_get_default_tab($is_redirect) {
    $path = bp_lms_generate_tab_url(TAB_DEFAULT_VALUE);
    if($is_redirect){
        wp_redirect($path);
    }else{
        return $path;
    }
}
