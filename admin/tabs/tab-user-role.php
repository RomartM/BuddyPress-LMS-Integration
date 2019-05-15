<?php
// Generate School Form
$fields = new BP_LMS_XProfile_Field(xprofile_get_field_id_from_name('User Role'));
$fields->tab = 'user-role';
$fields->identifier = 'user-role';
$fields->render_tab_form();
