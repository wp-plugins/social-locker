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
add_action('onp_licensing_325_plugin_created', 'onp_licensing_325_plugin_created');
function onp_licensing_325_plugin_created( $plugin ) {
    $manager = new OnpLicensing325_Manager( $plugin );
    $plugin->license = $manager;
}

/**
 * The License Manager class.
 * 
 * @since 1.0.0
 */
class OnpLicensing325_Manager {
    
    /**
     * A plugin for which the manager was created.
     * 
     * @since 1.0.0
     * @var Factory325_Plugin
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
    public function __construct( $plugin ) {
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
        $this->word = $this->build ? 'top' : 'bottom';
        
        // checks data returned from the api server
        add_action('onp_api_ping_' . $plugin->pluginName, array($this, 'apiPing'));
        
        // dectivates key if we got a request from the api server
        add_action('onp_api_action_deactivate-key', array($this, 'apiActionDeactivateKey'));
        
        if ( is_admin() ) {
        
            add_action('admin_enqueue_scripts', array($this, 'addLicenseInfoIntoSouceCode'));

            // adding links below the plugin title on the page plugins.php
            add_filter('plugin_action_links_' . $plugin->relativePath, array( $this, 'addLicenseLinks' ) );

            // adding messages to the plugin row on the page plugins.php
            add_filter('factory_plugin_row_' . $plugin->pluginName, array($this, 'addMessagesToPluginRow'));
        
            // adding notices to display
            add_filter('factory_notices_' . $this->plugin->pluginName, array( $this, 'addNotices'), 10, 2); 
            add_action('admin_enqueue_scripts', array( $this, 'printStylesForNotices'));
            
            // activation and deactivation hooks
            add_action('factory_plugin_activation_' . $plugin->pluginName, array($this, 'activationHook'));
        }
    }
    
    /**
     * Checks if the activation.json file exists, read and processes it.
     * 
     * @since 3.0.6
     * @return void
     */
    public function activationHook() {
        $licenseData = get_option('onp_license_' . $this->plugin->pluginName, array());
        if ( !empty($licenseData) ) return;
        
        $filepath = $this->plugin->pluginRoot . '/activation.json';
        if ( !file_exists( $filepath ) ) return;
        
        $data = json_decode( file_get_contents($filepath), true );

        // activate trial if it's needed
        
        if ( isset( $data['activate-trial'] ) && $data['activate-trial'] === true ) {
            
            $args = array(
                'fy_page'      => 'license-manager',
                'fy_action'    => 'activateTrial',  
                'fy_plugin'    => $this->plugin->pluginName
            );

            $urlToRedirect =  '?' . http_build_query( $args );
            factory_325_set_lazy_redirect($urlToRedirect);
                
            //@unlink( $filepath );  
            return;
        }
        
        // applying an embedded key
        
        if ( isset( $data['embedded-key'] ) && !empty( $data['embedded-key'] ) ) {
            $dataToSave = $data['embedded-key'];
            $dataToSave['Embedded'] = true;
            $this->setLicense( $dataToSave );
        }   
    }
    
    /**
     * Sets the active license.
     * 
     * @since 3.0.6
     * @param type $data licenase data.
     * @return void
     */
    public function setLicense( $data ) {
        update_option('onp_license_' . $this->plugin->pluginName, $this->normilizeLicenseData( $data )); 
        
        $this->data = get_option('onp_license_' . $this->plugin->pluginName, array());
        $this->build = isset( $this->data['Build'] ) ? $this->data['Build'] : null;
        $this->key = isset( $this->data['Key'] ) ? $this->data['Key'] : null;
    }
    
    /**
     * Sets the default license.
     * 
     * @since 3.0.6
     * @param type $data license data.
     * @return void
     */
    public function setDefaultLicense( $data ) {        
        $defaultLicense = get_option('onp_default_license_' . $this->plugin->pluginName, null);

        if ( empty($defaultLicense) ) {
            update_option('onp_default_license_' . $this->plugin->pluginName, $this->normilizeLicenseData( $data ));    
        }
    }
    
