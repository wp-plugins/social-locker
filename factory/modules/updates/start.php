<?php
/**
 * Factory Licensing
 * 
 * Factory Licensing is an important part of the Factory that lets to bring autoupdates and 
 * related services based on a license type.
 */

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('FACTORY_UPDATE_FR108S_LOADED')) return;
define('FACTORY_UPDATE_FR108S_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_UPDATE_FR108S_DIR', dirname(__FILE__));
define('FACTORY_UPDATE_FR108S_URL', plugins_url(null,  __FILE__ ));

// Includes parts
include(FACTORY_UPDATE_FR108S_DIR. '/update-manager.class.php');
include(FACTORY_UPDATE_FR108S_DIR. '/update-module.class.php');