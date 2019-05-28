<?php

/*
Plugin Name: BuddyPress LMS Integration
Plugin URI: http://dev.jsredevs-site.ml
Description: Add custom registration fields and Manage User Information
Version: 1.2.0
Author: JSRE Developers
Author URI: http://jsredevelopers.com
Requires at least: WordPress 2.9.1 / BuddyPress 1.2
Tested up to: WordPress 2.9.1 / BuddyPress 1.2
License: GNU/GPL 2
*/

// Avoid direct access to the file
defined('ABSPATH') or die('No script kiddies please!');
define('BP_LMS_BASE_PATH', __DIR__);

ob_clean();
ob_start();

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function plugin_init() {
    require_once('includes/core.php' );
}
add_action( 'bp_include', 'plugin_init' );


// Call init custom field component
require_once('includes/fields.php');

// Register custom fields
register_activation_hook( __FILE__, 'initialize_preset_fields');