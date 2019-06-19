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

function bp_lms_remove_start_button(){
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

add_action('init', 'bp_lms_remove_start_button');


function bp_lms_remove_start_quiz_hook(){

    $start_quiz_arr = array(LP_Quiz_Factory::class, 'start_quiz');

    remove_action( 'learn-press/ajax/start-quiz:nopriv', $start_quiz_arr, 5 );
    remove_action( 'learn-press/ajax/no-priv/start-quiz:nopriv', $start_quiz_arr, 5 );
    remove_action( 'learn_press_ajax_handler_start-quiz:nopriv', $start_quiz_arr, 5 );

    remove_action( 'learn_press_request_handler_lp-start-quiz:nopriv', $start_quiz_arr, 5);

    function start_quiz_custom() {
        $access_code = LP_Request::get_string( 'access-code' );
        $course_id = LP_Request::get_int( 'course-id' );
        $quiz_id   = LP_Request::get_int( 'quiz-id' );

        $access_code_data = get_post_meta($quiz_id, '_bp_lms_test_code_meta_key');

        $user      = learn_press_get_current_user();
        if(empty($access_code_data[0]) && empty($access_code)){
            start_quiz_custom_core($quiz_id, $course_id, $user);
        }else{
            if($access_code===$access_code_data[0]){
                start_quiz_custom_core($quiz_id, $course_id, $user);
            }else{
                $result['message']  = "Your access code is invalid";
                $result['result']   = 'error';
                $result['redirect'] = apply_filters( 'learn-press/quiz/start-quiz-failure-redirect', learn_press_get_current_url(), $quiz_id, $course_id, $user->get_id() );
                start_quiz_custom_alert($result);
            }
        }
    }

    function start_quiz_custom_core($quiz_id, $course_id, $user){
        $quiz      = learn_press_get_quiz( $quiz_id );
        $result    = array( 'result' => 'success' );

        try {
            // Actually, no save question here. Just check nonce here.
            $check = LP_Quiz_Factory::maybe_save_questions( 'start-custom' );

            // PHP Exception
            if ( learn_press_is_exception( $check ) ) {
                throw $check;
            }

            $data = $user->start_quiz( $quiz_id, $course_id, true );
            if ( is_wp_error( $data ) ) {
                throw new Exception( $data->get_error_message() );
            } else {

                $redirect           = $quiz->get_question_link( learn_press_get_user_item_meta( $data['user_item_id'], '_current_question', true ) );
                $result['result']   = 'success';
                $result['redirect'] = apply_filters( 'learn-press/quiz/started-redirect', $redirect, $quiz_id, $course_id, $user->get_id() );
            }
        }
        catch ( Exception $ex ) {
            $result['message']  = $ex->getMessage();
            $result['result']   = 'error';
            $result['redirect'] = apply_filters( 'learn-press/quiz/start-quiz-failure-redirect', learn_press_get_current_url(), $quiz_id, $course_id, $user->get_id() );
        }
        start_quiz_custom_alert($result);
    }

    function start_quiz_custom_alert($result){
        learn_press_maybe_send_json( $result );

        if ( ! empty( $result['message'] ) ) {
            learn_press_add_message($result['message'], $result['result']);
        }

        if ( ! empty( $result['redirect'] ) ) {
            wp_redirect( $result['redirect'] );
            exit();
        }
    }

    $action = 'start-custom-quiz';
    $custom_action  = 'start_quiz_custom';
    LP_Request_Handler::register_ajax( $action, $custom_action );
    LP_Request_Handler::register( "lp-{$action}", $custom_action );
}

add_action('init', 'bp_lms_remove_start_quiz_hook');
