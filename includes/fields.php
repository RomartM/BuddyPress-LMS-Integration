<?php
// Avoid direct access to the file
defined('ABSPATH') or die('No script kiddies please!');

/*
 * Initialization of Custom Preset Fields
 */

// Add Permanent Field
function add_perm_field($id, $field_name){
    // Preset field config template
    $field_args = array(
        'field_group_id' =>1,
        'parent_id' => 0,
        'type' => 'selectbox',
        'description' => '',
        'is_required' => 1,
        'can_delete' => 0,
        'order_by' => '',
        'is_default_option' => null
    );

    // Try to Add User Role field if does not exists
    if (!xprofile_get_field_id_from_name($field_name)){
        $field_args['id'] = $id;
        $field_args['name'] = $field_name;
        // Insert Field
        xprofile_insert_field($field_args);
        // Get field ID's
        $field_id = xprofile_get_field_id_from_name($field_name);
        // Set default field config
        field_config($field_id);
    }
}

// Preset field configuration
function field_config($object_id){
    $object_type = 'field';
    bp_xprofile_add_meta($object_id, $object_type, 'default_visibility', 'loggedin');
    bp_xprofile_add_meta($object_id, $object_type, 'allow_custom_visibility', 'disabled');
    bp_xprofile_add_meta($object_id, $object_type, 'do_autolink', 'on');
}

// Main Initialization for custom fields
function initialize_preset_fields(){

    // Check if function does exists
    if ( ! function_exists( 'xprofile_insert_field' ) ||
        ! function_exists( 'xprofile_get_field_id_from_name') ) {
        return "Error";
    }

    // Add Permanent Fields
    add_perm_field('2', 'School');
    add_perm_field('3', 'User Role');
}