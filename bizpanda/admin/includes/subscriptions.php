<?php

class OPanda_SubscriptionServices {
    
    /**
     * Returns a list of available subscription services.
     * 
     * @since 1.0.8
     * @return mixed[]
     */
    public static function getSerivcesList() {
        $result = apply_filters('opanda_subscription_services', array() );
        
        $helper = array();
        foreach( $result as $name => $data ) {
            $helper[$name] = $data['title'];
        }
        
        array_multisort( $result, $helper );
        return $result;
    }
    
    /**
     * Returns a name of the current subscription service.
     * 
     * @since 1.0.8
     * @return OPanda_Subscription
     */
    public static function getCurrentName() {
        return get_option('opanda_subscription_service', 'none');
    }
    
    /**
     * Returns a title of the current subscription service.
     * 
     * @since 1.0.8
     * @return OPanda_Subscription
     */
    public static function getCurrentServiceTitle() {
        $info = self::getCurrentServiceInfo();
        return !empty( $info ) ? $info['title'] : null;
    }
    
    /**
     * Returns information about the current subscription service.
     * 
     * @since 1.0.8
     * @return string[]
     */
    public static function getCurrentServiceInfo() {
        return self::getServiceInfo( null );
    }
    
    /**
     * Returns an object of the current subscription service.
     * 
     * @since 1.0.8
     * @return OPanda_Subscription
     */
    public static function getCurrentService() {
        return self::getService( null );
    }
    
    /**
     * Returns information about a specified service.
     * 
     * @since 1.0.8
     * @param string $name A name of the service to return.
     * @return string[]
     */
    public static function getServiceInfo( $name = null ) {

        $services = self::getSerivcesList();
        $name = empty( $name ) ? get_option('opanda_subscription_service', 'none') : $name;
        if ( !isset( $services[$name] ) ) $name = 'none';
        
        if ( isset( $services[$name] ) ) {
            $services[$name]['name'] = $name;
            return $services[$name];
        }
        
        return null;
    }
    
    /**
     * Returns an object of a specified subscription service.
     * 
     * @since 1.0.8
     * @param string $name A name of the service to return.
     * @return OPanda_Subscription
     */
    public static function getService( $name = null ) {
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/classes/class.subscription.php';

        $info = self::getServiceInfo( $name );
        if ( empty( $info) ) return null;

        require_once $info['path'];
        return new $info['class']( $info );
    }
    
    /**
     * Returns available opt-in modes for the current subscription service.
     * 
     * @since 1.0.8
     * @param string $name A name of the service to return.
     * @return mixed[]
     */
    public static function getCurrentOptinModes( $toList = false ) {

        $result = array();
        $finish = array();
        
        $info = self::getCurrentServiceInfo();
        if ( empty( $info ) ) return array();

        if ( OPANDA_DEPENDS_ON_LIST === $info['modes'] ) {

            if ( !$toList ) {
                return array( OPANDA_DEPENDS_ON_LIST );
            } else {
                $finish[] = array( OPANDA_DEPENDS_ON_LIST, __('[ Depends on the list ]', 'bizpanda'), __( 'The Opt-In Mode depends on the settings of the list you selected. Check the <a href="http://freshmail.com/help-and-knowledge/help/managing-clients/set-parameters-list-recipients/" target="_blank">parameter</a> "List type" of the selected list in your FreshMail account to know which Opt-In Mode will be applied.', 'bizpanda') );
                return $finish;                
            }
        }

        $all = self::getAllOptinModes();

        foreach( $info['modes'] as $name ) {
            $result[$name] = $all[$name];
        }
        
        if ( !$toList ) return $result;

        foreach( $result as $name => $mode ) {
            $finish[] = array(
                'value' => $name, 
                'title' => $mode['title'], 
                'hint' => $mode['description']
            );
        }

        return $finish;
    }
    
    /**
     * Returns all the available opt-in modes.
     * 
     * @since 1.0.8
     * @return mixed[]
     */
    public static function getAllOptinModes() {
        
        $modes = array(
            'double-optin' => array(
                'title' => __('Full Double Opt-In (recommended)', 'bizpanda'),
                'description' => __('After the user enters one\'s email address, sends the confirmation email (double opt-in) and waits until the user confirms the subscription. Then, unlocks the content.', 'bizpanda')
            ),
            'quick-double-optin' => array(
                'title' => __('Lazy Double Opt-In', 'bizpanda'),
                'description' => __('Unlocks the content immediately after the user enters one\'s email address but also sends the confirmation email (double opt-in) to confirm the subscription.', 'bizpanda')
            ),
            'quick' => array(
                'title' => __('Single Opt-In', 'bizpanda'),
                'description' => __('Unlocks the content immediately after the user enters one\'s email address. Doesn\'t send the confirmation email.', 'bizpanda')
            ),
        );
        
        return apply_filters('opanda_optin_modes', $modes);
    }
}