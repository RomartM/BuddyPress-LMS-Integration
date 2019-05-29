<?php
// Load Frontend Stylesheet
function bp_lms_load_stylesheet(){
    wp_enqueue_style('bp-lms-style', plugins_url('css/bp-lms-style.css', __FILE__));
}

function bp_lms_load_javascript(){
    //load registration script
    wp_enqueue_script('bp-lms-registration-script', plugins_url('js/registration.js', __FILE__));
}
