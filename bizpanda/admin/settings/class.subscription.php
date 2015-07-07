<?php
/**
 * A class for the page providing the subscription settings.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The Subscription Settings
 * 
 * @since 1.0.0
 */
class OPanda_SubscriptionSettings extends OPanda_Settings  {
 
    public $id = 'subscription';
    
    public function init() {

        if ( isset( $_GET['opanda_aweber_disconnected'] )) {
            $this->success = __('Your Aweber Account has been successfully disconnected.', 'bizpanda');
        }
    }
    
    /**
     * Shows the header html of the settings screen.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {
        ?>
        <p><?php _e('Set up here how you would like to save emails of your subscribers.', 'optionpanda') ?></p>
        <?php
    }
    
    /**
     * Returns subscription options.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getOptions() {
        
        $options = array();
        
        $options[] = array(
            'type' => 'separator'
        );
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/subscriptions.php';
        $serviceList = OPanda_SubscriptionServices::getSerivcesList();
        
        // fix
        $service =  get_option('opanda_subscription_service', 'database');
        if ( $service == 'none' ) update_option('opanda_subscription_service', 'database');
        
        $listItems = array();
        
        foreach( $serviceList as $serviceName => $serviceInfo ) {
            
            $listItems[] = array(
                'value' => $serviceName,
                'title' => $serviceInfo['title'],
                'hint' => isset( $serviceInfo['description'] ) ? $serviceInfo['description'] : null,
                'image' => isset( $serviceInfo['image'] ) ? $serviceInfo['image'] : null,
                'hover' => isset( $serviceInfo['hover'] ) ? $serviceInfo['hover'] : null             
            );
        }
        
        $options[] = array(
            'type' => 'dropdown',
            'name' => 'subscription_service',
            'way' => 'ddslick',
            'width' => 450,
            'data' => $listItems,
            'default' => 'none',
            'title' => __('Mailing Service', 'bizpanda')
        );
        
        $options = apply_filters( 'opanda_subscription_services_options', $options, $this );
        
        $options[] = array(
            'type' => 'separator'
        );
        
        return $options;
    }

    /**
     * Calls before saving the settings.
     * 
     * @since 1.0.0
     * @return void
    */
    public function onSaving() {
        do_action('opanda_on_saving_subscription_settings', $this );
   }

    public function disconnectAweberAction() {

        delete_option('opanda_aweber_consumer_key');
        delete_option('opanda_aweber_consumer_secret');
        delete_option('opanda_aweber_access_key');
        delete_option('opanda_aweber_access_secret');
        delete_option('opanda_aweber_auth_code'); 
        delete_option('opanda_aweber_account_id'); 
        
        return $this->redirectToAction('index', array('opanda_aweber_disconnected' => true));
    }
}
