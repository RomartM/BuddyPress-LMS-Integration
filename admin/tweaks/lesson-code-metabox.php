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
        echo '<div class="bp_lms_lesson_code"><b>Lesson Code not Set</b></div>';
    }else{
        echo '<div class="bp_lms_lesson_code">Lesson Code: <b>'.$lesson_code_data[0].'</b></div><br/>';
    }
}

function learn_press_course_item_content_highlighter() {
    global $lp_course, $lp_course_item;

    $item = LP_Global::course_item();

    if ( $item->is_blocked() ) {
        learn_press_get_template( 'global/block-content.php' );

        return;
    }

    $item_template_name = learn_press_locate_template( 'single-course/content-item-' . $item->get_item_type() . '.php');

    if ( file_exists( $item_template_name ) ) {
        learn_press_get_template( 'single-course/content-item-' . $item->get_item_type() . '.php' );
    }
}

function learn_press_content_item_lesson_content_highlighter(){
    the_content();
}

remove_action( 'learn-press/content-item-summary/lp_lesson', 'learn_press_content_item_lesson_content', 10 );

add_action( 'learn-press/content-item-summary/lp_lesson', 'learn_press_content_item_lesson_content_highlighter', 10 );

add_action( 'learn-press/content-item-summary/lp_lesson', 'bp_lms_lesson_code', 10 );
