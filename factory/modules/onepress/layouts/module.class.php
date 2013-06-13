<?php

/**
 * A module that loads and manages OnePress releated services.
 */
class OnePressFR106Module {
    
    public $plugin;
    
    public function __construct( $plugin ) {
        $this->plugin = $plugin;

        if ( $plugin->license ) {
            $plugin->license->licenseManagerUrl = onepress_fr106_get_link_license_manager($plugin->pluginName);
        }

        add_action('plugins_loaded', array( $this, 'langs' ));
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'add_license_link' ) );
        add_filter('factory_fr106_plugin_row-' . $plugin->pluginName, array($this, 'pluginRow'));
        add_filter('factory_fr106_admin_notices-' . $plugin->pluginName, array($this, 'notices'));
    }
    
    function add_license_link($links) {
        if ( !$this->plugin->license ) return $links;
        
        $url = onepress_fr106_get_link_license_manager( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">License</a>');
        unset($links['edit']);
        return $links; 
    }
    
    function langs() {
        // load_plugin_textdomain('onepress', false, basename( dirname( $this->plugin->relativePath ) ) . '/factory/modules/onepress/langs'); 
    }
        
    function notices( $notices ) {
        
        return $notices;
    }
    
    function pluginRow($messages) {
            if ( $this->plugin->license && !$this->plugin->license->hasKey() ) { 
                $current = get_site_transient( 'update_plugins' );
                
                if ( !isset( $current->response[ $this->plugin->relativePath ] ) ) {
                    
                    $message = __('Need more features? Look at a <a target="_blank" href="%1$s">premium version</a> of the plugin.');
                    $message = str_replace("%1\$s", $this->plugin->options['premium'], $message);
                    return array($message);  
                }
            }
        

        
        return $messages;
    }
}

add_action('factory_fr106_load_onepress', 'onepress_fr106_module_load');
function onepress_fr106_module_load( $plugin ) {
    new OnePressFR106Module( $plugin ); 
}