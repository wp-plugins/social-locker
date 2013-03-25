<?php
/**
 * OnePress for Factory
 */

// Module provides function for the admin area only
if ( !is_admin() ) return;

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('ONEPRESS_FR101_LOADED')) return;
define('ONEPRESS_FR101_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('ONEPRESS_FR101_DIR', dirname(__FILE__));
define('ONEPRESS_FR101_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(ONEPRESS_FR101_DIR. '/helper-functions.php');
include(ONEPRESS_FR101_DIR. '/activation.class.php');
    include(ONEPRESS_FR101_DIR. '/pages/license-manager.class.php');



class OnePressFR101Init {
    
    public $plugin;
    
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'add_license_link' ) );
        add_filter('factory_fr102_activation_message_' . $plugin->pluginName, array( $this, 'activation_message' ));
    }
    
    function add_license_link($links) {
        $url = onepress_fr101_get_link_license_manager( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">License</a>');
        unset($links['edit']);
        return $links; 
    }
    
    function activation_message($original) {
        $message = __('You use a premium version of the plugin. Please, verify your license key to unlock all its features. <a href="%1$s">Click here</a>.');
        return str_replace("%1\$s", onepress_fr101_get_link_license_manager($this->plugin->pluginName), $message);
    }
}

add_action('factory_fr102_init', 'onepress_fr101_init');
function onepress_fr101_init( $plugin ) {
    new OnePressFR101Init( $plugin ); 
}

