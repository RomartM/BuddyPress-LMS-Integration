<?php

// Avoid direct access to the file
defined('ABSPATH') or die('No script kiddies please!');

// Insert Dashboard Assets
require_once(BP_LMS_BASE_PATH.'/admin/component.php');

// Load Plugin Config
require BP_LMS_BASE_PATH.'/admin/config.php';

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

// Load Test Code Meta Box
require_once(BP_LMS_BASE_PATH . '/admin/tweaks/quiz-test-code-metabox.php');

// Load Test Taking Meta Box
require_once(BP_LMS_BASE_PATH . '/admin/tweaks/quiz-test-taking-metabox.php');

// Load User Dashboard Short Code
require_once(BP_LMS_BASE_PATH . '/public/shortcode-dashboard-user.php');

// Add Admin Menu
add_action('admin_menu', 'bp_lms_admin_dashboard');

// Override Old LP Course Post_Type
//add_action('init','bp_lp_course_post_type');

// Add Custom Taxonomy
add_action('init','bp_lp_custom_school_taxonomy');
add_action('init','bp_lp_custom_user_roles_taxonomy');

// Remove default LearnPress Course tab
remove_action( 'all_admin_notices', 'learn_press_admin_course_tabs' );

// Override old LP Course Tabs
add_action( 'all_admin_notices', 'bp_lp_admin_course_tabs' );

// Add Filter by Course
add_action( 'restrict_manage_posts', 'filter_by_schools_taxonomies');

// Add User Dashboard ShortCode
add_shortcode( 'user_dashboard', 'user_dashboard_shortcode' );

function r(){
    remove_action( 'learn-press/quiz-buttons', 'learn_press_quiz_start_button', 10 );
    function learn_press_quiz_start_button_custom() {
        $course = LP_Global::course();
        $user   = LP_Global::user();
        $quiz   = LP_Global::course_item_quiz();

        if ( $user->has_course_status( $course->get_id(), array( 'finished' ) ) || $user->has_quiz_status( array(
                'started',
                'completed'
            ), $quiz->get_id(), $course->get_id() )
        ) {
            return;
        }

        if ( ! $user->has_course_status( $course->get_id(), array( 'enrolled' ) ) && $course->is_required_enroll() && ! $quiz->get_preview() ) {
            return;
        }
        include BP_LMS_BASE_PATH.'/admin/overrides/lp-quiz-start.php';
    }
    add_action( 'learn-press/quiz-buttons', 'learn_press_quiz_start_button_custom', 10 );

}

add_action('init', 'r');