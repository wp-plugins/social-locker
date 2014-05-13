<?php
/**
 * OnePress Licensing
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

// creating a license manager for each plugin created via the factory
if ( !has_action('factory_309_plugin_created', 'onp_licensing_310_plugin_created') )
    add_action('factory_309_plugin_created', 'onp_licensing_310_plugin_created');

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONP_LICENSING_310_LOADED')) return;
define('ONP_LICENSING_310_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONP_LICENSING_310_DIR', dirname(__FILE__));
define('ONP_LICENSING_310_URL', plugins_url(null,  __FILE__ ));

include(ONP_LICENSING_310_DIR. '/licensing.php');
if ( !is_admin() ) return;
include(ONP_LICENSING_310_DIR. '/includes/license-manager.class.php');