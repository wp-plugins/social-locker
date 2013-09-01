<?php

/**
 * A module that loads and manages OnePress releated services.
 */
class OnePressPR108Module {
    
    public $plugin;
    
    public function __construct( $plugin ) {
        $this->plugin = $plugin;

        if ( $plugin->license ) {
            $plugin->license->licenseManagerUrl = onepress_pr108_get_link_license_manager($plugin->pluginName);
        }

        add_action('plugins_loaded', array( $this, 'langs' ));
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'add_license_link' ) );
        add_filter('factory_pr108_plugin_row-' . $plugin->pluginName, array($this, 'pluginRow'));
        add_filter('factory_pr108_admin_notices-' . $plugin->pluginName, array($this, 'notices'));
    }
    
    function add_license_link($links) {
        if ( !$this->plugin->license ) return $links;
        
        $url = onepress_pr108_get_link_license_manager( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">License</a>');
        unset($links['edit']);
        return $links; 
    }
    
    function langs() {
        // load_plugin_textdomain('onepress', false, basename( dirname( $this->plugin->relativePath ) ) . '/factory/modules/onepress/langs'); 
    }
        
    function notices( $notices ) {
            if ( $this->plugin->license && !$this->plugin->license->hasKey() ) {     
                if ( $this->plugin->license->default['Build'] == 'premium' ) {
                    
                    $notices[] = array(
                        'id'        => $this->plugin->pluginName . '-activate-premium-key',
                        'class'     => $this->plugin->pluginName,

                        // content and color
                        'type'      => 'offer',
                        'header'    => 'Thank you ',
                        'message'   => ' for purchasing <a target="_blank" href="' . $this->plugin->options['premium'] . '" class="highlighted">' . $this->plugin->pluginTitle . '</a>. 
                                        Please verify your license key you got to unlock all the plugin features. Click the button on the right. Feel free to <a target="_blank" href="http://support.onepress-media.com/create-ticket/">contact us</a> if you need help.',

                        // buttons and links
                        'buttons'   => array(
                            array(
                                'title'     => 'verify my license key',
                                'class'     => 'primary',
                                'action'    => onepress_pr108_get_link_license_manager($this->plugin->pluginName, 'index')
                            )
                        )
                    );
                }
            }
        

        
        return $notices;
    }
    
    function pluginRow($messages) {
            if ( $this->plugin->license && !$this->plugin->license->hasKey() ) {     
                if ( $this->plugin->license->default['Build'] == 'premium' ) {
                    $message = __('You use a premium version of the plugin. Please, verify your license key to unlock all its features. <a href="%1$s">Click here</a>.');
                    $message = str_replace("%1\$s", onepress_pr108_get_link_license_manager($this->plugin->pluginName), $message);
                    return array($message);     
                }
            }
        

        
        return $messages;
    }
}

add_action('factory_pr108_load_onepress', 'onepress_pr108_module_load');
function onepress_pr108_module_load( $plugin ) {
    new OnePressPR108Module( $plugin ); 
}