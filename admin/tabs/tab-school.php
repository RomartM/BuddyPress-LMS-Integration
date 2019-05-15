<?php
// Generate School Form
$fields = new BP_LMS_XProfile_Field(xprofile_get_field_id_from_name('School'));
$fields->tab = 'school';
$fields->identifier = 'school';
$fields->render_tab_form();


