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

if (defined('FACTORY_BOOTSTRAP_305_LOADED')) return;
define('FACTORY_BOOTSTRAP_305_LOADED', true);

define('FACTORY_BOOTSTRAP_305_DIR', dirname(__FILE__));
define('FACTORY_BOOTSTRAP_305_URL', plugins_url(null,  __FILE__ ));

// sets version of admin interface
if ( is_admin() ) {
    global $wp_version;
    if ( !defined('FACTORY_FLAT_ADMIN_030800')) {
        define('FACTORY_FLAT_ADMIN_030800', version_compare( $wp_version, '3.8', '>='  ));
    }
}

include_once(FACTORY_BOOTSTRAP_305_DIR . '/functions.php');