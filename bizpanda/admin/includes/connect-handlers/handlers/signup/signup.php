<?php
if ( !defined('OPANDA_PROXY') ) exit;

/**
 * The class to proxy the request to the Twitter API.
 */
class OPanda_SignupHandler extends OPanda_Handler {

    /**
     * Handles the proxy request.
     */
    public function handleRequest() {
        
        // - context data
        
        $contextData = isset( $_POST['opandaContextData'] ) ? $_POST['opandaContextData'] : array();
        $contextData = $this->normilizeValues( $contextData );
        
        // - identity data
        
        $identityData = isset( $_POST['opandaIdentityData'] ) ? $_POST['opandaIdentityData'] : array();
        $identityData = $this->normilizeValues( $identityData );
        
        // prepares data received from custom fields to be transferred to the mailing service
        
        $identityData = $this->prepareDataToSave( null, null, $identityData );
        
        do_action('opanda_lead_catched', $identityData, $contextData);
        
        if ( is_user_logged_in() ) {
            return false;
        }

        $email = $identityData['email'];
        if ( empty( $email ) ) return;
        
        if ( !email_exists( $email ) ) {
            
            $username = $this->generateUsername( $email );
            $random_password = wp_generate_password( $length = 12, false );

            $userId = wp_create_user( $username, $random_password, $email );
            wp_new_user_notification( $userId, $random_password );
            
            do_action('opanda_registered', $identityData, $contextData ); 
            
        } else {
            $user = get_user_by( 'email', $email );
            $userId = $user->ID;
        }
    
        /* 
         * Unsafe code, should be re-written
         */
        
        /*
        if ( !is_user_logged_in() ) {

            $mode = $this->options['mode'];

            if ( in_array( $mode, array('hidden', 'obvious')) ) {
                wp_set_auth_cookie( $userId, true );
            }  
        }*/
    }
    
    protected function generateUsername( $email ) {
        
        $parts = explode ('@', $email);
        if ( count( $parts ) < 2 ) return false;
        
        $username = $parts[0];
        if ( !username_exists( $username ) ) return $username;
        
        $index = 0;
        
        while(true) {
           $index++;
           $username = $parts[0] . $index;
           
           if ( !username_exists( $username ) ) return $username;
        }
    }
}


