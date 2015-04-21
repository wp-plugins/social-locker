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

if (defined('FACTORY_METABOXES_321_LOADED')) return;
define('FACTORY_METABOXES_321_LOADED', true);

define('FACTORY_METABOXES_321_DIR', dirname(__FILE__));
define('FACTORY_METABOXES_321_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_METABOXES_321_DIR . '/metaboxes.php');
require(FACTORY_METABOXES_321_DIR . '/metabox.class.php');
require(FACTORY_METABOXES_321_DIR . '/includes/form-metabox.class.php');
require(FACTORY_METABOXES_321_DIR . '/includes/publish-metabox.class.php');
#endcomp