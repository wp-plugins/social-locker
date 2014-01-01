<?php
/**
 * The file contains a class and a set of helper methods to manage licensing.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

// creating a license manager for each plugin created via the factory
add_action('factory_300_plugin_created', 'factory_licensing_000_plugin_created');
function factory_licensing_000_plugin_created( $plugin ) {
    $manager = new OnpLicensing300_Manager( $plugin );
    $plugin->license = $manager;
}

/**
 * The License Manager class.
 * 
 * @since 1.0.0
 */
class OnpLicensing300_Manager {
    
    /**
     * A plugin for which the manager was created.
     * 
     * @since 1.0.0
     * @var Factory300_Plugin
     */
    public $plugin;
    
    /**
     * Current license data.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    public $data;
    
    /**
     * A domain name of a current site.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $domain;
    
    /**
     * A current site URL.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $site;
    
    /**
     * A current site secret.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $secret;
    
    /**
     * Createas a new instance of the license manager for a given plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( Factory300_Plugin $plugin ) {
        $this->plugin = $plugin;
        $this->api = $plugin->options['api'];
        
        // gets a current license data
        $this->data = get_option('onp_license_' . $this->plugin->pluginName, array());
        $this->default = get_option('onp_default_license_' . $this->plugin->pluginName, array());
    
        // a bit fix if some incorrect data goes from a database
        if ( !$this->checkLicenseDataCorrectness($this->data) ) { 
            delete_option('onp_license_' . $this->plugin->pluginName);
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
        $this->secret = get_option('onp_site_secret', null);
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
        
        add_action('init', array($this, 'verifyRequest'));
        add_action('admin_enqueue_scripts', array($this, 'addLicenseInfoIntoSouceCode'));

        // adding links below the plugin title on the page plugins.php
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'addLicenseLinks' ) );
        
        // adding messages to the plugin row on the page plugins.php
        add_filter('factory_plugin_row_' . $plugin->pluginName, array($this, 'addMessagesToPluginRow'));
        
        // adding notices to display
        if ( is_admin() ) {
            add_filter('factory_notices_300', array( $this, 'addNotices'), 10, 2); 
        }
    }
    
    /**
     * Checks data license correctness.
     * 
     * @since 1.0.0
     * @param mixed[] e $data License data to check.
     * @return boolean
     */
    private function checkLicenseDataCorrectness( $data ) {
        if ( !is_array( $data ) ) return false;
        if ( !isset($data['Category'])) return false;
        if ( !isset($data['Title'])) return false;
        if ( !isset($data['Description'])) return false;   
        return true;
    }
    
