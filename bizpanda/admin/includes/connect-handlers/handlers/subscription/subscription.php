<?php
if ( !defined('OPANDA_PROXY') ) exit;

/**
 * The class to proxy the request to the Subscription API.
 */
class OPanda_SubscriptionHandler extends OPanda_Handler {
    /**
     * Handles the proxy request.
     */
    public function handleRequest() {
        
        if( !isset($_POST['opandaRequestType']) || !isset($_POST['opandaService']) ) {
           throw new Opanda_HandlerInternalException('Invalid request. The "opandaRequestType" or "opandaService" are not defined.');
        }

        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/subscriptions.php';
        $service = OPanda_SubscriptionServices::getCurrentService();

        if ( empty( $service) ) {
           throw new Opanda_HandlerInternalException( sprintf( 'The subscription service is not set.' )); 
        }
        
        // - service name
        
        $serviceName = $this->options['service'];
        if ( $serviceName !== $service->name  ) {
           throw new Opanda_HandlerInternalException( sprintf( 'Invalid subscription service "%s".', $serviceName ));
        }
        
        // - request type
        
        $requestType = strtolower( $_POST['opandaRequestType'] );
        $allowed = array('check', 'subscribe');

        if ( !in_array( $requestType, $allowed ) ) {
           throw new Opanda_HandlerInternalException( sprintf( 'Invalid request. The action "%s" not found.', $requestType ));
        }
        
        // - identity data
        
        $identityData = isset( $_POST['opandaIdentityData'] ) ? $_POST['opandaIdentityData'] : array();
        $identityData = $this->normilizeValues( $identityData );
        
        if ( empty( $identityData['email'] )) {
           throw new Opanda_HandlerException( 'Unable to subscribe. The email is not specified.' );
        }

        // - context data
        
        $contextData = isset( $_POST['opandaContextData'] ) ? $_POST['opandaContextData'] : array();
        $contextData = $this->normilizeValues( $contextData );

        // - list id
        
        $listId = isset( $_POST['opandaListId'] ) ? $_POST['opandaListId'] : null;
        if ( empty( $listId ) ) {
           throw new Opanda_HandlerException( 'Unable to subscribe. The list ID is not specified.' );
        }
        
        // - double opt-in
        
        $doubleOptin =  isset( $_POST['opandaDoubleOptin'] ) ? $_POST['opandaDoubleOptin'] : true;
        $doubleOptin = $this->normilizeValue( $doubleOptin );
        
        // - confirmation
        
        $confirm =  isset( $_POST['opandaConfirm'] ) ? $_POST['opandaConfirm'] : true;
        $confirm = $this->normilizeValue( $confirm );
        
        // prepares data received from custom fields to be transferred to the mailing service
        
        $itemId = intval( $contextData['itemId'] );
        
        $identityData = $this->prepareDataToSave( $service, $itemId, $identityData );
        $serviceReadyData = $this->mapToServiceIds( $service, $itemId, $identityData );
        
        $identityData = $this->mapToCustomLabels( $service, $itemId, $identityData );

        // creating subscription service
        
        try {    
            
            $result = array();
            
            if ( 'subscribe' === $requestType ) {
                $result = $service->subscribe( $serviceReadyData, $listId, $doubleOptin, $contextData );

                do_action('opanda_subscribe', 
                    ( $result && isset( $result['status'] ) ) ? $result['status'] : 'error', 
                    $identityData, $contextData
                );
                
            } elseif ( 'check' === $requestType ) {
                $result = $service->check( $serviceReadyData, $listId, $contextData );

                do_action('opanda_check', 
                    ( $result && isset( $result['status'] ) ) ? $result['status'] : 'error', 
                    $identityData, $contextData
                );
            }

            if ( !defined( 'OPANDA_WORDPRESS' ) ) return $result;
            
            // calls the hook to save the lead in the database
            if ( $result && isset( $result['status'] ) ) {

                $actionData = array(
                    'identity' => $identityData,
                    'requestType' => $requestType,
                    'service' => $this->options['service'],
                    'list' => $listId,
                    'doubleOptin' => $doubleOptin,
                    'confirm' => $confirm,
                    'context' => $contextData
                );
                
                if ( 'subscribed' === $result['status'] ) {
                    do_action('opanda_subscribed', $actionData);
                } else {
                    do_action('opanda_pending', $actionData); 
                }
            }
            
            return $result;
            
        } catch(Exception $ex) {
            throw new Opanda_HandlerException( $ex->getMessage() );
        }
    }
}
