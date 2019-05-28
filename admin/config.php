<?php

$bp_lms_tabs_registered = array("school", "user-role", "user-manage");

$bp_lms_tabs_nav_object = (object) [
    [
        'href'=>'school',
        'title'=>'Schools'
    ],
    [
        'href'=>'user-role',
        'title'=>'User Role'
    ],
    [
        'href'=>'user-manage',
        'title'=>'Manage Users'
    ]
];

define('TAB_DEFAULT_VALUE', $bp_lms_tabs_registered[0]);


function bp_lms_get_tab_page($filename){
    return __DIR__."/tabs/tab-".$filename.".php";
}

function bp_lms_get_template($filename){
    return __DIR__."/templates/".$filename.".tmpl.php";
}

// Add Option Field
function add_option_field($id, $field_name){
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
    }
}

// Get current page url
function bp_lms_get_current_url() {
    global $wp;
    return add_query_arg( $_SERVER['QUERY_STRING'], '', admin_url( 'admin.php', $wp->request ) );
}

// Generate Tab URL path
function bp_lms_generate_tab_url($tab_name){
    return add_query_arg( array(
        'page'  => 'buddypress-lms-integration',
        'tab'   => $tab_name,
    ), admin_url('admin.php') );
}

// Get difference of two arrays
function bp_lms_differ($original, $modified){
    $changes = array();
    for($i = 1; $i<=count($original); $i++){
        if($original[$i]!==$modified[$i]){
            array_push($changes, $original[$i].":".$modified[$i]);
		}
    }
    return (object) [
        "changes"   =>  $changes,
        "excess"    =>  array_slice($modified, count($original), count($modified))
    ];
}

function bp_lms_get_option_id($field_id, $option_name){
    $field = new BP_XProfile_Field( $field_id );
    $children = $field->get_children();
    if ( $children ) {
        foreach ( $children as $child ) {
            if($child->name===$option_name){
                return $child->id;
            }
        }
    }
}


function bp_lms_get_cat_id($cat_name){
    $cat = get_term_by( 'name', $cat_name, 'course_category' );
    if ( $cat ) {
        return $cat->term_id;
    }
    return 0;
}

function bp_lms_delete_category( $cat_ID ) {
    return wp_delete_term( $cat_ID, 'course_category' );
}


// Inject preset taxonomy for LearnPress Course
function bp_lms_course_category_inject($cat_arr, $type,  $cat_parent='', $cat_description=''){
    if($type==="excess"){
        // Insert new category
        foreach ($cat_arr as $cat_item) {
            wp_insert_category(
                array(
                    'taxonomy'  =>  'course_category',
                    'cat_name'  =>  $cat_item,
                    'category_description'  => $cat_description,
                    'category_nicename'     => $cat_item,
                    'category_parent'       => $cat_parent
                )
            );
        }
    }else{
        foreach ($cat_arr as $cat_item) {
            $split_item = explode(':', $cat_item);
            // Delete if not exist
            if (empty($split_item[1])){
                bp_lms_delete_category(bp_lms_get_cat_id($split_item[0]));
            }else {
                // Overwrite
                wp_insert_category(
                    array(
                        'cat_ID'    =>  bp_lms_get_cat_id($split_item[0]),
                        'taxonomy'  =>  'course_category',
                        'cat_name'  =>  $split_item[1],
                        'category_description'  => $cat_description,
                        'category_nicename'     => strtolower($split_item[1]),
                        'category_parent'       => $cat_parent
                    )
                );
            }
        }
    }

}

// Get default selected tab
function bp_lms_get_default_tab($is_redirect) {
    $path = bp_lms_generate_tab_url(TAB_DEFAULT_VALUE);
    if($is_redirect){
        wp_redirect($path);
    }else{
        return $path;
    }
}
