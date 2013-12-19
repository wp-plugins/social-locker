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

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONEPRESS_UPDATES_000_LOADED')) return;
define('ONEPRESS_UPDATES_000_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONEPRESS_UPDATES_000_DIR', dirname(__FILE__));

#comp merge
include(ONEPRESS_UPDATES_000_DIR. '/includes/transient.functions.php');
include(ONEPRESS_UPDATES_000_DIR. '/updates.php');
#endcomp