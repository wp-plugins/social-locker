<?php 
/**
Plugin Name: OnePress Social Locker
Plugin URI: http://onepress-media.com/plugin/social-locker-for-wordpress/get
Description: Social Locker is a set of social buttons and a locker in one bottle. <strong>Give people a reason</strong> why they need to click your social buttons. Ask people to “pay” with a Like/Tweet/+1 to get access to your content, to get discount, to download, to watch a video, to view a funny picture or so. And it will help you to get more likes/tweets/+1s, traffic and customers!
Author: OnePress
Version: 3.2.4
Author URI: http://onepress-media.com/portfolio
*/



define('ONP_SL_PLUGIN_DIR', dirname(__FILE__));
define('ONP_SL_PLUGIN_URL', plugins_url( null, __FILE__ ));

#comp merge
// the merge command allows to merge all files into one on compiling

require('libs/factory/core/boot.php');
require('libs/factory/bootstrap/boot.php');
require('libs/factory/font-awesome/boot.php');
require('libs/factory/forms/boot.php');
require('libs/factory/metaboxes/boot.php');
require('libs/factory/notices/boot.php');
require('libs/factory/pages/boot.php');
require('libs/factory/shortcodes/boot.php');
require('libs/factory/types/boot.php');
require('libs/factory/viewtables/boot.php');

require('libs/others/mendeleev-controls/boot.php');
#endcomp



#comp merge
require('libs/onepress/api/boot.php');
require('libs/onepress/licensing/boot.php');
require('libs/onepress/updates/boot.php');
#endcomp

// creating a plugin via the factory
global $sociallocker;
$sociallocker = new Factory306_Plugin(__FILE__, array(
    'name'      => 'sociallocker-next',
    'title'     => 'Social Locker',
    'version'   => '3.2.4',
    'assembly'  => 'free',
    'api'       => 'http://api.byonepress.com/1.1/',
    'premium'   => 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress',
    'account'   => 'http://accounts.byonepress.com/',
    'updates'   => ONP_SL_PLUGIN_DIR . '/includes/updates/',
    'tracker'   => /*@var:tracker*/'0ec2f14c9e007ba464c230b3ddd98384'/*@*/,
));

// loading other files
require(ONP_SL_PLUGIN_DIR . '/includes/classes/assets-manager.class.php');
if ( is_admin() ) require( ONP_SL_PLUGIN_DIR . '/admin/init.php' );

#comp merge
require(ONP_SL_PLUGIN_DIR . '/includes/types/sociallocker.php');
require(ONP_SL_PLUGIN_DIR . '/includes/shortcodes/sociallock-shortcode.php');
#endcomp