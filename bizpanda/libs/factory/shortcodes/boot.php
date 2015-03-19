<?php
/**
 * Factory Shortcodes
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

if (defined('FACTORY_SHORTCODES_320_LOADED')) return;
define('FACTORY_SHORTCODES_320_LOADED', true);

define('FACTORY_SHORTCODES_320_DIR', dirname(__FILE__));

#comp merge
require(FACTORY_SHORTCODES_320_DIR . '/shortcodes.php');
require(FACTORY_SHORTCODES_320_DIR . '/shortcode.class.php');
#endcomp
