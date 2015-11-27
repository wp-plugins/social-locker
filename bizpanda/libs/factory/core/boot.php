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

if (defined('FACTORY_325_LOADED')) return;
define('FACTORY_325_LOADED', true);

define('FACTORY_325_VERSION', '000');

define('FACTORY_325_DIR', dirname(__FILE__));
define('FACTORY_325_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_325_DIR . '/includes/assets-managment/assets-list.class.php');
require(FACTORY_325_DIR . '/includes/assets-managment/script-list.class.php');
require(FACTORY_325_DIR . '/includes/assets-managment/style-list.class.php');

require(FACTORY_325_DIR . '/includes/functions.php');
require(FACTORY_325_DIR . '/includes/plugin.class.php');

require(FACTORY_325_DIR . '/includes/activation/activator.class.php');
require(FACTORY_325_DIR . '/includes/activation/update.class.php');
#endcomp
