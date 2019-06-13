<div class="bp-lms-search">
    <form method="get">
        <input type="search" name="search_student" placeholder="Student Email Address" <?php echo isset($_GET['search_student'])? "value=\"".$_GET['search_student']."\"":""?>>
        <input type="submit" value="Search">
    </form>
</div>
<?php

function accessProtected($obj, $prop) {
    $reflection = new ReflectionClass($obj);
    $property = $reflection->getProperty($prop);
    $property->setAccessible(true);
    return $property->getValue($obj);
}

if(isset($_GET['search_student'])){
    $user_meta = get_user_by("email", $_GET['search_student']);
    if(empty($user_meta->ID)){
        echo "<div style='padding:60px;text-align:center;'>No student matched</div>";
    }else {
        $user_id = $user_meta->ID;
        if (!class_exists("LP_Global")) {
            echo "This functionality would not work without LearnPress Plugin";
        }else {
            $lp_user_data = LP_Global::user();
            $lp_user_data->set_id($user_id);
            $user = learn_press_get_user($user_id);
            $lp_course_data = $lp_user_data->get_purchased_courses();

            ?>
            <div class="bp-lms-student-information">
                <span>Name: <b><?php echo $user->get_display_name(); ?></b></span>
                <br/>
                <span>Email: <b><?php echo $user->get_email(); ?></b></span>
            </div>
            <div class="bp-lms-sum-student"> Student Taken Course Summary </div>
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
    }
}
