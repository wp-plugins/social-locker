<?php
/**
 * Factory Pages
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

if (defined('FACTORY_PAGES_320_LOADED')) return;
define('FACTORY_PAGES_320_LOADED', true);

define('FACTORY_PAGES_320_DIR', dirname(__FILE__));
define('FACTORY_PAGES_320_URL', plugins_url(null,  __FILE__ ));

if ( !defined('FACTORY_FLAT_ADMIN')) define('FACTORY_FLAT_ADMIN', true);

require(FACTORY_PAGES_320_DIR . '/pages.php');
require(FACTORY_PAGES_320_DIR . '/includes/page.class.php');
require(FACTORY_PAGES_320_DIR . '/includes/admin-page.class.php');