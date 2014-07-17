<?php
/**
 * Factory Bootstrap
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-bootstrap 
 * @since 1.0.0
 */

// module provides function only for the admin area
if ( !is_admin() ) return;

if (defined('FACTORY_BOOTSTRAP_323_LOADED')) return;
define('FACTORY_BOOTSTRAP_323_LOADED', true);

define('FACTORY_BOOTSTRAP_323_DIR', dirname(__FILE__));
define('FACTORY_BOOTSTRAP_323_URL', plugins_url(null,  __FILE__ ));

// sets version of admin interface
define('FACTORY_BOOTSTRAP_323_VERSION', 'FACTORY_BOOTSTRAP_323');
if ( !defined('FACTORY_FLAT_ADMIN')) define('FACTORY_FLAT_ADMIN', true);

include_once(FACTORY_BOOTSTRAP_323_DIR . '/includes/functions.php');