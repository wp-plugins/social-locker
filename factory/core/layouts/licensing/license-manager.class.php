<?php

class FactoryFR102LicenseManager {
    
    public $plugin;
    public $data;
    
    public $domain;
    public $site;
    public $siteSecret;
    
    /**
     * Server to manage licenses.
     * @var string 
     */
    public $server;
    
    /**
     * Data about the last version check via the Server.
     * @var type 
     */
    public $versionCheck;
    
    public function __construct( FactoryFR102Plugin $plugin, $server ) {
        $this->plugin = $plugin;
        $this->server = $server;
        
        // gets a current license data
        $this->data = get_option('fy_license_' . $this->plugin->pluginName, array());
        $defaultData = get_option('fy_default_license_' . $this->plugin->pluginName, array());
        
        // a bit fix if some incorrect data goes from a databae
        if ( !is_array($this->data)) { 
            delete_option('fy_license_' . $this->plugin->pluginName);
            $this->data = array();
        }
        
        // gets default license if a license is empty
        if ( empty( $this->data) ) $this->data = $defaultData;
        
        // sets a license type what is used by the plugin
        $this->type = ( !isset($this->data['Category']) || $this->isExpired() ) 
                ? $defaultData['Category'] 
                : $this->data['Category'];
        
        $this->versionCheck = get_option('fy_version_check_' . $this->plugin->pluginName, array());

        $this->site = site_url();
        $this->domain = parse_url( $this->site, PHP_URL_HOST );
        $this->siteSecret = get_option('fy_license_site_secret', null);
        
        // an action to check a validate request from the licensing server
        add_action('plugins_loaded', array( $this, 'checkVerificationRequest' ));
        // an action that is called by the cron
        add_action('fy_check_upadates_' . $this->plugin->pluginName, array($this, 'checkUpdates'));
                
        if ( !is_admin() ) return;
        
        // show trial estimate message
        if ( $this->type == 'trial' ) {
            add_action('admin_notices', array($this, 'showEstimateMessageAction' )); 
        }
        
        // checks if a current key expired
        if ( $this->isExpired() ) {
            add_action('admin_notices', array($this, 'showExpiredMessageAction' )); 
        }
        
        // if the license build and the plugin build are not equal
        if ( $this->isInvalidBuild() ) {
            $this->updatePluginTransient();
            add_action('admin_notices', array($this, 'showChangeBuildMessageAction' )); 
        }

        // checks updates via the licensing server if it's not a free build
        // otherwise updates pull from wordpress.org
        if ( ( !empty( $this->data ) && $this->data['Build'] != 'free' ) || $this->plugin->build != 'free' ) {
            add_filter('pre_set_site_transient_update_plugins', array($this, 'updatePluginAction'));
            add_filter('plugins_api', array($this, 'getPluginInfoAction'), 10, 3);
            
            add_action('admin_init', array($this, 'replacePluginUpdateRow'), 99);
        }
    }
    
    public function replacePluginUpdateRow() {
        remove_action("after_plugin_row_" . $this->plugin->relativePath, 'wp_plugin_update_row');
        add_action("after_plugin_row_" . $this->plugin->relativePath, array($this, 'renderPluginUpdateRow'), 10, 2);
    }
    
    // -------------------------------------------------------------------------------------
    // Activation and deactivation
    // -------------------------------------------------------------------------------------
    
