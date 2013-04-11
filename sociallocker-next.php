<?php
/**
Plugin Name: OnePress Social Locker
Plugin URI: http://onepress-media.com/plugin/social-locker-for-wordpress/get
Description: Social Locker is a set of social buttons and a locker in one bottle. <strong>Give people a reason</strong> why they need to click your social buttons. Ask people to “pay” with a Like/Tweet/+1 to get access to your content, to get discount, to download, to watch a video, to view a funny picture or so. And it will help you to get more likes/tweets/+1s, traffic and customers!
Author: OnePress
Version: 2.1.4
Author URI: http://onepress-media.com/portfolio
*/



// Loads code created via Factory.

require('factory/core/start.php');
$socialLocker = factory_fr105_create_plugin(__FILE__, array(
    'name'      => 'sociallocker-next',
    'title'     => 'Social Locker',
    'version'   => '2.1.4',
    'assembly'  => 'free',
    'api'       => 'http://api.byonepress.com/1.0/',
    'premium'   => 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress'
));

$socialLocker->load('factory/modules/forms', 'forms');
$socialLocker->load('factory/modules/licensing', 'licensing');
$socialLocker->load('factory/modules/updates', 'updates');
$socialLocker->load('factory/modules/onepress', 'onepress');

// Loads rest of code that is created manually via the standard wordpress plugin api.

define('SOCIALLOCKER_PLUGIN_ROOT', dirname(__FILE__));
define('SOCIALLOCKER_PLUGIN_URL', plugins_url( null, __FILE__ ));

if ( is_admin() ) include( SOCIALLOCKER_PLUGIN_ROOT . '/admin/init.php' );
include(SOCIALLOCKER_PLUGIN_ROOT . '/addons/either/either-widget.php');