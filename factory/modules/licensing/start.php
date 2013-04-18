<?php
/**
 * Factory Licensing
 * 
 * Factory Licensing is an important part of the Factory that lets to bring autoupdates and 
 * related services based on a license type.
 */

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('FACTORY_LICENSING_FR105_LOADED')) return;
define('FACTORY_LICENSING_FR105_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_LICENSING_FR105_DIR', dirname(__FILE__));
define('FACTORY_LICENSING_FR105_URL', plugins_url(null,  __FILE__ ));

// Includes parts
include(FACTORY_LICENSING_FR105_DIR. '/license-manager.class.php');
include(FACTORY_LICENSING_FR105_DIR. '/license-module.class.php');