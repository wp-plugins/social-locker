<?php
/**
 * The file contains a class and a set of helper methods to manage licensing.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package onepress-api
 */

add_action('onp_api_320_plugin_created', 'onp_api_320_plugin_created');
function onp_api_320_plugin_created( $plugin ) {
    $manager = new OnpApi320_Manager( $plugin );
    $plugin->api = $manager;
}

/**
 * The API Manager class.
 * 
 * @since 1.0.0
 */
class OnpApi320_Manager {
    
    /**
     * A plugin for which the manager was created.
     * 
     * @since 1.0.0
     * @var Factory325_Plugin
     */
    public $plugin;
    
    /**
     * An API server entry point.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $entryPoint;

    /**
     * Createas a new instance of the license api for a given plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        $this->entryPoint = $plugin->options['api'];
                
        add_action('init', array($this, 'verifyRequest'));
        add_action('init', array($this, 'actionFromApiSever'));    
    }
    
    
    // -------------------------------------------------------------------------------------
    // Domain verification
    // -------------------------------------------------------------------------------------
    
    /**
     * Verifies input requests from the Licensing Server.
     * 
     * @since 1.0.0
     * @return void
     */
    public function verifyRequest() {

        $gateToken = isset( $_GET['onp_gate_token'] ) ? $_GET['onp_gate_token'] : null;
        if ( empty($gateToken) ) return;

        $expectedToken = get_option('onp_gate_token');
        $tokenExpired = (int)get_option('onp_gate_expired');
        
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
     * Opens a callback gate to verfy site permissions to manage a domain.
     * 
     * @since 1.0.0
     * @return string
     */
    public function openVerificationGate() {
        $token = md5(rand(0, 10000));
        update_option('onp_gate_token', $token);
        update_option('onp_gate_expired', time() + (60 * 60));
        return $token;
    }
    
    /**
     * Checks a current api action.
     */
    public function actionFromApiSever() {
        $action = isset( $_GET['onp_action'] ) ? $_GET['onp_action'] : null;
        if ( !in_array($action, array('deactivate-key')) ) return;
        
        $siteSecret = isset( $_GET['onp_site_secret'] ) ? $_GET['onp_site_secret'] : null;
        if ( $siteSecret != get_option( 'onp_site_secret', null ) ) return;
        
        do_action('onp_api_action_' . $action );
    }
    
    // -------------------------------------------------------------------------------------
    // Sending requests
    // -------------------------------------------------------------------------------------
    
    /**
     * Sends a request to an API Server.
     * 
     * @param string $url Url to send a request
     * @param mixed $args Http request arguments
     * @param mixed $args Extra options
     * @return \WP_Error
     */
    private function _request( $url, $args = array(), $options = array() ) {
        $response = @wp_remote_request ($url, $args); 

        if ( is_wp_error($response) ) {
            
            if ( $response->get_error_code() == 'http_request_failed')
                return new WP_Error( 
                    'HTTP:' . $response->get_error_code(), 
                    'The Licensing Server is not found or busy at the moment.' );
            
            return new WP_Error( 'HTTP:' . $response->get_error_code(), $response->get_error_message() );
        }

	$response_code = wp_remote_retrieve_response_code( $response );
	$response_message = wp_remote_retrieve_response_message( $response );

	if ( $response_code >= 500 && $response_code <= 510 )
            return new WP_Error( 'API:InternalError', 'An unexpected error occurred during the request. Please contact OnePress support.' );
        
        // checks http errors
	if ( 200 != $response_code && ! empty( $response_message ) )
            return new WP_Error( 'HTTP:' . $response_code, $response_message );

	elseif ( 200 != $response_code )
            return new WP_Error( 'HTTP:' . $response_code, 'Unknown error occurred' );

        // check server errors
        $data = json_decode( $response['body'], true );

        if ( isset( $data['SiteSecret'] ) && !empty( $data['SiteSecret'] ) ) {
            update_option('onp_site_secret', $data['SiteSecret']);
        }

        if ( isset( $data['ErrorCode'] ) ) 
            return new WP_Error( 'API:' . $data['ErrorCode'], $data['ErrorText'] );
        
        return $data;
    }

    /**
    * Sends a post request to an API Server.
     * 
     * @param string $action Action to perform
     * @param mixed $args Http request arguments
     * @param mixed $args Extra options
     * @return \WP_Error
     */
    public function request( $action, $args = array(), $options = array() ) {
        $url = $this->entryPoint . $action;
        
        if ( !isset($args['method'] ) )$args['method'] = 'POST';
        if ( !isset($args['timeout'] ) ) $args['timeout'] = 60;
        if ( !isset($args['body']) ) $args['body'] = array();
        
        if ( !isset( $args['skipBody']) || !$args['skipBody'] ) {
            
            if ( !isset( $args['body']['secret'] ) ) $args['body']['secret'] = get_option('onp_site_secret', null);
            if ( !isset( $args['body']['site'] ) ) $args['body']['site'] = site_url();
            if ( !isset( $args['body']['key'] ) ) $args['body']['key'] = isset( $this->plugin->license ) ? $this->plugin->license->key : null;
            if ( !isset( $args['body']['plugin'] ) ) $args['body']['plugin'] = $this->plugin->pluginName; 
            if ( !isset( $args['body']['assembly'] ) ) $args['body']['assembly'] = $this->plugin->build;
            if ( !isset( $args['body']['version'] ) ) $args['body']['version'] = $this->plugin->version;
            if ( !isset( $args['body']['tracker'] ) ) $args['body']['tracker'] = $this->plugin->tracker;

            if ( !isset( $args['body']['embedded'] ) ) 
                $args['body']['embedded'] = ( isset( $this->plugin->license ) && $this->plugin->license->isEmbedded() ) ? 'true' : 'false';    

            if ( defined('FACTORY_BETA') && FACTORY_BETA ) $args['body']['beta'] = 'true';

            $secretToken = $this->openVerificationGate();
            $args['body']['secretToken'] = $secretToken;  
        }

        return $this->_request($url, $args, $options);
    }
}