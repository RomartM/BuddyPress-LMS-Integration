<?php

function bp_lms_test_invitation()
{
    add_meta_box(
        'bp_lms_test_invitation_box_id',    // Unique ID
        'Test Invitation',                 // Box title
        'bp_lms_test_invitation_html',  // Content callback, must be of type callable
        'lp_quiz',                   // Post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bp_lms_test_invitation');


function bp_lms_test_invitation_html($post)
{
    $value = get_post_meta($post->ID, '_bp_lms_test_invitation_meta_key', true);

    include BP_LMS_BASE_PATH. '/admin/templates/user-invite.tmpl.php';
}

function bp_lms_load_chips_user_assets(){
    global $post;
    if(!empty($post->post_type) && is_admin()){
        if($post->post_type=='lp_quiz'){
            wp_enqueue_style('bp-lms-chip-assets-css', plugins_url('../css/quiz-test-user-chips.css', __FILE__));
            wp_enqueue_script('bp-lms-chip-assets-js', plugins_url('../js/quiz-test-user-chips.js', __FILE__), array('jquery'));
        }
    }
}

add_action('admin_enqueue_scripts', 'bp_lms_load_lesson_student_note_assets');

function bp_lms_test_invitation_save_postdata($post_id)
{
    if (array_key_exists('bp_lms_test_invitation_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_bp_lms_test_invitation_meta_key',
            $_POST['bp_lms_test_invitation_field']
        );
    }
}
add_action('save_post', 'bp_lms_test_invitation_save_postdata');
