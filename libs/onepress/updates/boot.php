<?php
/**
 * OnePress Updates
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */
 
 // creating an update manager for each plugin created via the factory
if ( !has_action('factory_311_plugin_created', 'onp_updates_307_plugin_created') )
    add_action('factory_311_plugin_created', 'onp_updates_307_plugin_created');

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONP_UPDATES_307_LOADED')) return;
define('ONP_UPDATES_307_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONP_UPDATES_307_DIR', dirname(__FILE__));

load_plugin_textdomain('onepress_updates_000', false, dirname( plugin_basename( __FILE__ ) ) . '/langs');

#comp merge
include(ONP_UPDATES_307_DIR. '/includes/transient.functions.php');
include(ONP_UPDATES_307_DIR. '/updates.php');
#endcomp

