<?php

class FactoryFR107UpdateFR107Manager {
    
    /**
     * Current factory plugin.
     * @var FactoryPlugin 
     */
    public $plugin;
    
    /**
     * Current license manger
     * @var FactoryLicenseMananger
     */
    protected $license;
    
    /**
     * Data about the last version check.
     * @var array 
     */
    public $lastCheck;

    /**
     * Current site secret.
     * @var string 
     */
    protected $secret;

    /**
     * Creates an instance of the update manager.
     * @param type $plugin
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin; 
        $this->license = $this->plugin->license;

        $this->lastCheck = get_option('fy_version_check_' . $this->plugin->pluginName, null);

        $this->secret = $this->siteSecret = get_option('fy_license_site_secret', null);
        $this->site = site_url();
        $this->api = $this->plugin->options['api'];
                
        // if a plugin is not licensed, or a user has a license key
        if ( $this->needCheckUpdates() ) {
            
            // an action that is called by the cron to check updates
            add_action('fy_check_upadates_' . $this->plugin->pluginName, array($this, 'checkUpdates')); 
        }
        
        if ( is_admin() ) {
        
            // if the license build and the plugin build are not equal
            if ( $this->needChangeAssembly() ) {

                $this->updatePluginTransient();
                add_filter('factory_fr107_plugin_row-' . $this->plugin->pluginName, array($this, 'showChangeAssemblyPluginRow' ), 10, 3); 
                add_filter('factory_fr107_admin_notices-' . $this->plugin->pluginName, array( $this, 'showAssemblyMessages'), 10, 2);    
            }
            
            
            add_action('admin_notices', array($this, 'clearTransient'));

            if ( $this->needCheckUpdates() || $this->needChangeAssembly() ) {

                // the method that responses for changin plugin transient when it's saved
                add_filter('pre_set_site_transient_update_plugins', array($this, 'changePluginTransient')); 
                // filter that returns info about available updates
                add_filter('plugins_api', array($this, 'getUpdateInfo'), 10, 3);
            }

            // activation and deactivation hooks
            add_action('factory_fr107_activation_or_update-' . $plugin->pluginName, array($this, 'activationOrUpdateHook'));
            add_action('factory_fr107_deactivation-' . $plugin->pluginName, array($this, 'deactivationHook')); 
        }
    }
    
    /**
     * Need to check updates?
     * @return bool
     */
    public function needCheckUpdates() {
        if ( !$this->plugin->license ) return false;
        return $this->plugin->build == 'premium';
    }
    
    /**
     * Need to change a current assembly?
     * @return bool
     */
    public function needChangeAssembly() {
        return $this->plugin->license && ( $this->plugin->build !== $this->plugin->license->build );
    }
    
    /**
     * Returns true if a plugin version has been checked up to the moment.
     * @return boolean
     */
    public function isVersionChecked() {
        return !( empty($this->lastCheck) ) && isset( $this->lastCheck['Version'] );
    }
    
    /**
     * Returns true if a plugin version is actual.
     * @return boolean
     */
    public function isActualVersion() {
        if ( $this->needChangeAssembly() ) return false;
        if ( !$this->needCheckUpdates() ) return true;
        if ( !isset($this->lastCheck['Version']) ) return true;
        
        $currentVersion = $this->plugin->version;
        $serverVersion = $this->lastCheck['Version'];
        if ( empty($serverVersion) || empty($serverVersion)  ) return true;
        return (version_compare($currentVersion, $serverVersion, '>='));
    } 
    
    // -------------------------------------------------------------------------------------
    // Activation and deactivation
    // -------------------------------------------------------------------------------------
    
