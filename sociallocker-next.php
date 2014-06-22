<?php 
/**
Plugin Name: OnePress Social Locker
Plugin URI: http://codecanyon.net/item/social-locker-for-wordpress/3667715?ref=OnePress&utm_source=plugin&utm_medium=plugin-uri&utm_campaign=plugin-uri
Description: Social Locker is a set of social buttons and a locker in one bottle. <strong>Give people a reason</strong> why they need to click your social buttons. Ask people to “pay” with a Like/Tweet/+1 to get access to your content, to get discount, to download, to watch a video, to view a funny picture or so. And it will help you to get more likes/tweets/+1s, traffic and customers!
Author: OnePress
Version: 3.5.6
Author URI: http://byoneress.com
*/

if (defined('ONP_SL_PLUGIN_ACTIVE')) return;
define('ONP_SL_PLUGIN_ACTIVE', true);



define('ONP_SL_PLUGIN_DIR', dirname(__FILE__));
define('ONP_SL_PLUGIN_URL', plugins_url( null, __FILE__ ));



// creating a plugin via the factory
require('libs/factory/core/boot.php');
global $sociallocker;
    
    $sociallocker = new Factory320_Plugin(__FILE__, array(
        'name'          => 'sociallocker-next',
        'title'         => 'Social Locker',
        'version'       => '3.5.6',
        'assembly'      => 'free',
        'lang'          => 'en_US',
        'api'           => 'http://api.byonepress.com/1.1/',
        'premium'       => 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress',
        'addons'        => 'http://sociallocker.org/addons',
        'styleroller'   => 'http://sociallocker.org/styleroller',        
        'account'       => 'http://accounts.byonepress.com/',
        'updates'       => ONP_SL_PLUGIN_DIR . '/includes/updates/',
        'tracker'       => /*@var:tracker*/'0ec2f14c9e007ba464c230b3ddd98384'/*@*/,
    ));  
    



// requires factory modules
$sociallocker->load(array(
    array( 'libs/factory/bootstrap', 'factory_bootstrap_320', 'admin' ),
    array( 'libs/factory/font-awesome', 'factory_fontawesome_320', 'admin' ),
    array( 'libs/factory/forms', 'factory_forms_320', 'admin' ),
    array( 'libs/factory/notices', 'factory_notices_321', 'admin' ),
    array( 'libs/factory/pages', 'factory_pages_320', 'admin' ),
    array( 'libs/factory/viewtables', 'factory_viewtables_320', 'admin' ),
    array( 'libs/factory/metaboxes', 'factory_metaboxes_320', 'admin' ),
    array( 'libs/factory/shortcodes', 'factory_shortcodes_320' ),
    array( 'libs/factory/types', 'factory_types_320' ),
    array( 'libs/onepress/api', 'onp_api_320' ),
    array( 'libs/onepress/licensing', 'onp_licensing_321' ),
    array( 'libs/onepress/updates', 'onp_updates_321' )
));

// loading other files
require(ONP_SL_PLUGIN_DIR . '/includes/classes/assets-manager.class.php');
if ( is_admin() ) require( ONP_SL_PLUGIN_DIR . '/admin/init.php' );

#comp merge
require(ONP_SL_PLUGIN_DIR . '/includes/types/sociallocker.php');
require(ONP_SL_PLUGIN_DIR . '/includes/shortcodes/sociallock-shortcode.php');
#endcomp