    /**
     * Deletes the active license data and applies the default license data.
     * 
     * @return void
     */
    public function resetLicense( $resetDefault = false ) {
        delete_option('onp_license_' . $this->plugin->pluginName);
        if ( $resetDefault ) { 
            delete_option('onp_default_license_' . $this->plugin->pluginName);
            $this->plugin->activationHook();
        }
        $this->data = get_option('onp_default_license_' . $this->plugin->pluginName, array());
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
     * Removes impurities in license data.
     * 
     * @since 3.0.6
     * @param mixed $data
     * @return mixed
     */
    private function normilizeLicenseData( $data ) {
        $keys = array('Key', 'KeySecret', 'Category', 'Build', 'Title', 'Description', 'Activated', 'Expired', 'Embedded', 'KeyBound');
        $dataToReturn = array();
        
        foreach($data as $itemKey => $itemValue) {
            if ( !in_array( $itemKey, $keys )) continue;
            $dataToReturn[$itemKey] = $itemValue;
        } 
        
        if ( !isset( $dataToReturn['Expired'] )) $dataToReturn['Expired'] = 0;
        return $dataToReturn;
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
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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

        delete_option('mix_word_' . $this->plugin->pluginName);
        
        $this->setLicense( $data );
        
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
        $this->setLicense( $data );
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Make attampt to activate one of trial license via the Licensing server.
     * @param string $server Licensing server to get license data.
     */
    public function activateTrial() {
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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
        
        update_option('onp_trial_activated_' . $this->plugin->pluginName, true);
        $this->setLicense( $data );
        
        if ( $this->plugin->updates ) $this->plugin->updates->checkUpdates();
        return true;
    }
    
    /**
     * Delete current active key for the site.
     */
    public function deleteKey() {
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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
        
        if ( defined('ONP_DEBUG_NETWORK_DISABLED') && ONP_DEBUG_NETWORK_DISABLED )
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
    
    public function isTrial() {
        if ( !isset( $this->data['Category'] ) ) return false;
        return $this->data['Category']  === 'trial';
    }
    
    public function isEmbedded() {
        if ( !isset( $this->data['Embedded'] ) ) return false;   
        return $this->data['Embedded'];
    }
    
    public function needToProtect() {
        return !$this->isTrial() && !$this->isEmbedded();
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
        $url = onp_licensing_325_get_manager_link( $this->plugin->pluginName );
        array_unshift($links, '<a href="' . $url . '" style="font-weight: bold;">'.__('License', 'onp_licensing_325'),'</a>');
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
                    
                    $message = __('Need more features? Look at a <a target="_blank" href="%1$s">premium version</a> of the plugin.', 'onp_licensing_325');
                    $message = str_replace("%1\$s", onp_licensing_325_get_purchase_url( $this->plugin ), $message);
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
        if ( !factory_325_is_administrator() ) return $notices;
        
        $closed = get_option('factory_notices_closed', array());
        
        $time = 0;
        if ( isset( $closed[$this->plugin->pluginName . '-key-not-bound'] ) ) {
            $time = $closed[$this->plugin->pluginName . '-key-not-bound']['time'];
        }

        // shows the key binding message only if changing the assembly is not required
        
        $forceBindingMessage = defined('ONP_DEBUG_SHOW_BINDING_MESSAGE') && ONP_DEBUG_SHOW_BINDING_MESSAGE;
    
        if ( ( $time + 60*60*24 <= time() && ( !$this->plugin->updates || !$this->plugin->updates->needChangeAssembly() )) || $forceBindingMessage ) {        
        
            $keyBound = get_option('onp_bound_message_' . $this->plugin->pluginName, false);
            if ( ( $keyBound && $this->plugin->license && $this->plugin->license->key && $this->needToProtect() ) || $forceBindingMessage ) {

                $notices[] = array(
                    'id'        => $this->plugin->pluginName . '-key-not-bound',

                    // content and color
                    'class'     => 'call-to-action',
                    'icon'      => 'fa fa-frown-o',  
                    'header'    => __('Your license key is not protected!', 'onp_licensing_325'),
                    'message'   => sprintf(__('Bind your license key (for %s) to your email address in order to avoid theft (it will take just a couple of seconds).', 'onp_licensing_325'), $this->plugin->options['title']),   
                    'plugin'    => $this->plugin->pluginName,

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'class'     => 'btn btn-primary',
                            'title'     => '<i class="fa fa-key"></i> ' . sprintf( __('Protect my key: %s', 'onp_licensing_325' ), '<i>' . $this->plugin->license->key . '</i>' ),
                            'action'    => '?' . http_build_query(array(
                                'fy_page'      => 'license-manager',
                                'fy_action'    => 'createAccount',  
                                'fy_plugin'    => $this->plugin->pluginName
                            ))
                        ),
                        array(
                            'title'     => __('Hide this message', 'onp_licensing_325'),
                            'class'     => 'btn btn-default',
                            'action'    => 'x'
                        )
                    )
                );
            }
        }
        
        $forceTrialNotices = defined('ONP_DEBUG_TRIAL_EXPIRES') && ONP_DEBUG_TRIAL_EXPIRES !== false;

        $exipred = floatval($this->data['Expired']); 
        if ( $exipred != 0 || $forceTrialNotices ) {

            $remained = round( ( $this->data['Expired'] - time() ) / (60 * 60 * 24), 2 );
            if ( $forceTrialNotices ) $remained = ONP_DEBUG_TRIAL_EXPIRES;
                
            if ( $remained < 5 && $remained > 0 ) {
                
                $time = 0;
                if ( isset( $closed[$this->plugin->pluginName . '-key-estimate'] ) ) {
                    $time = $closed[$this->plugin->pluginName . '-key-estimate']['time'];
                }
                
                if ( $time + 60*60*24 <= time() || $forceTrialNotices ) {
                    
                    if ( $this->type == 'trial' || $forceTrialNotices ) {
                    
                        if ( $remained <= 1  ) {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'class'     => 'call-to-action',
                                'icon'      => 'fa fa-clock-o',   
                                'header'    => sprintf(__('The trial key for the %s will expire during the day!', 'onp_licensing_325'), $this->plugin->pluginTitle),
                                'message'   => __('Don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.', 'onp_licensing_325'),   
                                'plugin'    => $this->plugin->pluginName,
                                
                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => '<i class="fa fa-arrow-circle-o-up"></i> '.__('Buy a premium key now!', 'onp_licensing_325'),
                                        'class'     => 'btn btn-primary',
                                        'action'    => onp_licensing_325_get_purchase_url( $this->plugin, 'trial-remained-1' )
                                    ),
                                    array(
                                        'title'     => __('Hide this message', 'onp_licensing_325'),
                                        'class'     => 'btn btn-default',
                                        'action'    => 'x'
                                    ),
                                )
                            );

                        } else {

                            $notices[] = array(
                                'id'        => $this->plugin->pluginName . '-key-estimate',

                                // content and color
                                'class'     => 'call-to-action',
                                'icon'      => 'fa fa-clock-o',
                                'header'    => sprintf(__('The trial key for the %s will expire in %s days.', 'onp_licensing_325'),$this->plugin->pluginTitle, $remained),
                                'message'   => __('Please don\'t forget to purchase the premium key or delete the trial key to use the free version of the plugin.', 'onp_licensing_325'),   
                                'plugin'    => $this->plugin->pluginName,
                                
                                // buttons and links
                                'buttons'   => array(
                                    array(
                                        'title'     => '<i class="fa fa-arrow-circle-o-up"></i> '.__('Buy a premium key now!', 'onp_licensing_325'),
                                        'class'     => 'btn btn-primary',
                                        'action'    => onp_licensing_325_get_purchase_url( $this->plugin, 'trial-remained-' . $remained )
                                    ),
                                    array(
                                        'title'     => __('Hide this message', 'onp_licensing_325'),
                                        'class'     => 'btn btn-default',
                                        'action'    => 'x'
                                    ),
                                )
                            );
                        }  

                    }
                }
            }
            
