<?php
/**
 * OnePress API
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package onepress-api
 */

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONP_API_305_LOADED')) return;
define('ONP_API_305_LOADED', true);

// Absolute path for the files and resources of the module.
define('ONP_API_305_DIR', dirname(__FILE__));
include(ONP_API_305_DIR. '/api.php');