<?php
/**
 * Factory Metaboxes
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

// module provides function only for the admin area
if ( !is_admin() ) return;

if (defined('FACTORY_VIEWTABLES_320_LOADED')) return;
define('FACTORY_VIEWTABLES_320_LOADED', true);

define('FACTORY_VIEWTABLES_320_DIR', dirname(__FILE__));
define('FACTORY_VIEWTABLES_320_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_VIEWTABLES_320_DIR . '/viewtable.class.php');
require(FACTORY_VIEWTABLES_320_DIR . '/includes/viewtable-columns.class.php');
#endcomp