<?php

// Admin Dashboard Configuration
include ('config.php');

// Include Custom Profile Field Class
include ('bp-lms-xprofile-field.php');

if( isset($_GET["mode"]) && isset($_GET["field_id"])){
    if($_GET["mode"]==="edit"){
        global $wpdb;
        $bp = buddypress();
        $field = xprofile_get_field( $_GET["field_id"] );
        $field_id = $_GET["field_id"];
        if ( ! empty( $_POST["sort_order_{$field->type}"] ) ) {
            $field->order_by = $_POST["sort_order_{$field->type}"];
        }

        $field->field_order = $wpdb->get_var( $wpdb->prepare( "SELECT field_order FROM {$bp->profile->table_name_fields} WHERE id = %d", $field_id ) );
        if ( ! is_numeric( $field->field_order ) || is_wp_error( $field->field_order ) ) {
            $field->field_order = (int) $wpdb->get_var( $wpdb->prepare( "SELECT max(field_order) FROM {$bp->profile->table_name_fields} WHERE group_id = %d", $group_id ) );
            $field->field_order++;
        }

        // For new profile fields, set the $field_id. For existing profile
        // fields, this will overwrite $field_id with the same value.
        $field_id = $field->save();
        if ( $field->type_obj->do_settings_section() ) {
            $settings = isset( $_POST['field-settings'] ) ? wp_unslash( $_POST['field-settings'] ) : array();
            $field->admin_save_settings( $settings );
        }
        do_action( 'xprofile_fields_saved_field', $field );
    }
}

// Get current selected tab
$bp_lms_selected_tab = filter_input( INPUT_GET, "tab", FILTER_SANITIZE_STRING );

// Load default tab
if(empty($bp_lms_selected_tab) || !in_array($bp_lms_selected_tab, $bp_lms_tabs_registered)){
    //Redirect to default tab
    bp_lms_get_default_tab(true);
}

// Load Dashboard template
require_once('templates/dashboard.tmpl.php');