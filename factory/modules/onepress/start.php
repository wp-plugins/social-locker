<?php
/**
 * OnePress for Factory
 */

// Module provides function for the admin area only
if ( !is_admin() ) return;

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONEPRESS_FR100_LOADED')) return;
define('ONEPRESS_FR100_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONEPRESS_FR100_DIR', dirname(__FILE__));
define('ONEPRESS_FR100_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(ONEPRESS_FR100_DIR. '/helper-functions.php');
include(ONEPRESS_FR100_DIR. '/activation.class.php');
include(ONEPRESS_FR100_DIR. '/pages/license-manager.class.php');