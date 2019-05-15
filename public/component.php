<?php
// Load Frontend Stylesheet
function bp_lms_load_stylesheet(){
    wp_enqueue_style('bp-lms-style', plugins_url('css/bp-lms-style.css', __FILE__));
}