<?php
/**
 * BuddyPress XProfile Classes.
 *
 * @package BuddyPress
 * @subpackage XProfileClasses
 * @since 2.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Checkbox xprofile field type.
 *
 * @since 2.0.0
 */

class BP_LMS_XProfile_Field_Type_Selectbox extends BP_LMS_XProfile_Field_Type {
    public function __construct()
    {
        parent::__construct();
    }

    public function admin_new_field_html( BP_XProfile_Field $current_field, $control_type = '' ) {
        parent::tab_new_field_html( $current_field, 'selectbox' );
    }
}