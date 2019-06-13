
<?php

function accessProtected($obj, $prop) {
    $reflection = new ReflectionClass($obj);
    $property = $reflection->getProperty($prop);
    $property->setAccessible(true);
    return $property->getValue($obj);
}

$user_meta = wp_get_current_user();
$user_id = $user_meta->ID;
if (!class_exists("LP_Global")) {
    echo "This functionality would not work without LearnPress Plugin";
}else {
    $lp_user_data = LP_Global::user();
    $lp_user_data->set_id($user_id);
    $user = learn_press_get_user($user_id);
    $lp_course_data = $lp_user_data->get_purchased_courses();

    ?>
    <table class="gradebook-list">
        <thead>
        <tr>
            <th class="course-item-user fixed-column">
                <?php _e('Course', 'learnpress-gradebook'); ?>
            </th>
            <th class="user-grade fixed-column">
                <?php _e('Completed', 'learnpress-gradebook'); ?>
            </th>
            <th class="user-grade fixed-column">
                <?php _e('Status', 'learnpress-gradebook'); ?>
            </th>
            <th class="user-grade fixed-column">
                <?php _e('Grade', 'learnpress-gradebook'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($lp_course_data->get_items() as $item_m) {
            $course_id = accessProtected($item_m, '_data')['ID'];
            $course_data = $lp_user_data->get_course_data($course_id);
            $course_results = $course_data->get_results(false);
            ?>
            <tr>
                <th class="course-item-user fixed-column">
                    <?php echo accessProtected($item_m, '_data')['post_title']; ?>
                </th>
                <th class="user-grade fixed-column">
                    <?php echo sprintf('%d/%d', $course_data->get_completed_items(), !empty($course_data->get_items()) ? sizeof($course_data->get_items()) : 0); ?>
                </th>
                <th class="user-grade fixed-column">
                    <?php echo $course_data->get_percent_result();
                    learn_press_label_html($course_data->get_status_label()); ?>
                </th>
                <th class="user-grade fixed-column">
                    <?php echo $course_data->get_grade()=='in-progress'? '-': $course_data->get_grade(); ?>
                </th>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
