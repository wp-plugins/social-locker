<?php
/**
 * Factory Notices
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-notices 
 * @since 1.0.0
 */

// module provides function only for the admin area
if ( !is_admin() ) return;

if (defined('FACTORY_NOTICES_323_LOADED')) return;
define('FACTORY_NOTICES_323_LOADED', true);

define('FACTORY_NOTICES_323_DIR', dirname(__FILE__));
define('FACTORY_NOTICES_323_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_NOTICES_323_DIR . '/ajax.php');
require(FACTORY_NOTICES_323_DIR . '/notices.php');
#endcomp