    /**
     * Calls from the main plugin class when the plugin is being activated.
     */
    public function runCron() {

        if ( !wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            wp_schedule_event( time(), 'hourly', 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
    }
    
    /**
     * Calls from the main plugin class when the plugin is being deactivated.
     */
    public function stopCron() {
        
        if ( wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            $timestamp = wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ); 
            wp_unschedule_event( $timestamp, 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
    }

    // -------------------------------------------------------------------------------------
    // Domain verification
    // -------------------------------------------------------------------------------------
    
    /**
     * Checks input request from the Licensing Server that 
     * action: plugins_loaded
     */
    public function checkVerificationRequest() {

        $gateToken = isset( $_GET['fy_gate_token'] ) ? $_GET['fy_gate_token'] : null;
        if ( empty($gateToken) ) return;
        
        $expectedToken = get_option('fy_license_gate_token');
        $tokenExpired = (int)get_option('fy_license_gate_expired');
        
        if ( time() > $tokenExpired ) { 
            echo "expired";
            exit;
        }
        if ( $expectedToken != $gateToken ) { 
            echo "invalid_token";
            exit;
        }        
        
        echo $gateToken . '_valid_ok';
        exit;
    }
    
    /**
     * Open a callback gate to verfy site permissions to manage a domain.
     */
    public function openVerificationGate() {
        $token = md5(rand(0, 10000));
        update_option('fy_license_site_secret', null);
        update_option('fy_license_gate_token', $token);
        update_option('fy_license_gate_expired', time() + (60 * 60));
        return $token;
    }
    
    // -------------------------------------------------------------------------------------
    // Key managment
    // -------------------------------------------------------------------------------------
    
    /**
     * Trying to apply a given license key.
     * @param string $key License key to apply.
     * @param string $server Licensing server to get license data.
     */
    public function activateKey( $key) {
        
        $query = array(
            'key' => $key
        );

        $data = $this->sendPostRequest( $this->server . 'ActivateKey', array('body' => $query) );
        
        if (is_wp_error($data) ) return $data;

        update_option('fy_license_' . $this->plugin->pluginName, $data);
        $this->data = $data;
        
        $this->checkUpdates();
        return true;
    }
    
    public function activateKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        $data['Description'] = base64_decode( str_replace( ' ', '+', $data['Description'] ));
        update_option('fy_license_' . $this->plugin->pluginName, $data);     
        
        if ( isset( $data['SiteSecret'] ) && !empty( $data['SiteSecret'] ) ) {
            update_option('fy_license_site_secret', $data['SiteSecret']);
            $this->siteSecret = $data['SiteSecret'];
        }
        
        $this->data = $data;
        $this->checkUpdates();
        return true;
    }
    
    /**
     * Clears any license data.
     */
    public function clearLicenseData() {
        delete_option('fy_license_' . $this->plugin->pluginName);

        delete_option('fy_license_site_secret');
        delete_option('fy_license_gate_token');
        delete_option('fy_license_gate_expired');
        delete_option('fy_trial_activated_' . $this->plugin->pluginName);
        
        $this->versionCheck = array();
        $this->data = array();
        $this->siteSecret = null;
        
        $this->clearVersionCheck();
    }
    
    public function clearVersionCheck() {
        delete_option('fy_version_check_' . $this->plugin->pluginName);

        $transient = $this->updatePluginAction( get_site_transient('update_plugins') );
        if ( !empty( $transient) ) {
            unset($transient->response[$this->plugin->relativePath]);
            factory_fr102_set_site_transient('update_plugins', $transient);  
        }
    }

    /**
     * Make attampt to activate one of trial license via the Licensing server.
     * @param string $server Licensing server to get license data.
     */
    public function activateTrial() {
        
        $data = $this->sendPostRequest( $this->server . 'ActivateTrial');
        if (is_wp_error($data) ) return $data;

        update_option('fy_license_' . $this->plugin->pluginName, $data);
        update_option('fy_trial_activated_' . $this->plugin->pluginName, true);
        $this->data = $data;
        
        $this->checkUpdates();
        return true;
    }
    
    /**
     * Delete current active key for the site.
     */
    public function deleteKey() {

        $query = array(
            'pluginName'    => $this->plugin->pluginName,
            'siteUrl'       => $this->site,
            'siteSecret'    => $this->siteSecret
        );

        $data = $this->sendPostRequest( $this->server . 'DeleteKey', array('body' => $query) );
        if (is_wp_error($data) ) return $data;
        
        delete_option('fy_license_' . $this->plugin->pluginName);
        $this->data = get_option('fy_default_license_' . $this->plugin->pluginName, array());
        
        $this->checkUpdates();
        return true;
    }
    
    public function deleteKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        if ( $data['SiteSecret'] == $this->siteSecret ) {
            delete_option('fy_license_' . $this->plugin->pluginName);
            $this->data = get_option('fy_default_license_' . $this->plugin->pluginName, array());
            return true;
        };
        
        $this->checkUpdates();
        return false;
    }    
    
    public function getLinkToActivateTrial() {
        
        $query = array(
            'pluginName'    => $this->plugin->pluginName,
            'siteUrl'       => $this->site,
            'siteSecret'    => $this->siteSecret
        );
        
        if ( empty( $this->siteSecret ) ) {
            $secretToken = $this->openVerificationGate();
            $query['secretToken'] = $secretToken;
        }
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array( 'request' => $request ), $this->server . 'ActivateTrialManualy' );
    }
    
