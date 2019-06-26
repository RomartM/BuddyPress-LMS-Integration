<?php

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