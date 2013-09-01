<?php
/**
 * OnePress for Factory
 */

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONEPRESS_PR108_LOADED')) return;
define('ONEPRESS_PR108_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONEPRESS_PR108_DIR', dirname(__FILE__));
define('ONEPRESS_PR108_URL', plugins_url(null,  __FILE__ ));

// Module provides function for the admin area only
if ( !is_admin() ) return;

// Includes parts
include(ONEPRESS_PR108_DIR. '/layouts/module.class.php');

include(ONEPRESS_PR108_DIR. '/layouts/functions/helper-functions.php');
include(ONEPRESS_PR108_DIR. '/layouts/pages/license-manager.class.php');
include(ONEPRESS_PR108_DIR. '/layouts/activation.class.php');