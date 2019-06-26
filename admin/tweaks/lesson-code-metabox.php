<?php

function bp_lms_lesson_code_access()
{
    add_meta_box(
        'bp_lms_lesson_code_box_id',           // Unique ID
        'Lesson Code',  // Box title
        'bp_lms_lesson_code_access_html',  // Content callback, must be of type callable
        'lp_lesson',                   // Post type
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'bp_lms_lesson_code_access');


function bp_lms_lesson_code_access_html($post)
{
    $value = get_post_meta($post->ID, '_bp_lms_lesson_code_meta_key', true);
    $style = 'padding: 5px;
    font-size: 22px;
    margin: 1px;
    width: 100%;
    text-align:center';
    ?>
    <input name="bp_lms_lesson_code_field" placeholder="Lesson Code" maxlength="30" id="bp_lms_lesson_code_field" class="postbox"
           value="<?php echo $value;?>"
           style="<?php echo $style?>">
    <input type="button" onclick="copyTestCodeToClipBoard()" class="button" name="bp_lms_lesson_code_copy" value="Copy" style="margin-top: 5px">
    <?php
}

function bp_lms_lesson_code_save_postdata($post_id)
{
    if (array_key_exists('bp_lms_lesson_code_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_bp_lms_lesson_code_meta_key',
            $_POST['bp_lms_lesson_code_field']
        );
    }
}
add_action('save_post', 'bp_lms_lesson_code_save_postdata');


function bp_lms_lesson_code(){
    $id = LP_Global::course_item();
    $lesson_code_data = get_post_meta($id->get_id(), '_bp_lms_lesson_code_meta_key');
    if(empty($lesson_code_data)){
        echo '<b>Lesson Code not Set</b>';
    }else{
        echo 'Lesson Code:<b>'.$lesson_code_data[0].'</b><br/>';
    }

}

add_action( 'learn-press/content-item-summary/lp_lesson', 'bp_lms_lesson_code', 10 );
