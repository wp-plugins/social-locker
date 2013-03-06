<?php

class FactoryFR100LicenseManager {
    
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
    
    public function __construct( FactoryFR100Plugin $plugin, $server ) {
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
        
        add_action('plugins_loaded', array( $this, 'checkActions' ));
        add_action('fy_check_upadates_' . $this->plugin->pluginName, array($this, 'checkUpdates'));
                
        if ( !is_admin() ) return;
        
        // show trial estimate message
        if ( $this->type == 'trial' ) {
            add_action('admin_notices', array($this, 'showEstimateMessageAction' )); 
        }
        
        // checks is key active
        if ( $this->isExpired() ) {
            add_action('admin_notices', array($this, 'showExpiredMessageAction' )); 
        }

        // checks updates from licensing server if it's not the free build
        if ( $this->data['Build'] != 'free' || $this->plugin->build != 'free' ) {
            add_filter('pre_set_site_transient_update_plugins', array($this, 'updatePluginAction'));
            add_filter('plugins_api', array($this, 'getPluginInfoAction'), 10, 3); 
        }
    }
    
    /**
     * Calls from the main plugin class when the plugin is activated.
     */
    public function activationHook() {

        if ( !wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            wp_schedule_event( time(), 'hourly', 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->versionCheck = array();
        delete_option('fy_version_check_' . $this->plugin->pluginName);
    }
    
    /**
     * Calls from the main plugin class when the plugin is deactivated.
     */
    public function deactivationHook() {
        
        if ( wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ) ) { 
            $timestamp = wp_next_scheduled( 'fy_check_upadates_' . $this->plugin->pluginName ); 
            wp_unschedule_event( $timestamp, 'fy_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->versionCheck = array();
        delete_option('fy_version_check_' . $this->plugin->pluginName);
    }
    
    /**
     * Checks input request from the Licensing Server that 
     * @return type
     */
    public function checkActions() {
        
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
        return true;
    }
    
    /**
     * Clears any license data.
     */
    public function clearLicenseData() {
        delete_option('fy_license_' . $this->plugin->pluginName);
        delete_option('fy_version_check_' . $this->plugin->pluginName);
        delete_option('fy_license_site_secret');
        delete_option('fy_license_gate_token');
        delete_option('fy_license_gate_expired');
        delete_option('fy_trial_activated_' . $this->plugin->pluginName);
        
        $this->versionCheck = array();
        $this->data = array();
        $this->siteSecret = null;
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
    // Updates
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

        $transient = get_site_transient('update_plugins');
        $transient = $this->updatePluginAction($transient);
        factory_fr100_set_site_transient('update_plugins', $transient);
        if (is_wp_error($data) ) return $data;
        
        return true;
    }
    
    /**
     * Updates a giver transient to add info about updates of a current plugin.
     * @param type $transient
     * @return type
     */
    public function updatePluginAction( $transient ) {
        if ( empty( $transient ) ) $transient = new stdClass();
        
        if ( isset( $this->versionCheck['Version'] ) ) {
            
            // Moving from one build to another one
            
            if ( $this->versionCheck['Build'] != $this->plugin->build) {
                $obj = new stdClass();  
                $obj->slug = $this->plugin->pluginSlug;  
                $obj->new_version = $this->versionCheck['Build'] . '-' . $this->versionCheck['Version'];  
                $obj->url = $this->versionCheck['DetailsURL'];  
                $obj->package = $this->versionCheck['PackageURL'] . '&siteUrl=' . urldecode( $this->site ) . '&siteSecret=' . $this->siteSecret; 
                $transient->response[$this->plugin->relativePath] = $obj; 
                return $transient;
            }
            
            // Nothing to do if the plugin version is the last one
            
            if (version_compare($this->plugin->version, $this->versionCheck['Version'], '>=')) {
                unset($transient->response[$this->plugin->relativePath]);
                return $transient;
            }
            
            // if the plugin version is less then the remote one
            
            $obj = new stdClass();  
            $obj->slug = $this->plugin->pluginSlug;  
            $obj->new_version = $this->versionCheck['Version'];  
            $obj->url = $this->versionCheck['DetailsURL'];  
            $obj->package = $this->versionCheck['PackageURL'] . '&siteUrl=' . urldecode( $this->site ) . '&siteSecret=' . $this->siteSecret;  
            
            $transient->response[$this->plugin->relativePath] = $obj; 
        } else {
            unset($transient->response[$this->plugin->relativePath]);
        }

        return $transient;  
    }
    
    public function getPluginInfoAction($false, $action, $arg) {
        
        if ($arg->slug === $this->plugin->pluginSlug) {  
            $url = $this->versionCheck['DetailsURL'];
            $data = $this->sendPostRequest( $url );

            if ( is_wp_error( $data ) ) {
                ?>
                <strong><?php echo $error->get_error_message() ?></strong>
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
            $obj->download_link = $data['PackageURL']; 
            $obj->sections = array(  
              'description' => $data['VersionDescription']
            );  

            return $obj;
        }  
        return $false; 
    }
    
    public function hasKey() {
        return !empty( $this->data['Key'] );
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
        if ( $this->plugin->build != $this->versionCheck['Build'] ) return false;
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
    
    
    public function showExpiredMessageAction() {
        do_action('fy_expired_message_' . $this->plugin->pluginName);
    }
    
    public function showEstimateMessageAction() {
        $remained = round( ( $this->data['Expired'] - time() ) / (60 * 60 * 24), 2 );
        if ( $remained > 5 ) return;

        do_action('fy_estimate_message_' . $this->plugin->pluginName, $remained);
    }
}