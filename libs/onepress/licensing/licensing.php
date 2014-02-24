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
add_action('factory_305_plugin_created', 'factory_licensing_000_plugin_created');
function factory_licensing_000_plugin_created( $plugin ) {
    $manager = new OnpLicensing305_Manager( $plugin );
    $plugin->license = $manager;
}

/**
 * The License Manager class.
 * 
 * @since 1.0.0
 */
class OnpLicensing305_Manager {
    
    /**
     * A plugin for which the manager was created.
     * 
     * @since 1.0.0
     * @var Factory305_Plugin
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
     * Createas a new instance of the license manager for a given plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( Factory305_Plugin $plugin ) {
        $this->plugin = $plugin;

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
        
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
        
        add_action('admin_enqueue_scripts', array($this, 'addLicenseInfoIntoSouceCode'));

        // adding links below the plugin title on the page plugins.php
        add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'addLicenseLinks' ) );
        
        // adding messages to the plugin row on the page plugins.php
        add_filter('factory_plugin_row_' . $plugin->pluginName, array($this, 'addMessagesToPluginRow'));
        
        // checks data returned from the api server
        add_action('onp_api_ping_' . $plugin->pluginName, array($this, 'apiPing'));
        
        // dectivates key if we got a request from the api server
        add_action('onp_api_action_deactivate-key', array($this, 'apiActionDeactivateKey'));
        
        // adding notices to display
        if ( is_admin() ) {
            add_filter('factory_notices_305', array( $this, 'addNotices'), 10, 2); 
            add_action('admin_enqueue_scripts', array( $this, 'printStylesForNotices'));
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
    
    /**
     * Processes data returned by an api server.
     * 
     * @since 1.0.0
     * @return void
     */
    public function apiPing( $data ) {
        if ( isset( $data['DeleteLicense'] ) && !empty( $data['DeleteLicense'] ) ) $this->resetLicense();

        if ( isset( $data['KeyNotBound'] ) && !empty( $data['KeyNotBound'] ) ) {
            update_option('onp_bound_message_' . $this->plugin->pluginName, true );
        } else {
            update_option('onp_bound_message_' . $this->plugin->pluginName, false );
        }
    }
    
