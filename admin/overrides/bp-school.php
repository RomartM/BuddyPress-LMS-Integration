<?php
    $back_url = add_query_arg( array(
            'taxonomy'      => 'course_school',
            'post_type' => 'lp_course'
        ), admin_url('edit-tags.php') );
?>
<div class="wrap nosubsub">
    <h1 class="wp-heading-inline">School Field Settings</h1>
    <a href="<?php echo $back_url;?>" title="Go back to Previous">Go back to School List</a>
<?php
$fields = new BP_LMS_XProfile_Field(xprofile_get_field_id_from_name('School'));
$fields->tab = 'school';
$fields->identifier = 'school';
$fields->render_tab_form();
?>
</div>
