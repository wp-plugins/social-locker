<?php

class FactoryLicensingFR108Manager {
    
    public $plugin;
    public $data;
    
    public $domain;
    public $site;
    public $secret;
    
    public function __construct( FactoryFR108Plugin $plugin ) {
        $this->plugin = $plugin;
        $this->api = $plugin->options['api'];
        
        // gets a current license data
        $this->data = get_option('fy_license_' . $this->plugin->pluginName, array());
        $this->default = get_option('fy_default_license_' . $this->plugin->pluginName, array());
    
        // a bit fix if some incorrect data goes from a databae
        if ( !is_array($this->data)) { 
            delete_option('fy_license_' . $this->plugin->pluginName);
            $this->data = array();
        }
        
        // sets default license if a license is empty
        if ( empty( $this->data) ) $this->data = $this->default;

        // sets a license type what is used by the plugin
        $this->type = ( !array_key_exists('Category', $this->data) || $this->isExpired() ) 
                ? @$this->default['Category'] 
                : $this->data['Category'];
        
        $this->site = site_url();
        $this->domain = parse_url( $this->site, PHP_URL_HOST );
        $this->secret = get_option('fy_license_site_secret', null);
        $this->build = @$this->data['Build'];
        $this->key = @$this->data['Key'];
        
        add_action('init', array($this, 'checkVerificationRequest'));
                
        if ( is_admin() ) {
            add_filter('factory_fr108_admin_notices-' . $this->plugin->pluginName, array( $this, 'showKeyMessages'), 10, 2); 
        }
    }
    
    public function showKeyMessages( $notices, $plugin ) {
        $closed = get_option('fy_closed_notices', array());
        $exipred = floatval($this->data['Expired']);
  
        if ( $exipred != 0 ) {
            
            $remained = round( ( $this->data['Expired'] - time() ) / (60 * 60 * 24), 2 );
            if ( $remained < 5 && $remained > 0 ) {
                
                $time = 0;
                if ( isset( $closed[$this->plugin->pluginName . '-key-estimate'] ) ) {
                    $time = $closed[$this->plugin->pluginName . '-key-estimate']['time'];
                }
                
                if ( $time + 60*60*24 <= time() ) {
                    
                    if ( $this->type == 'trial' ) {
                    
                        if ( $remained <= 1 ) {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'type'      => 'alert-danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire during the day.',
                                'message'   => 'Don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   

                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => 'Visit License Manager',
                                        'action'    => $this->licenseManagerUrl
                                    ), 
                                    array(
                                        'title'     => 'Hide this message',
                                        'action'    => 'x'
                                    )
                                )
                            );

                        } else {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'type'      => 'alert-danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire in ' . $remained . ' days.',
                                'message'   => 'Please don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   

                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => 'Visit License Manager',
                                        'action'    => $this->licenseManagerUrl
                                    ), 
                                    array(
                                        'title'     => 'Hide this message',
                                        'action'    => 'x'
                                    )
                                )
                            );
                        }  

                    }
                }
            }
            
            if ( $this->isExpired() ) {
                
                $notices[] = array(
                    'id'        => $this->plugin->pluginName . '-key-expired',

                    // content and color
                    'type'      => 'alert-danger',
                    'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" has expired.',
                    'message'   => 'Please purchase another key or delete the current key to use the free version of the plugin.',   

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => 'Visit License Manager',
                            'action'    => $this->licenseManagerUrl
                        )
                    )
                );
            }
        }

        return $notices;
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

        $data = $this->sendPostRequest( $this->api . 'ActivateKey', array('body' => $query) );
        
        if (is_wp_error($data) ) return $data;

        update_option('fy_license_' . $this->plugin->pluginName, $data);
        $this->data = $data;
        
        if ( $this->updates ) $this->updates->checkUpdates();
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
            $this->secret = $data['SiteSecret'];
        }
        
        $this->data = $data;
        if ( $this->updates ) $this->updates->checkUpdates();
        return true;
    }
    
    /**
     * Make attampt to activate one of trial license via the Licensing server.
     * @param string $server Licensing server to get license data.
     */
    public function activateTrial() {
        
        $data = $this->sendPostRequest( $this->api . 'ActivateTrial');
        if (is_wp_error($data) ) return $data;

        update_option('fy_license_' . $this->plugin->pluginName, $data);
        update_option('fy_trial_activated_' . $this->plugin->pluginName, true);
        $this->data = $data;
        
        if ( $this->updates ) $this->updates->checkUpdates();
        return true;
    }
    
    /**
     * Delete current active key for the site.
     */
    public function deleteKey() {

        $data = $this->sendPostRequest( $this->api . 'DeleteKey' );
        if (is_wp_error($data) ) return $data;
        
        delete_option('fy_license_' . $this->plugin->pluginName);
        $this->data = get_option('fy_default_license_' . $this->plugin->pluginName, array());
        
        if ( $this->updates ) $this->updates->checkUpdates();
        return true;
    }
    
    public function deleteKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        if ( $data['SiteSecret'] == $this->secret ) {
            delete_option('fy_license_' . $this->plugin->pluginName);
            $this->data = get_option('fy_default_license_' . $this->plugin->pluginName, array());
            return true;
        };
        
        if ( $this->updates ) $this->updates->checkUpdates();
        return false;
    }    
    
    public function getLinkToActivateTrial() {
        
        $query = array(
            'plugin'    => $this->plugin->pluginName,
            'site'      => $this->site,
            'secret'    => $this->secret,
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
        );
        
        $secretToken = $this->openVerificationGate();
        $query['secretToken'] = $secretToken;
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array( 'request' => $request ), $this->api . 'ActivateTrialManualy' );
    }
    
    public function getLinkToActivateKey( $key ) {
        
        $query = array(
            'key'       => $key,
            'plugin'    => $this->plugin->pluginName,
            'site'      => $this->site,
            'secret'    => $this->secret,
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version, 
        );
        
        $secretToken = $this->openVerificationGate();
        $query['secretToken'] = $secretToken;

        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->api . 'ActivateKeyManualy');
    } 
    
    public function getLinkToDeleteKey() {
        
        $query = array(
            'plugin'    => $this->plugin->pluginName,
            'site'      => $this->site,
            'secret'    => $this->secret,
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version
        );
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->api . 'DeleteKeyManualy');
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
            $this->secret = $data['SiteSecret'];
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
        $args['body']['secret'] = $this->secret;
        $args['body']['site'] = $this->site;
        $args['body']['plugin'] = $this->plugin->pluginName; 
        $args['body']['assembly'] = $this->plugin->build;
        $args['body']['version'] = $this->plugin->version;

        $secretToken = $this->openVerificationGate();
        $args['body']['secretToken'] = $secretToken;

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
    
    public function isExpired() {
        if ( !isset( $this->data['Expired'] ) || empty($this->data['Expired']) ) return false;
        $expired = (int)$this->data['Expired'];
        if ( $expired == 0 ) return false;
        
        return $this->data['Expired'] - time() <= 0;
    }
}