            if ( $this->isExpired() || $forceTrialNotices ) {
                
                $notices[] = array(
                    'id'        => $this->plugin->pluginName . '-key-expired',

                    // content and color
                    'class'     => 'call-to-action',
                    'icon'      => 'fa fa-arrow-circle-o-up',
                    'header'    => sprintf(__('The trial key for the %s has expired.', 'onp_licensing_325'),$this->plugin->pluginTitle),
                    'message'   => __('Please purchase another key or delete the current key to use the free version of the plugin.', 'onp_licensing_325'),   
                    'plugin'    => $this->plugin->pluginName,
                    
                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => '<i class="fa fa-arrow-circle-o-up"></i> '.__('Buy a premium key now!', 'onp_licensing_325'),
                            'class'     => 'btn btn-primary',
                            'action'    => onp_licensing_325_get_purchase_url( $this->plugin, 'trial-expired' )
                        ),
                        array(
                            'title'     => __('Visit the license manager', 'onp_licensing_325'),
                            'class'     => 'btn btn-default',
                            'action'    => onp_licensing_325_get_manager_link($this->plugin->pluginName, 'index')
                        ),
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
            .alert-danger.onp-alert-trial {
                background-color: #fafafa !important;
                color: #111 !important;
                border: 2px solid #0074a2 !important;
            }
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
function onp_licensing_325_manager_link( $pluginName, $action = null, $echo = true ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    if( $echo )
        echo   '?' . http_build_query( $args );
    else 
        return '?' . http_build_query( $args );
}

/**
 * Gets link to the license manager.
 * 
 * @since 1.0.0
 * @param type $pluginName
 * @param type $action
 */
function onp_licensing_325_get_manager_link( $pluginName, $action = null ) {
    
    $args = array(
        'fy_page'      => 'license-manager',
        'fy_action'    => $action,  
        'fy_plugin'    => $pluginName
    );
    
    return '?' . http_build_query( $args );
}

/**
 * Prints a purchasing link with a set of tracking query arguments.
 * 
 * @since 3.0.7
 * @param Factory325_Plugin $plugin
 * @return void
 */
function onp_licensing_325_purchase_url( $plugin ) {
    echo onp_licensing_325_get_purchase_url( $plugin );
}

/**
 * Returns a purchasing link with a set of tracking query arguments.
 * 
 * @since 3.0.7
 * @param Factory325_Plugin $plugin
 * @return string
 */
function onp_licensing_325_get_purchase_url( $plugin, $campaign = 'upgrade-to-premium', $content = null ) {
    if ( empty( $plugin ) || empty( $plugin->options ) ) return null; 
    if ( !isset( $plugin->options['premium'] ) ) return null;
    
    $url = $plugin->options['premium'];
    $args = array(
        'utm_source'            => 'plugin-' . $plugin->options['name'],
        'utm_medium'            => ( $plugin->license && isset( $plugin->license->data['Category'] ) ) 
                                    ? ( $plugin->license->data['Category'] . '-version' )
                                    : 'unknown-version',
        'utm_campaign'          => $campaign,
        'tracker'               => isset( $plugin->options['tracker'] ) ? $plugin->options['tracker'] : null
    );
    
    if ( $content ) $args['utm_content'] = $content;
    return add_query_arg( $args, $url );
}