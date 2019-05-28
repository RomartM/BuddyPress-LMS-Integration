<?php

// Admin Dashboard Configuration
include ('config.php');

// Include Custom Profile Field Class
include('buddypress/bp-lms-xprofile-functions.php');
include('buddypress/bp-lms-xprofile-field.php');
include('buddypress/bp-lms-xprofile-field-type.php');
include('buddypress/bp-lms-xprofile-field-selectbox.php');


if(isset($_GET["action"])){
    switch ($_GET["action"]){
        case 'field_settings':
            include 'overrides/bp-school.php';
            break;
    }
}

if( isset($_GET["mode"]) && isset($_GET["field_id"])){
    if($_GET["mode"]==="edit" && $_SERVER["REQUEST_METHOD"]=="POST"){
        $field = xprofile_get_field( $_GET["field_id"] );
        $field->save();
        $field_id = $_GET["field_id"];

        $orignal_cat = $_POST["multiselectbox_option"];
        $modified_cat = $_POST["selectbox_option"];

        $cat_differ = bp_lms_differ($orignal_cat, $modified_cat);
        $cat_excess = $cat_differ->excess;
        $cat_changes = $cat_differ->changes;

        if(count($cat_changes)!==0){
            bp_lms_course_category_inject($cat_changes,"changes");
            echo "C";
        }

        if(count($cat_excess)!==0){
            bp_lms_course_category_inject($cat_excess,"excess");
            echo "E";
        }
    }
}

