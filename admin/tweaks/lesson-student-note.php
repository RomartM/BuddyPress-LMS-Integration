<?php


function bp_lms_lesson_student_note(){
    include BP_LMS_BASE_PATH . '/admin/templates/lesson-student-note.tmpl.php';
}

add_action( 'learn-press/content-item-summary/lp_lesson', 'bp_lms_lesson_student_note', 10 );

function bp_lms_load_lesson_student_note_assets(){
    global $post;
    global $user;

    $lesson = LP_Global::is_course_item_type('lp_lesson');
    $lesson_id = LP_Global::course_item();
    if($lesson){
        // Note Data ID format: {Post ID}:{Lesson ID}
        $note_data = get_user_meta($user->ID, $post->ID.':'.$lesson_id->get_id());
        wp_enqueue_style('bp-lms-lesson-student-note-assets-css', plugins_url('../css/lesson-student-note.css', __FILE__));
        wp_enqueue_script('bp-lms-lesson-student-note-assets-js', plugins_url('../js/lesson-student-note.js', __FILE__), array('jquery'));
        wp_localize_script( 'bp-lms-lesson-student-note-assets-js', 'MyNoteObject', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'note_data' => $note_data || [],
            'user_id'   => $user->ID,
            'course_id'  => $post->ID,
            'lesson_id'  => $lesson_id->get_id(),
            'security' => wp_create_nonce( 'Student Note!!' )
        ));
    }
}

add_action('wp_enqueue_scripts', 'bp_lms_load_lesson_student_note_assets');


add_action( 'wp_ajax_my_action', 'my_action_callback' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
function my_action_callback() {
    check_ajax_referer( 'Student Note!!', 'security' );
    echo 'It worked!';
    die();
}