    /**
     * Adds a license and build name as javascript variables into the source code of pages.
     * 
     * Calls on the hook 'admin_enqueue_scripts'
     * 
     * @since 1.0.0
     * @return void
     */
    public function addLicenseInfoIntoSouceCode() {
        $licenseType = defined('LICENSE_TYPE') ? LICENSE_TYPE : $this->data['Category'];
        $buildType = defined('BUILD_TYPE') ? BUILD_TYPE : $this->data['Build'];      
        ?>
        <script>
            window['<?php echo $this->plugin->pluginName ?>-license'] = '<?php echo $licenseType ?>';
            window['<?php echo $this->plugin->pluginName ?>-build'] = '<?php echo $buildType ?>';   
        </script>
        <?php
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
    
    // -------------------------------------------------------------------------------------
    // Key managment
    // -------------------------------------------------------------------------------------
    
    /**
     * Trying to apply a given license key.
     * 
     * @since 1.0.0
     * @param string $key License key to apply.
     * @param string $server Licensing server to get license data.
     * @return mixed
     */
    public function activateKey( $key) {
        
        $query = array(
            'key' => $key
        );

        $data = $this->sendPostRequest( $this->api . 'ActivateKey', array('body' => $query) );
        
        if (is_wp_error($data) ) return $data;
        
        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('invalid_license_data', 'The server returned invalid license data.');

        update_option('onp_license_' . $this->plugin->pluginName, $data);
        $this->data = $data;
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    public function activateKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('invalid_license_data', 'The server returned invalid license data.');
        
        $data['Description'] = base64_decode( str_replace( ' ', '+', $data['Description'] ));
        update_option('onp_license_' . $this->plugin->pluginName, $data);     
        
        if ( isset( $data['SiteSecret'] ) && !empty( $data['SiteSecret'] ) ) {
            update_option('onp_site_secret', $data['SiteSecret']);
            $this->secret = $data['SiteSecret'];
        }
        
        $this->data = $data;
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Make attampt to activate one of trial license via the Licensing server.
     * @param string $server Licensing server to get license data.
     */
    public function activateTrial() {
        
        $data = $this->sendPostRequest( $this->api . 'ActivateTrial');
        if (is_wp_error($data) ) return $data;
        
        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('invalid_license_data', 'The server returned invalid license data.');
        
        update_option('onp_license_' . $this->plugin->pluginName, $data);
        update_option('onp_trial_activated_' . $this->plugin->pluginName, true);
        $this->data = $data;
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Delete current active key for the site.
     */
    public function deleteKey() {

        $data = $this->sendPostRequest( $this->api . 'DeleteKey' );
        if (is_wp_error($data) ) return $data;
        
        delete_option('onp_license_' . $this->plugin->pluginName);
        $this->data = get_option('onp_default_license_' . $this->plugin->pluginName, array());
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    public function deleteKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        if ( $data['SiteSecret'] == $this->secret ) {
            delete_option('onp_license_' . $this->plugin->pluginName);
            $this->data = get_option('onp_default_license_' . $this->plugin->pluginName, array());
            return true;
        };
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return false;
    }    
    
    public function getLinkToActivateTrial() {
        
        $query = array(
            'plugin'    => $this->plugin->pluginName,
            'site'      => $this->site,
            'secret'    => $this->secret,
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
            'tracker'   => $this->plugin->tracker
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
            'tracker'   => $this->plugin->tracker
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
            'version'   => $this->plugin->version,
            'tracker'   => $this->plugin->tracker
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
        $response = wp_remote_request ($url, $args); 

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
            update_option('onp_site_secret', $data['SiteSecret']);
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
        $args['body']['tracker'] = $this->plugin->tracker;

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
    
    
    // -------------------------------------------------------------------------------------
    // Links, messages, notices and so on ...
    // -------------------------------------------------------------------------------------
    
    /**
     * Adds the License link below the plugin title.
     * 
     * Calls on the hook 'plugin_action_links_[plugin_name]'
     * 
     * @since 1.0.0
     * @param mixed[] $links
     * @return mixed[]
     */
    function addLicenseLinks($links) {
        $url = onp_licensing_300_get_manager_link( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">License</a>');
        unset($links['edit']);
        return $links; 
    }
    
    /**
     * Adds messages offering to uprade a plugin into the plugin row on the page plugins.php.
     * 
     * Calls on the hook 'factory_plugin_row_[plugin_name]'
     * 
     * @since 1.0.0
     * @param string[] $messages
     * @return string[]
     */
    function addMessagesToPluginRow($messages) {
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
    
    /**
     * Adds notices to display.
     * 
     * Calls on the hook 'factory_notices_[plugin_name]'
     * 
     * @param mixed[] $notices
     * @return mixed[]
     */
    function addNotices( $notices ) {
        
        $closed = get_option('factory_notices_closed', array());
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
                                'type'      => 'alert',
                                'subtype'   => 'danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire during the day.',
                                'message'   => 'Don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   

                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => 'Visit the License Manager',
                                        'action'    => onp_licensing_300_get_manager_link($this->plugin->pluginName, 'index')
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
                                'type'      => 'alert',
                                'subtype'   => 'danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire in ' . $remained . ' days.',
                                'message'   => 'Please don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   

                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => 'Visit the License Manager',
                                        'action'    => onp_licensing_300_get_manager_link($this->plugin->pluginName, 'index')
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
                    'type'      => 'alert',
                    'subtype'   => 'danger',
                    'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" has expired.',
                    'message'   => 'Please purchase another key or delete the current key to use the free version of the plugin.',   

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => 'Visit License Manager',
                            'action'    => onp_licensing_300_get_manager_link($this->plugin->pluginName, 'index')
                        )
                    )
                );
            }
        }
        
        return $notices;
    }
}

/**
 * Renders link to the license manager.
 * 
 * @since 1.0.0
 * @param type $pluginName
 * @param type $action
 */
function onp_licensing_300_manager_link( $pluginName, $action = null ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    echo '?' . http_build_query( $args );
}

/**
 * Gets link to the license manager.
 * 
 * @since 1.0.0
 * @param type $pluginName
 * @param type $action
 */
function onp_licensing_300_get_manager_link( $pluginName, $action = null ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    return '?' . http_build_query( $args );
}