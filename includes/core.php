<?php

// Avoid direct access to the file
defined('ABSPATH') or die('No script kiddies please!');

// Insert Dashboard Assets
require_once(BP_LMS_BASE_PATH.'/admin/component.php');

// Load Plugin Config
require BP_LMS_BASE_PATH.'/admin/config.php';

// Add Admin Dashboard assets
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_javascript');
add_action('admin_enqueue_scripts', 'bp_lms_load_admin_stylesheet');

// Load FrontEnd Assets
require_once(BP_LMS_BASE_PATH.'/public/component.php');

// Load custom post type
require_once(BP_LMS_BASE_PATH . '/admin/overrides/lp-course-postypes.php');

// Load Custom Tabs Assets
require_once(BP_LMS_BASE_PATH . '/admin/overrides/lp-course-tabs.php');

// Load Custom Filter
require_once(BP_LMS_BASE_PATH . '/admin/tweaks/school-filter.php');

// Load Custom School Taxonomy
require_once(BP_LMS_BASE_PATH . '/admin/tweaks/school-taxonomy.php');

// Load Custom User Taxonomy
require_once(BP_LMS_BASE_PATH . '/admin/tweaks/user-roles-taxonomy.php');

// Add Admin Menu
add_action('admin_menu', 'bp_lms_admin_dashboard');

// Load FrontEnd stylesheet
add_action('wp_enqueue_scripts', 'bp_lms_load_stylesheet');

// Override Old LP Course Post_Type
add_action('init','bp_lp_course_post_type');

// Add Custom Taxonomy
add_action('init','bp_lp_custom_school_taxonomy');
add_action('init','bp_lp_custom_user_roles_taxonomy');

// Remove default LearnPress Course tab
remove_action( 'all_admin_notices', 'learn_press_admin_course_tabs' );

// Override old LP Course Tabs
add_action( 'all_admin_notices', 'bp_lp_admin_course_tabs' );

// Add Filter by Course
add_action( 'restrict_manage_posts', 'filter_by_schools_taxonomies');
