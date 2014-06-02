<?php
/**
 * Factory Plugin
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

if (defined('FACTORY_310_LOADED')) return;
define('FACTORY_310_LOADED', true);

define('FACTORY_310_DIR', dirname(__FILE__));
define('FACTORY_310_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_310_DIR . '/includes/assets-managment/assets-list.class.php');
require(FACTORY_310_DIR . '/includes/assets-managment/script-list.class.php');
require(FACTORY_310_DIR . '/includes/assets-managment/style-list.class.php');

require(FACTORY_310_DIR . '/includes/functions.php');
require(FACTORY_310_DIR . '/includes/plugin.class.php');

require(FACTORY_310_DIR . '/includes/activation/activator.class.php');
require(FACTORY_310_DIR . '/includes/activation/update.class.php');
#endcomp