    /**
     * Calls on plugin activation or updating.
     */
    public function activationOrUpdateHook() {

        // set cron tasks and clear last version check data
        if ( !wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            wp_schedule_event( time(), 'twicedaily', 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->clearUpdates();
    }
    
    /**
     * Calls on plugin deactivation .
     */
    public function deactivationHook() {

        // clear cron tasks and license data
        if ( wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            $timestamp = wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ); 
            wp_unschedule_event( $timestamp, 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->clearUpdates();
    }  
    
    // -------------------------------------------------------------------------------------
    // Checking updates
    // -------------------------------------------------------------------------------------
    
    public function checkUpdates() {

        $query = array(
            'plugin'    => $this->plugin->pluginName,
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
            'site'      => $this->site,
            'key'       => $this->license ? $this->license->key : null,
            'secret'    => $this->secret
        );
        
        $data = $this->sendRequest( $this->api . 'GetCurrentVersion', array('body' => $query ) );

        if ( is_wp_error( $data ) )  {
            $result = array();
            $result['Checked'] = time();
            $result['Error'] = $data->get_error_message();          
            update_option('fy_version_check_' . $this->plugin->pluginName, $result);
            $this->lastCheck = $result;  
        } else {
            $data['Checked'] = time();
            update_option('fy_version_check_' . $this->plugin->pluginName, $data);
            $this->lastCheck = $data;        
        }

        $this->updatePluginTransient();
    }
    
    /**
     * Clears info about updates for the plugin.
     */
    public function clearUpdates() {
        delete_option('fy_version_check_' . $this->plugin->pluginName);
        $this->lastCheck = null;
         
        $transient = $this->changePluginTransient( get_site_transient('update_plugins') );
        if ( !empty( $transient) ) {
            unset($transient->response[$this->plugin->relativePath]);
            factory_fr107_set_site_transient('update_plugins', $transient);  
        }
    }
    
    /**
     * Fix a bug when the message offering to change assembly appears even if the assemble is correct.
     * @return type
     */
    public function clearTransient() {
        $screen = get_current_screen();
        if ( empty($screen) ) return;
        
        if ( in_array( $screen->base, array('plugins', 'update-core') ) ) {
            $this->updatePluginTransient();
        } 
    }
    
    /**
     * Calls a basic method to get info about updates and saves it into updates transient.
     */
    public function updatePluginTransient() {
        $transient = $this->changePluginTransient( get_site_transient('update_plugins') );
        factory_fr107_set_site_transient('update_plugins', $transient);
    }
    
    /**
     * Updates a given transient to add info about updates of a current plugin.
     */
    public function changePluginTransient( $transient ) {
        if ( empty( $transient ) ) $transient = new stdClass();
        
        // migrating from one assembly to another one
        if ( $this->needChangeAssembly() ) {
            
            $obj = new stdClass();  
            $obj->slug = $this->plugin->pluginSlug;  
            $obj->new_version = '[ migrate-to-' . $this->license->build . ' ]';  
            
            $obj->package = $this->api . 'GetPackage?' . http_build_query(array(
                'plugin'   => $this->plugin->pluginName,
                'assembly' => $this->license->build,   
                'site'     => $this->site,
                'secret'   => $this->secret
            ));
            
            $obj->changeAssembly = true;
            
            $transient->response[$this->plugin->relativePath] = $obj; 
            return $transient;
            
        } else {
            
            if ( isset($transient->response[$this->plugin->relativePath]) ) {
                $r = $transient->response[$this->plugin->relativePath];
                if ( property_exists($r, 'changeAssembly') ) {
                    unset($transient->response[$this->plugin->relativePath]);
                    return $transient;
                }
            }
        }
        
        // if we don't need to check update, return original transient data,
        // for example if we use a free version of the plugin that is updated from wordpress.org
        if ( !$this->needCheckUpdates() ) return $transient;
        
        // if we have data about the last version check
        if ( isset( $this->lastCheck['Version'] ) ) {
   
            // nothing to do if the plugin version is the last one
            if (version_compare($this->plugin->version, $this->lastCheck['Version'], '>=')) {
                unset($transient->response[$this->plugin->relativePath]);
                return $transient;
            }
            
            // if the plugin version is less then the remote one
            
            $obj = new stdClass();  
            $obj->slug = $this->plugin->pluginSlug;  

            $obj->url = $this->api . 'GetDetails?' . http_build_query(array(
                'version' => $this->lastCheck['Id']
            ));  
            
            $obj->package = $this->api . 'GetPackage?' . http_build_query(array(
                'versionId' => $this->lastCheck['Id'],  
                'site'      => $this->site,
                'secret'    => $this->secret
            ));
 
            $transient->response[$this->plugin->relativePath] = $obj; 
            return $transient;
        } 
        
        unset($transient->response[$this->plugin->relativePath]);
        return $transient;  
    }

    /**
     * Gets info about update.
     */
    public function getUpdateInfo($false, $action, $arg) {

        if ($arg->slug === $this->plugin->pluginSlug) {  

            // if we need to change a current assembly, then nothing to say about the update
            if ( $this->needChangeAssembly() ) {
                ?>
                <strong>Migration to another plugin assenbly.</strong>
                <?php 
                return $false;
            }
            
            $url = $this->api . 'GetDetails?' . http_build_query(array(
                'version' => $this->lastCheck['Id']
            ));  
            
            $package = $this->api . 'GetPackage?' . http_build_query(array(
                'versionId' => $this->lastCheck['Id'],  
                'site' => $this->site,
                'secret' => $this->secret
            ));

            $data = $this->sendRequest( $url );

            if ( is_wp_error( $data ) ) {
                ?>
                <strong><?php echo $data->get_error_message() ?></strong>
                <?php
                return $false;
            }
            
            $obj = new stdClass();
            $obj->slug = $this->plugin->pluginSlug;
            $obj->homepage = $data['Homepage'];
            $obj->plugin_name = $this->plugin->pluginSlug;
            $obj->new_version = $data['Version'];
            $obj->requires = $data['Requires'];  
            $obj->tested = $data['Tested']; 
            $obj->downloaded = $data['Downloads'];
            $obj->last_updated = date('Y-m-d H:i:s', $data['Registered']);
            $obj->download_link = $package; 
            $obj->sections = array(  
              'Changes' => $data['VersionDescription']
            );  

            return $obj;
        }  
        return $false; 
    }
      
    public function showChangeAssemblyPluginRow( $messages, $file, $plugin_data ) {
        if ( !$this->needChangeAssembly() ) return $messages;

	$current = get_site_transient( 'update_plugins' );
        if ( !isset( $current->response[ $file ] ) ) return $messages;
        $r = $current->response[ $file ];
        
        if ( ! current_user_can('update_plugins') ) {

            $message = sprintf( 
                __('You changed the license type. Please download "%1$s" assembly'), 
                $this->license->build
            );

        } else if ( empty($r->package) ) {

            $message = sprintf( 
                __('You changed the license type. Please download "%1$s" assembly. <em>Automatic update 
                    is unavailable for this plugin.</em>'), 
                $this->license->build
            );

        } else {

            $message = sprintf( 
                __('You successfully changed the license type. Please install another plugin assembly (%1$s). <a href="%2$s">Update it now</a>.'), 
                $this->license->build,
                wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file)     
            );
        }
        
        return array($message);
    }
    
    public function showAssemblyMessages( $notices, $plugin ) {
        
        if ( $this->needChangeAssembly() ) {
            
            $notices[] = array(
                'id'        => $this->plugin->pluginName . '-change-assembly',
                'where'     => array('dashboard', 'edit', 'post'),
                
                // content and color
                'type'      => 'alert-danger',
                'header'    => 'One small step...',
                'message'   => 'You changed a license type for <strong>' . $this->plugin->pluginTitle . '</strong>. 
                                But the license you use now requries another plugin assembly.<br />
                                The plugin will not work fully until you download the proper assembly. 
                                Don\'t worry it takes only 5 seconds and all your data will be saved.',   

                // buttons and links
                'buttons'   => array(
                    array(
                        'title'     => 'Visit Plugins page',
                        'action'    => "plugins.php"
                    )
                )
            );
        }
        
        return $notices;
    }
    
    // -------------------------------------------------------------------------------------
    // Tools
    // -------------------------------------------------------------------------------------

    /** 
     * Sends request to the update server.
     */
    protected function sendRequest($url, $args = array()) {
        
        $args['method'] = 'POST';
        $args['timeout'] = 15;
        
        if ( !isset($args['body']) ) $args['body'] = array();
        $response = wp_remote_request ( $url, $args ); 

        if ( is_wp_error($response) ) {
            
            if ( $response->get_error_code() == 'http_request_failed')
                return new WP_Error( 
                    $response->get_error_code(), 
                    'The Update server is not found or unresponsive at the moment.' );
            
            return new WP_Error( 'http_' . $response->get_error_code(), $response->get_error_message() );
        }

	$response_code = wp_remote_retrieve_response_code( $response );
	$response_message = wp_remote_retrieve_response_message( $response );

        // checks http errors
	if ( 200 != $response_code && ! empty( $response_message ) )
            return new WP_Error( 'http_' . $response_code, $response_message );

	elseif ( 200 != $response_code )
            return new WP_Error( 'http_' . $response_code, 'Unknown error occurred' );

        // check licensing server errors
        $data = json_decode( $response['body'], true );
        
        if ( isset( $data['ErrorCode'] ) ) 
            return new WP_Error( 'license_' . $data['ErrorCode'], $data['ErrorText'] );
        
        return $data;
    }
}

?>
