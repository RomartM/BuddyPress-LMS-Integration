<?php

// Avoid direct access to the file
defined('ABSPATH') or die('No script kiddies please!');

// Insert Dashboard Assets
require_once(BP_LMS_BASE_PATH.'/admin/component.php');

// Add Admin Dashboard assets
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_javascript');
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_stylesheet');

// Add Admin Dashboard View
add_action('admin_menu', 'bp_lms_admin_dashboard');


// Insert FrontEnd Assets
require_once(BP_LMS_BASE_PATH.'/public/component.php');

// Load FrontEnd stylesheet
add_action('wp_enqueue_scripts', 'bp_lms_load_stylesheet');