    public function getLinkToActivateKey( $key ) {
        
        $query = array(
            'key'           => $key,
            'pluginName'    => $this->plugin->pluginName,
            'siteUrl'       => $this->site,
            'siteSecret'    => $this->siteSecret
        );
        
        if ( empty( $this->siteSecret ) ) {
            $secretToken = $this->openVerificationGate();
            $query['secretToken'] = $secretToken;
        }
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->server . 'ActivateKeyManualy');
    } 
    
    public function getLinkToDeleteKey() {
        
        $query = array(
            'pluginName'    => $this->plugin->pluginName,
            'siteUrl'       => $this->site,
            'siteSecret'    => $this->siteSecret
        );
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->server . 'DeleteKeyManualy');
    }     
    
    // ---------------------------------------------------------------------------------
    // Updates checking
    // ---------------------------------------------------------------------------------
    
    /**
     * Checks updates on the Server.
     * @return type
     */
    public function checkUpdates() {
        
        $query = array(
            'pluginName'    => $this->plugin->pluginName,
            'buildName'     => empty( $this->data['Build'] ) ? $this->plugin->build : $this->data['Build']
        );

        $data = $this->sendPostRequest( $this->server . 'GetCurrentVersion', array('body' => $query) );

        if ( is_wp_error( $data ) )  {
            $result = array();
            $result['Checked'] = time();
            update_option('fy_version_check_' . $this->plugin->pluginName, $result);
            $this->versionCheck = $result;  
        } else {
            $data['Checked'] = time();
            update_option('fy_version_check_' . $this->plugin->pluginName, $data);
            $this->versionCheck = $data;        
        }

        $this->updatePluginTransient();
        if (is_wp_error($data) ) return $data;
        
        return true;
    }
    
    /**
     * Calls a basic method to get info about updates and saves it into updates transient.
     */
    public function updatePluginTransient() {
        $transient = $this->updatePluginAction( get_site_transient('update_plugins') );
        factory_fr102_set_site_transient('update_plugins', $transient);
    }
    
    /**
     * Updates a given transient to add info about updates of a current plugin.
     */
    public function updatePluginAction( $transient ) {
        if ( empty( $transient ) ) $transient = new stdClass();
        
        // Migrating from one build to another one

        if ( $this->isInvalidBuild() ) {
            $obj = new stdClass();  
            $obj->slug = $this->plugin->pluginSlug;  
            $obj->new_version = '[ migrate-to-' . $this->data['Build'] . ' ]';  
            
            $obj->url = null;

            $obj->package = $this->server . 'GetPackage?' . http_build_query(array(
                'pluginName' => $this->plugin->pluginName,
                'build' => $this->data['Build'],   
                'siteUrl' => $this->site,
                'siteSecret' => $this->siteSecret
            ));
            
            $transient->response[$this->plugin->relativePath] = $obj; 
            return $transient;
        }
        
        // if we have data about the last version check
        
        if ( isset( $this->versionCheck['Version'] ) ) {
   
            // Nothing to do if the plugin version is the last one
            
            if (version_compare($this->plugin->version, $this->versionCheck['Version'], '>=')) {
                unset($transient->response[$this->plugin->relativePath]);
                return $transient;
            }
            
            // if the plugin version is less then the remote one
            
            $obj = new stdClass();  
            $obj->slug = $this->plugin->pluginSlug;  

            $obj->url = $this->server . 'GetDetails?' . http_build_query(array(
                'versionId' => $this->versionCheck['Id']
            ));  
            
            $obj->package = $this->server . 'GetPackage?' . http_build_query(array(
                'versionId' => $this->versionCheck['Id'],  
                'siteUrl' => $this->site,
                'siteSecret' => $this->siteSecret
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
    public function getPluginInfoAction($false, $action, $arg) {

        if ($arg->slug === $this->plugin->pluginSlug) {  

            $url = $this->server . 'GetDetails?' . http_build_query(array(
                'versionId' => $this->versionCheck['Id']
            ));  
            
            $package = $this->server . 'GetPackage?' . http_build_query(array(
                'versionId' => $this->versionCheck['Id'],  
                'siteUrl' => $this->site,
                'siteSecret' => $this->siteSecret
            ));
            
            $data = $this->sendPostRequest( $url );

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
    
    public function renderPluginUpdateRow( $file, $plugin_data ) {
	$current = get_site_transient( 'update_plugins' );

        // if a new version is available or 
        // if it's a premium version and a user does't submit any key
	if ( !isset( $current->response[ $file ] ) && !$this->needKey() ) {
            return false;
        }
        
	$r = $current->response[ $file ];

	$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
	$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );

	$details_url = self_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $r->slug . '&section=changelog&TB_iframe=true&width=600&height=800');

	$wp_list_table = _get_list_table('WP_Plugins_List_Table');

	if ( is_network_admin() || !is_multisite() ) {
            
            if ( $this->needKey() ) {

                echo '<tr class="onp-activation-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="onp-activation-message">';
                
                $message = __('You use a premium version of the plugin. Please, verify your license key to unlock all its features.');
                $message = apply_filters('factory_fr102_activation_message_' . $this->plugin->pluginName, $message);
                
                echo $message;
                
                echo '</div></td></tr>';
                
            } else {

                echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';

                if ( $this->isInvalidBuild() ) {

                    if ( ! current_user_can('update_plugins') )

                        printf( 
                            __('You changed the license type. Please download the "%1$s" assembly of the plugin to complete the license activation.'), 
                            $this->data['Build']
                        );

                    else if ( empty($r->package) )

                        printf( 
                            __('You changed the license type. Please download the "%1$s" assembly to complete the license activation. <em>Automatic update is unavailable for this plugin.</em>'), 
                            $this->data['Build']
                        );

                    else

                        printf( 
                            __('You changed the license type. Please download the "%1$s" assembly to complete the license activation. <a href="%2$s">Download and install automatically</a>.'), 
                            $this->data['Build'],
                            wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file)     
                        );
                } else {

                    if ( ! current_user_can('update_plugins') )
                            printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a>.'), $plugin_name, esc_url($details_url), esc_attr($plugin_name), $r->new_version );
                    else if ( empty($r->package) )
                            printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a>. <em>Automatic update is unavailable for this plugin.</em>'), $plugin_name, esc_url($details_url), esc_attr($plugin_name), $r->new_version );
                    else
                            printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">update now</a>.'), $plugin_name, esc_url($details_url), esc_attr($plugin_name), $r->new_version, wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file) ); 
                }

                do_action( "in_plugin_update_message-$file", $plugin_data, $r );

                echo '</div></td></tr>';

            }
	}
    }
    
    // -------------------------------------------------------------------------------------
    // Helper methods to send requests
    // -------------------------------------------------------------------------------------
    
    /**
     * Sends a request to the Licensing Server.
     * 
     * @param type $url Url to send a request
     * @param type $args Http request arguments
     * @return \WP_Error
     */
    private function sendRequest( $url, $args = array() ) {
        $response = wp_remote_request ( $url, $args ); 

        if ( is_wp_error($response) ) {
            
            if ( $response->get_error_code() == 'http_request_failed')
                return new WP_Error( 
                    $response->get_error_code(), 
                    'The Licensing server is not found or unresponsive at the moment.' );
            
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

        if ( isset( $data['SiteSecret'] ) && !empty( $data['SiteSecret'] ) ) {
            update_option('fy_license_site_secret', $data['SiteSecret']);
            $this->siteSecret = $data['SiteSecret'];
        }
        
        if ( isset( $data['ErrorCode'] ) ) 
            return new WP_Error( 'license_' . $data['ErrorCode'], $data['ErrorText'] );
        
        return $data;
    }

    /**
     * Sends a post request to the Licensing Server.
     */
    private function sendPostRequest( $url, $args = array() ) {
        $args['method'] = 'POST';
        $args['timeout'] = 60;
        
        if ( !isset($args['body']) ) $args['body'] = array();
        $args['body']['siteSecret'] = $this->siteSecret;
        $args['body']['siteUrl'] = $this->site;
        $args['body']['pluginName'] = $this->plugin->pluginName; 
        
        if ( empty( $this->siteSecret ) ) {
            $secretToken = $this->openVerificationGate();
            $args['body']['secretToken'] = $secretToken;
        }
        
        return $this->sendRequest($url, $args);
    }
    
    // ---------------------------------------------------------------------------------
    // Helper methods to work with keys
    // ---------------------------------------------------------------------------------
    
    public function hasKey() {
        return !empty( $this->data['Key'] );
    }
    
    public function needKey() {
        return $this->plugin->build == 'premium' && !$this->hasKey();
    }
    
    public function hasUpgrade() {
        return 
            in_array($this->data['Category'], array('free', 'trial')) || 
            in_array($this->data['Build'], array('free'));
    }
    
    /**
     * Returns true if a plugin version has been checked up to the moment.
     * @return boolean
     */
    public function isVersionChecked() {
        return !( empty($this->versionCheck) ) && isset( $this->versionCheck['Version'] );
    }
    
    /**
     * Returns true if a plugin version is actual.
     * @return boolean
     */
    public function isActualVersion() {
        if ( $this->isInvalidBuild() ) return false;
        $currentVersion = $this->plugin->version;
        $serverVersion = $this->versionCheck['Version'];
        if ( empty($serverVersion) || empty($serverVersion)  ) return null;
        return (version_compare($currentVersion, $serverVersion, '>='));
    } 
    
    public function isExpired() {
        if ( !isset( $this->data['Expired'] ) || empty($this->data['Expired']) ) return false;
        $expired = (int)$this->data['Expired'];
        if ( $expired == 0 ) return false;
        
        return $this->data['Expired'] - time() <= 0;
    }
    
    public function isInvalidBuild() {
        if ( empty($this->data) ) return false;
        return $this->data['Build'] != $this->plugin->build;
    }
    
    public function showExpiredMessageAction() {
        do_action('fy_expired_message_' . $this->plugin->pluginName);
    }
    
    public function showEstimateMessageAction() {
        $remained = round( ( $this->data['Expired'] - time() ) / (60 * 60 * 24), 2 );
        if ( $remained > 5 ) return;

        do_action('fy_estimate_message_' . $this->plugin->pluginName, $remained);
    }
    
     public function showChangeBuildMessageAction() {
         
        $screen = get_current_screen();
        if ( !empty($screen) && $screen->base == 'plugins' ) return;
        if ( !empty($screen) && $screen->base == 'update' ) return;

        do_action('fy_change_build_message_' . $this->plugin->pluginName);
    }   
}