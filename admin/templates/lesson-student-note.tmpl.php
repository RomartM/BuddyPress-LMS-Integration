<div class="fabs">
    <div class="bp_lms_lesson_student_note">
        <div class="bp_lms_lesson_student_note_header">
            <span id="bp_lms_lesson_student_note_head">My Note</span>
            <div class="bp_lms_lesson_student_note_loader"></div>
            <div class="bp_lms_lesson_student_note_option"><i class="zmdi zmdi-more-vert"></i>
                <ul>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #2196F3" color="blue"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #00bcd4" color="cyan"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #607d8b" color="blue-grey"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #4caf50" color="green"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #8bc34a" color="light-green"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #cddc39" color="lime"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #ffc107" color="amber"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #ff5722" color="deep-orange"></span></li>
                    <li><span class="bp_lms_lesson_student_note_color" style="border:solid 5px #f44336" color="red"></span></li>
                </ul>
            </div>
            <div class="bp_lms_lesson_student_note_export_btn bp_float_right bp_mini_header_button">
                <i class="zmdi zmdi-open-in-new"></i>
            </div>
            <div class="bp_lms_lesson_student_note_new_note_btn bp_float_right bp_mini_header_button">
                <i class="zmdi zmdi-plus-circle"></i>
            </div>
        </div>
        <div class="bp_lms_lesson_student_note_list_wrapper">
            <div class="bp_lms_lesson_note_item"></div>
        </div>
        <div class="bp_lms_lesson_student_note_new_title">
            <input type="text" id="noteNewTitle" placeholder="Note Title">
            <?php wp_editor( 'Note Here', '1-q', $settings = array('quicktags'=>false));?>
        </div>
        <div class="bp_lms_lesson_student_note_editor_wrapper">
        </div>
        <div id="bp_lms_lesson_student_note_converse" class="bp_lms_lesson_student_note_converse">
      <span class="bp_lms_lesson_student_note_msg_item bp_lms_lesson_student_note_msg_item_admin">
            <div class="bp_lms_lesson_student_note_avatar">
              <i class="zmdi zmdi-headset-mic"></i>
            </div>Hi! How may I be of service</span>
            <span class="bp_lms_lesson_student_note_msg_item bp_lms_lesson_student_note_msg_item_user">
            <div class="bp_lms_lesson_student_note_avatar">
              <i class="zmdi zmdi-account"></i>
            </div>Ermm..</span>
        </div>
        <div class="fab_field">
            <a id="fab_listen" class="fab"><i class="zmdi zmdi-mic-outline"></i></a>
            <a id="fab_send" class="fab"><i class="zmdi zmdi-mail-send"></i></a>
            <textarea id="bp_lms_lesson_student_noteSend" name="bp_lms_lesson_student_note_message" placeholder="Write a message" class="bp_lms_lesson_student_note_field bp_lms_lesson_student_note_message"></textarea>
        </div>
    </div>
    <a id="prime" class="fab"><i class="prime zmdi zmdi-plus"></i></a>
</div>