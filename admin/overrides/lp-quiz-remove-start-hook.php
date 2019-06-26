<?php

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