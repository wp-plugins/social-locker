<?php
/**
 * The file contains a class and a set of helper methods to manage updates.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package onepress-updates 
 * @since 1.0.0
 */

add_action('onp_updates_324_plugin_created', 'onp_updates_324_plugin_created');
function onp_updates_324_plugin_created( $plugin ) {
    $manager = new OnpUpdates324_Manager( $plugin );
    $plugin->updates = $manager;
}

/**
 * The Updates Manager class.
 * 
 * @since 1.0.0
 */
class OnpUpdates324_Manager {
    
    /**
     * Current factory plugin.
     * 
     * @since 1.0.0
     * @var Factory325_Plugin 
     */
    public $plugin;
    
    /**
     * Data about the last version check.
     * 
     * @since 1.0.0
     * @var array 
     */
    public $lastCheck;

    /**
     * Creates an instance of the update manager.
     * @param type $plugin
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin; 
        $this->lastCheck = get_option('onp_version_check_' . $this->plugin->pluginName, null);
        $this->word = $this->lastCheck ? $this->lastCheck : 'never';
        
        // if a plugin is not licensed, or a user has a license key
        if ( $this->needCheckUpdates() ) {
            
            // an action that is called by the cron to check updates
            add_action('onp_check_upadates_' . $this->plugin->pluginName, array($this, 'checkUpdatesAuto')); 
            
            // if a special constant set, then forced to check updates
            if ( defined('ONP_DEBUG_CHECK_UPDATES') && ONP_DEBUG_CHECK_UPDATES ) $this->checkUpdates();
        }

        if ( is_admin() ) {
        
            // if the license build and the plugin build are not equal
            if ( $this->needChangeAssembly() ) {

                $this->updatePluginTransient();
                add_filter('factory_plugin_row_' . $this->plugin->pluginName, array($this, 'showChangeAssemblyPluginRow' ), 10, 3); 
                add_filter('factory_notices_' . $this->plugin->pluginName, array( $this, 'addNotices'), 10, 2);    
            }
            
            add_action('admin_notices', array($this, 'clearTransient'));

            if ( $this->needCheckUpdates() || $this->needChangeAssembly() ) {

                // the method that responses for changin plugin transient when it's saved
                add_filter('pre_set_site_transient_update_plugins', array($this, 'changePluginTransient')); 
                // filter that returns info about available updates
                add_filter('plugins_api', array($this, 'getUpdateInfo'), 10, 3);
            }

            // activation and deactivation hooks
            add_action('factory_plugin_activation_or_update_' . $plugin->pluginName, array($this, 'activationOrUpdateHook'));
            add_action('factory_plugin_deactivation_' . $plugin->pluginName, array($this, 'deactivationHook')); 
        }

    }
    
    /**
     * Need to check updates?
     * @return bool
     */
    public function needCheckUpdates() {
        return $this->plugin->build == 'premium';
    }
    
