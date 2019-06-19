<?php
/**
 * Template for displaying Start quiz button.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/buttons/start.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$quiz   = LP_Global::course_item_quiz();
?>

<?php do_action( 'learn-press/before-quiz-start-button' ); ?>

    <form name="start-quiz" class="start-quiz" method="post" enctype="multipart/form-data">
        <?php do_action( 'learn-press/begin-quiz-start-button' ); ?>

        <?php
        $access_code_data = get_post_meta($quiz->get_id(), '_bp_lms_test_code_meta_key');
        if(!empty($access_code_data)):
        ?>
        <div style="font-size:16px;font-weight:600;color:#E91E63;margin-bottom: 15px;">
            To start the exam, please provide the access code given by your instructor
        </div>
        <input type="text" name="access-code" placeholder="Access Code" required>
        <?php endif;?>
        <button type="submit" class="button"><?php _e( 'Start', 'learnpress' ); ?></button>

        <?php do_action( 'learn-press/end-quiz-start-button' ); ?>

        <?php LP_Nonce_Helper::quiz_action( 'start-custom', $quiz->get_id(), $course->get_id(), true ); ?>
        <input type="hidden" name="noajax" value="yes">

    </form>

<?php do_action( 'learn-press/after-quiz-start-button' ); ?>