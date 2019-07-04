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
        wp_enqueue_style('bp-lms-jquery-ui-css', plugins_url('../css/lib/jquery-ui.min.css', __FILE__));
        wp_enqueue_script('bp-lms-jquery-ui', plugins_url('../js/lib/jquery-ui.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('bp-lms-lesson-student-note-assets-js', plugins_url('../js/lesson-student-note.js', __FILE__), array('jquery'), null, true);
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

add_action('wp_ajax_bp_lms_student_note_ajax', 'bp_lms_student_note_ajax');

function bp_lms_student_note_ajax(){
    $data = $_POST['obj'];
    $type = $_POST['type'];
    switch ($type){
        case 'retrieve':
            $lesson_id = $data['lesson_id'];
            $course_id = $data['course_id'];
            $nid =$data['nid'];
            $user_data = get_user_meta(get_current_user_id(), "user_note:{$lesson_id}:{$course_id}:{$nid}");
            echo $user_data[0];
            break;
        case 'update':
            $user_meta_name = "user_note:{$data['lesson_id']}:{$data['course_id']}:{$data['nid']}";
            $callback = update_user_meta(
                get_current_user_id(),
                $user_meta_name,
                json_encode((object) array('title'=> $data['title'], 'obj'=> $data['data_obj'] ))
            );
            echo !empty($callback) ? "success" : "failed";
            break;
        case 'save':
            $user_meta_name = "user_note:{$data['lesson_id']}:{$data['course_id']}:{$data['nid']}";
            $callback = add_user_meta(
              get_current_user_id(),
              $user_meta_name,
                json_encode((object) array('title'=> $data['title'], 'obj'=> $data['data_obj'] ))
            );
            echo !empty($callback) ? "success" : "failed";
            break;
        case 'delete':
            $user_meta_name = "user_note:{$data['lesson_id']}:{$data['course_id']}:{$data['nid']}";
            $callback = delete_user_meta(get_current_user_id(), $user_meta_name);
            echo !empty($callback) ? "success" : "failed";
            break;
        case 'list':
            $data = get_user_meta(get_current_user_id());
            $filtered = array_filter($data, function ($key) {
                return strpos($key, 'user_note:') === 0;
            }, ARRAY_FILTER_USE_KEY);
            $filtered_keys = array_keys($filtered);
            for($i = 0; $i<count($filtered); $i++){
                $da = json_decode($filtered[$filtered_keys[$i]][0]);
                unset($da->obj);
                $filtered[$filtered_keys[$i]][0] = json_encode($da);
            }
            echo json_encode($filtered);
            break;
        default:
            echo "Request not Valid";
    }
    wp_die();
}

