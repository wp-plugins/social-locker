<?php

/**
 * A module that loads and manages OnePress releated services.
 */
class OnePressFR103Module {
    
    public $plugin;
    
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'add_license_link' ) );
        add_filter('factory_fr103_activation_message_' . $plugin->pluginName, array( $this, 'activation_message' ));
        
        // pulse
        $this->pulse = new OnePressFR103PulseManager( $plugin, $this->plugin->options['api'] );
        $this->plugin->pulse = $this->pulse;
    }
    
    function add_license_link($links) {
        $url = onepress_fr103_get_link_license_manager( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">License</a>');
        unset($links['edit']);
        return $links; 
    }
    
    function activation_message($original) {
        $message = __('You use a premium version of the plugin. Please, verify your license key to unlock all its features. <a href="%1$s">Click here</a>.');
        return str_replace("%1\$s", onepress_fr103_get_link_license_manager($this->plugin->pluginName), $message);
    }
}

add_action('factory_fr103_load_onepress', 'onepress_fr103_module_load');
function onepress_fr103_module_load( $plugin ) {
    new OnePressFR103Module( $plugin ); 
}