    /**
     * Need to change a current assembly?
     * @return bool
     */
    public function needChangeAssembly() {
        if ( $this->plugin->build === 'offline' ) return false;
        return isset( $this->plugin->license ) && ( $this->plugin->build !== $this->plugin->license->build );
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
        if ( !wp_next_scheduled( 'onp_check_upadates_' . $this->plugin->pluginName ) ) { 
            wp_schedule_event( time(), 'twicedaily', 'onp_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->clearUpdates();
    }
    
    /**
     * Calls on plugin deactivation .
     */
    public function deactivationHook() {

        // clear cron tasks and license data
        if ( wp_next_scheduled( 'onp_check_upadates_' . $this->plugin->pluginName ) ) { 
            $timestamp = wp_next_scheduled( 'onp_check_upadates_' . $this->plugin->pluginName ); 
            wp_unschedule_event( $timestamp, 'onp_check_upadates_' . $this->plugin->pluginName );    
        }
        
        $this->clearUpdates();
    }  
    
    // -------------------------------------------------------------------------------------
    // Checking updates
    // -------------------------------------------------------------------------------------
    
    public function checkUpdatesAuto() {
        $lastTime = intval( get_option( 'onp_last_check_' . $this->plugin->pluginName ) );
        if ( !$lastTime ) $lastTime = 0;
    
        if ( time() > $lastTime + 10800 )  {
            $this->checkUpdates();
            update_option( 'onp_last_check_' . $this->plugin->pluginName, time() );
        }
    }
    
    public function checkUpdates() {
        $data = $this->sendRequest( 'GetCurrentVersion' );

        if ( is_wp_error( $data ) )  {
            $result = array();
            $result['Checked'] = time();
            $result['Error'] = $data->get_error_message();          
            update_option('onp_version_check_' . $this->plugin->pluginName, $result);
            $this->lastCheck = $result;  
        } else {
            $data['Checked'] = time();
            update_option('onp_version_check_' . $this->plugin->pluginName, $data);
            $this->lastCheck = $data; 
            
            do_action('onp_api_ping_' . $this->plugin->pluginName, $data);
        }

        $this->updatePluginTransient();
        return $data;
    }
    
    /**
     * Clears info about updates for the plugin.
     */
    public function clearUpdates() {
        delete_option('onp_version_check_' . $this->plugin->pluginName);
        $this->lastCheck = null;
         
        $transient = $this->changePluginTransient( get_site_transient('update_plugins') );
        if ( !empty( $transient) ) {
            unset($transient->response[$this->plugin->relativePath]);
            onp_updates_324_set_site_transient('update_plugins', $transient);  
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
        onp_updates_324_set_site_transient('update_plugins', $transient);
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
            $obj->new_version = '[ migrate-to-' . $this->plugin->license->build . ' ]';  
            
            $queryArgs = array(
                'plugin'   => $this->plugin->pluginName,
                'assembly' => $this->plugin->license->build,   
                'site'     => site_url(),
                'secret'   => get_option('onp_site_secret', null),
                'tracker'  => $this->plugin->tracker
            );
            
            if ( defined('FACTORY_BETA') && FACTORY_BETA ) $queryArgs['beta'] = true;
        
            $obj->package = $this->plugin->options['api'] . 'GetPackage?' . http_build_query($queryArgs);
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
            $obj->new_version = $this->lastCheck['Version'];   

            $obj->url = $this->plugin->options['api'] . 'GetDetails?' . http_build_query(array(
                'version' => $this->lastCheck['Id']
            ));  
            
            $queryArgs = array(
                'versionId' => $this->lastCheck['Id'],  
                'site'      => site_url(),
                'secret'    => get_option('onp_site_secret', null),
                'tracker'   => $this->plugin->tracker
            );
            
            if ( defined('FACTORY_BETA') && FACTORY_BETA ) $queryArgs['beta'] = true;
            $obj->package = $this->plugin->options['api'] . 'GetPackage?' . http_build_query($queryArgs);
 
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

        if (!empty($arg) && isset($arg->slug) && $arg->slug === $this->plugin->pluginSlug) {  

            // if we need to change a current assembly, then nothing to say about the update
            if ( $this->needChangeAssembly() ) {
                ?>
                <strong>Migration to another plugin assenbly.</strong>
                <?php 
                return $false;
            }
            
            $package = $this->plugin->options['api'] . 'GetPackage?' . http_build_query(array(
                'versionId' => $this->lastCheck['Id'],  
                'site' => site_url(),
                'secret' => get_option('onp_site_secret', null)
            ));

            $data = $this->sendRequest( 'GetDetails?' . http_build_query(array(
                'version' => $this->lastCheck['Id']
            )), array( 
                'skipBody' => true,
                'method' => 'GET'
            ));

            if ( is_wp_error( $data ) ) {
                ?>
                <strong><?php echo $data->get_error_message() ?></strong>
                <?php
                return $false;
            }
            
            $obj = new stdClass();
            $obj->slug = $this->plugin->pluginSlug;
            $obj->homepage = $data['Homepage'];
            $obj->name = $this->plugin->pluginTitle; 
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
                __('You changed the license type. Please download "%1$s" assembly', 'onepress_updates_000'), 
                $this->plugin->license->build
            );

        } else if ( empty($r->package) ) {

            $message = sprintf( 
                __('You changed the license type. Please download "%1$s" assembly. <em>Automatic update is unavailable for this plugin.</em>', 'onepress_updates_000'), 
                $this->plugin->license->build
            );

        } else {

            $message = sprintf( 
                __('You successfully changed the license type. Please install another plugin assembly (%1$s). <a href="%2$s">Update it now</a>.', 'onepress_updates_000'), 
                $this->plugin->license->build,
                wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file)     
            );
        }
        
        return array($message);
    }
    
    public function addNotices( $notices ) {
        
        if ( $this->needChangeAssembly() ) {
            
            $notices[] = array(
                'id'        => $this->plugin->pluginName . '-change-assembly',
                'where'     => array('dashboard', 'edit', 'post'),
                
                // content and color
                'class'     => 'alert alert-danger onp-need-change-assembly',
                'header'    => __('Please update the plugin', 'onepress_updates_000'),
                'message'   => sprintf(__('You changed a license type for <strong>%s</strong>. But the license you use\'re currently requires another plugin assembly.<br />The plugin won\'t work fully until you download the proper assembly. Don\'t worry it takes only 5 seconds and all your data will be saved.', 'onepress_updates_000'), $this->plugin->pluginTitle),   

                // buttons and links
                'buttons'   => array(
                    array(
                        'title'     => __('Visit the Plugins page', 'onepress_updates_000'),
                        'class'     => 'btn btn-danger',
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
    protected function sendRequest($action, $args = array()) {
        $args['timeout'] = 8;
        return $this->plugin->api->request( $action, $args );
    }
}

?>
