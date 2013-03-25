<?php

/**
 * A class to used to send 'pulse' requests.
 * It's used by OnePress to collect statistic about usage our plugins.
 */
class OnePressFR103PulseManager {
    
    /**
     * Current plugin.
     * @var FactoryPlugin
     */
    public $plugin;
    
    /**
     * URL of a server to make API requests.
     * @var type 
     */
    public $server;
    
    public function __construct( FactoryFR103Plugin $plugin, $server ) {
        $this->plugin = $plugin;
        $this->server = $server;

        // an action that is called by the cron to send the pulse requests
        add_action('fy_pulse_' . $this->plugin->pluginName, array($this, 'sendPulse'));
        
        // activation and deactivation hooks
        add_action('factory_fr103_activation_or_update', array($this, 'activationOrUpdateHook'));
        add_action('factory_fr103_deactivation', array($this, 'deactivationHook')); 
    }
    
     // -------------------------------------------------------------------------------------
    // Activation and deactivation
    // -------------------------------------------------------------------------------------
    
    /**
     * Calls on plugin activation or updating.
     */
    public function activationOrUpdateHook() {
        $this->runCron();
    }
    
    /**
     * Calls on plugin deactivation .
     */
    public function deactivationHook() {
        $this->stopCron();
    }  
    
    // -------------------------------------------------------------------------------------
    // Cron
    // -------------------------------------------------------------------------------------
    
    /**
     * Calls from the main plugin class when the plugin is being activated.
     */
    public function runCron() {

        if ( !wp_next_scheduled( 'fy_pulse_' . $this->plugin->pluginName ) ) { 
            wp_schedule_event( time(), 'twicedaily', 'fy_pulse_' . $this->plugin->pluginName );    
        }
    }
    
    /**
     * Calls from the main plugin class when the plugin is being deactivated.
     */
    public function stopCron() {
        
        if ( wp_next_scheduled( 'fy_pulse_' . $this->plugin->pluginName ) ) { 
            $timestamp = wp_next_scheduled( 'fy_pulse_' . $this->plugin->pluginName ); 
            wp_unschedule_event( $timestamp, 'fy_pulse_' . $this->plugin->pluginName );    
        }
    }
    
    // -------------------------------------------------------------------------------------
    // Sending the pulse
    // -------------------------------------------------------------------------------------
    
    /**
     * Base method to send a pulse request.
     */
    public function sendPulse() { 
        $license = $this->plugin->license;
        
        $query = array(
            'siteUrl' => site_url(),
            'pluginName' => $this->plugin->pluginName,
            'version' => $this->plugin->version,
            'assembleForFiles' => $this->plugin->build,
            'assembleForLicenese' => 
                !empty( $license->data['Build'] ) ? $license->data['Build'] : null,
            'licenseName' => 
                !empty( $license->data['Name'] ) ? $license->data['Name'] : null,   
            'licenseCategory' => 
                !empty( $license->data['Category'] ) ? $license->data['Category'] : null
        );
        
        $url = $this->server . 'Pulse';
        
        $request = array(
            'method' => 'POST',
            'body' => $query,
            'timeout' => 10
        );
        
        $response = wp_remote_request ( $url, $request ); 
    }
}