<?php
/**
 * Factory Types
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

if (defined('FACTORY_TYPES_305_LOADED')) return;
define('FACTORY_TYPES_305_LOADED', true);

define('FACTORY_TYPES_305_DIR', dirname(__FILE__));
define('FACTORY_TYPES_305_URL', plugins_url(null,  __FILE__ ));

// sets version of admin interface
if ( is_admin() ) {
    global $wp_version;
    if ( !defined('FACTORY_FLAT_ADMIN')) {
        define('FACTORY_FLAT_ADMIN', version_compare( $wp_version, '3.8', '>='  ));
    }
}

#comp merge
require(FACTORY_TYPES_305_DIR . '/types.php');
require(FACTORY_TYPES_305_DIR . '/type.class.php');
require(FACTORY_TYPES_305_DIR . '/includes/type-menu.class.php');
#endcomp