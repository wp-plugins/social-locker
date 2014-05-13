<?php
/**
 * OnePress API
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package onepress-api
 */

// creating an api manager for each plugin created via the factory
if ( !has_action('factory_309_plugin_created', 'onp_api_308_plugin_created') )
    add_action('factory_309_plugin_created', 'onp_api_308_plugin_created');

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONP_API_308_LOADED')) return;
define('ONP_API_308_LOADED', true);

// Absolute path for the files and resources of the module.
define('ONP_API_308_DIR', dirname(__FILE__));
include(ONP_API_308_DIR. '/api.php');