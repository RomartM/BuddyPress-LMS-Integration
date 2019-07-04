<div class="fabs">
    <div class="bp_lms_lesson_student_note ui-widget-content">
        <div class="bp_lms_lesson_student_note_header">
            <span id="bp_lms_lesson_student_note_head">My Note</span>
            <div class="bp_lms_lesson_student_note_loader"></div>
            <div class="bp_lms_lesson_student_note_close_btn bp_float_right bp_mini_header_button" title="Close">
                <i class="zmdi zmdi-close"></i>
            </div>
            <div class="bp_lms_lesson_student_note_fullscreen_btn bp_float_right bp_mini_header_button" title="Maximize Window">
                <i class="zmdi zmdi-window-maximize"></i>
            </div>
            <div class="bp_float_right bp_lms_separator">
            </div>
            <div class="bp_lms_lesson_student_note_new_note_btn bp_float_right bp_mini_header_button" title="Add New">
                <i class="zmdi zmdi-plus-circle"></i>
            </div>
            <div class="bp_lms_lesson_student_note_close_note_btn bp_float_right bp_mini_header_button" style="display: none" title="Close Sheet">
                <i class="zmdi zmdi-mail-reply"></i>
            </div>
            <div class="bp_lms_lesson_student_note_save_btn bp_float_right bp_mini_header_button" style="display: none" title="Save file">
                <i class="zmdi zmdi-floppy"></i>
            </div>
            <div class="bp_lms_lesson_student_note_delete_btn bp_float_right bp_mini_header_button" style="display: none" title="Delete file">
                <i class="zmdi zmdi-delete"></i>
            </div>
        </div>
        <div class="bp_lms_lesson_student_note_list_wrapper">
        </div>
        <div class="bp_lms_lesson_student_note_new_title">
            <input type="text" id="noteTitle" placeholder="Note Title">
            <?php wp_editor( 'Note Here', 'student_note', $settings = array(
                'quicktags'=> false,
                'tinymce'=> array(
                    "onchange_callback" => "OnStudentNoteChange"
                )
            ));?>
        </div>
        <div class="bp_lms_lesson_student_note_editor_wrapper">
        </div>
    </div>
    <div id="prime" class="fab" title="Note"><i class="prime zmdi zmdi-assignment"></i></div>
</div>