<?php
/**
Plugin Name: OnePress Social Locker
Plugin URI: http://onepress-media.com/plugin/social-locker-for-wordpress/get
Description: Social Locker is a set of social buttons and a locker in one bottle. <strong>Give people a reason</strong> why they need to click your social buttons. Ask people to “pay” with a Like/Tweet/+1 to get access to your content, to get discount, to download, to watch a video, to view a funny picture or so. And it will help you to get more likes/tweets/+1s, traffic and customers!
Author: OnePress
Version: 2.2.3
Author URI: http://onepress-media.com/portfolio
*/



define('SOCIALLOCKER_PLUGIN_ROOT', dirname(__FILE__));
define('SOCIALLOCKER_PLUGIN_URL', plugins_url( null, __FILE__ ));

// Loads Factory Plugin Framework and some modules

require('factory/core/start.php');

global $socialLocker;
$socialLocker = factory_pr108_create_plugin(__FILE__, array(
    'name'      => 'sociallocker-next',
    'title'     => 'Social Locker',
    'version'   => '2.2.3',
    'assembly'  => 'premium',
    'api'       => 'http://api.byonepress.com/1.0/',
    'premium'   => 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress',
    'updates'   => SOCIALLOCKER_PLUGIN_ROOT . '/includes/updates/'
));

$socialLocker->load('factory/modules/forms', 'forms');
$socialLocker->load('factory/modules/licensing', 'licensing');
$socialLocker->load('factory/modules/updates', 'updates');
$socialLocker->load('factory/modules/onepress', 'onepress');

// Loads main plugin code

if ( is_admin() ) include( SOCIALLOCKER_PLUGIN_ROOT . '/admin/init.php' );

include(SOCIALLOCKER_PLUGIN_ROOT . '/includes/addons/either-widget.php');
include(SOCIALLOCKER_PLUGIN_ROOT . '/includes/addons/dynamic-themes.php');
include(SOCIALLOCKER_PLUGIN_ROOT . '/includes/types/social-locker.php');
include(SOCIALLOCKER_PLUGIN_ROOT . '/includes/shortcodes/sociallock-shortcode.php');

