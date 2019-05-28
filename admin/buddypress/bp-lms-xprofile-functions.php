<?php

function bp_lms_xprofile_create_field_type( $type ) {

    $field = bp_lms_xprofile_get_field_types();
    $class = isset( $field[$type] ) ? $field[$type] : '';
    /**
     * To handle (missing) field types, fallback to a placeholder field object if a type is unknown.
     */
    if ( $class && class_exists( $class ) ) {
        return new $class;
    } else {
        return new BP_XProfile_Field_Type_Placeholder;
    }
}


function bp_lms_xprofile_get_field_types() {
    $fields = array(
        'checkbox'       => 'BP_XProfile_Field_Type_Checkbox',
        'datebox'        => 'BP_XProfile_Field_Type_Datebox',
        'multiselectbox' => 'BP_XProfile_Field_Type_Multiselectbox',
        'number'         => 'BP_XProfile_Field_Type_Number',
        'url'            => 'BP_XProfile_Field_Type_URL',
        'radio'          => 'BP_XProfile_Field_Type_Radiobutton',
        'selectbox'      => 'BP_LMS_XProfile_Field_Type_Selectbox',
        'textarea'       => 'BP_XProfile_Field_Type_Textarea',
        'textbox'        => 'BP_XProfile_Field_Type_Textbox',
        'telephone'      => 'BP_XProfile_Field_Type_Telephone',
    );

    /**
     * Filters the list of all xprofile field types.
     *
     * If you've added a custom field type in a plugin, register it with this filter.
     *
     * @since 2.0.0
     *
     * @param array $fields Array of field type/class name pairings.
     */
    return apply_filters( 'bp_xprofile_get_field_types', $fields );
}