<?php

function bp_lms_test_code_access()
{
    add_meta_box(
        'bp_lms_test_code_box_id',           // Unique ID
        'Test Access Code',  // Box title
        'bp_lms_test_code_access_html',  // Content callback, must be of type callable
        'lp_quiz',                   // Post type
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'bp_lms_test_code_access');


function bp_lms_test_code_access_html($post)
{
    $value = get_post_meta($post->ID, '_bp_lms_test_code_meta_key', true);
    $style = 'padding: 5px;
    font-size: 22px;
    margin: 1px;
    width: 100%;
    text-align:center';
    if(empty($value)){
        $value = substr(wp_generate_uuid4(), 0,8);
    }
    ?>
    <input name="bp_lms_test_code_field" maxlength="8" id="bp_lms_test_code_field" class="postbox"
           value="<?php echo $value;?>"
           style="<?php echo $style?>">
    <input type="button" onclick="generateTestCode()" class="button" name="bp_lms_test_code_generate" value="Generate" style="margin-top: 5px">
    <input type="button" onclick="copyTestCodeToClipBoard()" class="button" name="bp_lms_test_code_copy" value="Copy" style="margin-top: 5px">
    <?php
}

function bp_lms_test_code_save_postdata($post_id)
{
    if (array_key_exists('bp_lms_test_code_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_bp_lms_test_code_meta_key',
            $_POST['bp_lms_test_code_field']
        );
    }
}
add_action('save_post', 'bp_lms_test_taking_info_save_postdata');