    /**
     * Dectivates key if we got a request from the api server.
     * 
     * @since 1.0.0
     * @return void
     */
    public function apiActionDeactivateKey() {
        $key = isset( $_GET['onp_key'] ) ? $_GET['onp_key'] : null;
        if ( $key !== $this->key ) return;
        
        $this->resetLicense();
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
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
            
        $data = $this->plugin->api->request( 
            'ActivateKey', 
            array(
                'body' => array(
                    'key' => $key
                )
            ), 
            array(
                'verification' => true
            )
        );
        
        if (is_wp_error($data) ) return $data;
        
        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('FORM:InvalidLicenseData', 'The server returned invalid license data. If you tried to submit or delete key manually please make sure that you copied and pasted the server response code entirely.');

        update_option('onp_license_' . $this->plugin->pluginName, $data);
        $this->data = $data;
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Activates a licensing key manually.
     */
    public function activateKeyManualy( $response ) {
        $response = base64_decode( $response );
        
        $data = array();
        parse_str($response, $data);

        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('FORM:InvalidLicenseData', 'The server returned invalid license data. If you tried to submit or delete key manually please make sure that you copied and pasted the server response code entirely.');
        
        $data['Description'] = base64_decode( str_replace( ' ', '+', $data['Description'] ));
        update_option('onp_license_' . $this->plugin->pluginName, $data);     

        $this->data = $data;
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Make attampt to activate one of trial license via the Licensing server.
     * @param string $server Licensing server to get license data.
     */
    public function activateTrial() {
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
        
        $data = $this->plugin->api->request( 
            'ActivateTrial', array(), 
            array(
                'verification' => true
            )
        );
        
        if (is_wp_error($data) ) return $data;
        
        if ( !$this->checkLicenseDataCorrectness($data) )
            return new WP_Error('FORM:InvalidLicenseData', 'The server returned invalid license data. If you tried to submit or delete key manually please make sure that you copied and pasted the server response code entirely.');
        
        update_option('onp_license_' . $this->plugin->pluginName, $data);
        update_option('onp_trial_activated_' . $this->plugin->pluginName, true);
        
        $this->data = $data;
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Delete current active key for the site.
     */
    public function deleteKey() {
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
        
        $data = $this->plugin->api->request( 
            'DeleteKey', array(), 
            array(
                'verification' => true
            )
        );
         
        if (is_wp_error($data) ) return $data;
        $this->resetLicense();
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    public function resetLicense() {
        delete_option('onp_license_' . $this->plugin->pluginName);
        $this->data = get_option('onp_default_license_' . $this->plugin->pluginName, array());
    }
    
    public function deleteKeyManualy( $response ) {
        $response = base64_decode( $response );

        $data = array();
        parse_str($response, $data);
        
        if ( $data['SiteSecret'] == get_option('onp_site_secret', null) ) {
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
            'site'      => site_url(),
            'secret'    => get_option('onp_site_secret', null),
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
            'tracker'   => $this->plugin->tracker
        );
        
        $secretToken = $this->plugin->api->openVerificationGate();
        $query['secretToken'] = $secretToken;
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array( 'request' => $request ), $this->plugin->options['api'] . 'ActivateTrialManualy' );
    }
    
    public function getLinkToActivateKey( $key ) {
        
        $query = array(
            'key'       => $key,
            'plugin'    => $this->plugin->pluginName,
            'site'      => site_url(),
            'secret'    => get_option('onp_site_secret', null),
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
            'tracker'   => $this->plugin->tracker
        );
        
        $secretToken = $this->plugin->api->openVerificationGate();
        $query['secretToken'] = $secretToken;

        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->plugin->options['api'] . 'ActivateKeyManualy');
    } 
    
    public function getLinkToDeleteKey() {
        
        $query = array(
            'plugin'    => $this->plugin->pluginName,
            'site'      => site_url(),
            'secret'    => get_option('onp_site_secret', null),
            'assembly'  => $this->plugin->build,
            'version'   => $this->plugin->version,
            'tracker'   => $this->plugin->tracker
        );
        
        $request = base64_encode( http_build_query($query) );
        return add_query_arg( array('request' => $request), $this->plugin->options['api'] . 'DeleteKeyManualy');
    }     
    
    /**
     * Creates a customer account and links a licence key.
     * 
     * @since 1.0.0
     * @param type $email Email to create an account.
     * @return true|WP_Error An error occurred during creating an account or true.
     */
    public function createAccount( $email, $subscribe ) {
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
        
        $data = $this->plugin->api->request( 
            'CreateAccount', 
            array(
                'body' => array(
                    'email' => $email,
                    'subscribe' => $subscribe ? 'true' : 'false'
                )  
            ), 
            array(
                'verification' => true
            )
        );
        
        update_option('onp_bound_message_' . $this->plugin->pluginName, false );
        return $data;
    }
    
    /**
     * Binds the current key to the specified email.
     * 
     * @since 1.0.0
     * @param type $email Email to link the current key.
     * @return true|WP_Error An error occurred during creating an account or true.
     */
    public function bindKey( $email ) {
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
        
        $data = $this->plugin->api->request( 
            'bindKey', 
            array(
                'body' => array(
                    'email' => $email
                )  
            ), 
            array(
                'verification' => true
            )
        );
        
        update_option('onp_bound_message_' . $this->plugin->pluginName, false );
        return $data;  
    }
    
    /**
     * Cancels the recently created account (which is not activated yet).
     * 
     * @since 1.0.0
     */
    public function cancelAccount( $cancelCode, $confirmationId ) {
        
        if ( defined('ONP_SL_NETWORK_DISABLED') && ONP_SL_NETWORK_DISABLED )
            return new WP_Error('HTTP:NetworkDisabled', 'The network is disabled.');
        
        $data = $this->plugin->api->request( 
            'cancelAccount', 
            array(
                'body' => array(
                    'cancelCode' => $cancelCode,
                    'confirmationId' => $confirmationId        
                )  
            ), 
            array(
                'verification' => false
            )
        );

        return $data;   
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
        $url = onp_licensing_305_get_manager_link( $this->plugin->pluginName );
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
        
        // show messages only for administrators
        if ( !factory_305_is_administrator() ) return $notices;
        
        $closed = get_option('factory_notices_closed', array());
        
        $time = 0;
        if ( isset( $closed[$this->plugin->pluginName . '-key-not-bound'] ) ) {
            $time = $closed[$this->plugin->pluginName . '-key-not-bound']['time'];
        }

        // shows the key binding message only if changing the assembly is not required
    
        if ( $time + 60*60*24 <= time() && ( !$this->plugin->updates || !$this->plugin->updates->needChangeAssembly() ) ) {        
        
            $keyBound = get_option('onp_bound_message_' . $this->plugin->pluginName, false);
            if ( $keyBound && $this->plugin->license && $this->plugin->license->key ) {

                $notices[] = array(
                    'id'        => $this->plugin->pluginName . '-key-not-bound',

                    // content and color
                    'class'     => 'alert alert-danger onp-alert-key-not-bound',
                    'header'    => 'Your license key is not protected!',
                    'message'   => 'Bind your license key (for ' . $this->plugin->options['title'] . ') to your email address in order to avoid theft (it will take just a couple of seconds).',   
                    'plugin'    => $this->plugin->pluginName,
                    'close'     => true,

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'class'     => 'btn btn-danger',
                            'title'     => '<i class="fa fa-key"></i> Protect my key: <i>' . $this->plugin->license->key . '</i>',
                            'action'    => '?' . http_build_query(array(
                                'fy_page'      => 'license-manager',
                                'fy_action'    => 'createAccount',  
                                'fy_plugin'    => $this->plugin->pluginName
                            ))
                        )
                    )
                );
            }
        }

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
                    
                        if ( $remained <= 1  ) {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'class'     => 'alert alert-danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire during the day!',
                                'message'   => 'Don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   
                                'plugin'    => $this->plugin->pluginName,
                                'close'     => true,
                                
                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'class'     => 'btn btn-danger btn-large btn-lg',
                                        'title'     => '<i class="fa fa-heart"></i> Buy a premium key now!',
                                        'action'    => $this->plugin->options['premium']
                                    )
                                )
                            );

                        } else {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'class'     => 'alert alert-danger',
                                'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" will expire in ' . $remained . ' days.',
                                'message'   => 'Please don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.',   
                                'plugin'    => $this->plugin->pluginName,
                                'close'     => true,
                                
                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'class'     => 'btn btn-danger btn-large btn-lg',
                                        'title'     => '<i class="fa fa-heart"></i> Buy a premium key now!',
                                        'action'    => $this->plugin->options['premium']
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
                    'class'     => 'alert alert-danger',
                    'header'    => 'The trial key for "' . $this->plugin->pluginTitle . '" has expired.',
                    'message'   => 'Please purchase another key or delete the current key to use the free version of the plugin.',   
                    'plugin'    => $this->plugin->pluginName,
                    
                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => '<i class="fa fa-heart"></i> Buy a premium key now!',
                            'class'     => 'btn btn-danger btn-large btn-lg',
                            'action'    => $this->plugin->options['premium']
                        )
                    )
                );
            }
        }
        
        return $notices;
    }
    
    public function printStylesForNotices( $hook ) {
        if ( $hook !== 'index.php' && $hook !== 'plugins.php' ) return;
        ?>
        <style>

        </style>
        <?php
    }
}

/**
 * Renders link to the license manager.
 * 
 * @since 1.0.0
 * @param type $pluginName
 * @param type $action
 */
function onp_licensing_305_manager_link( $pluginName, $action = null ) {
    
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
function onp_licensing_305_get_manager_link( $pluginName, $action = null ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    return '?' . http_build_query( $args );
}