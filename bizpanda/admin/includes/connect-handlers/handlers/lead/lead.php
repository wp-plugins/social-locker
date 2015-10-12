<?php
if ( !defined('OPANDA_PROXY') ) exit;

/**
 * The class to proxy the request to the Twitter API.
 */
class OPanda_LeadHandler extends OPanda_Handler {

    /**
     * Handles the proxy request.
     */
    public function handleRequest() {
        
        // - context data
        
        $contextData = isset( $_POST['opandaContextData'] ) ? $_POST['opandaContextData'] : array();
        $contextData = $this->normilizeValues( $contextData );
        
        // - idetity data
        
        $identityData = isset( $_POST['opandaIdentityData'] ) ? $_POST['opandaIdentityData'] : array();
        $identityData = $this->normilizeValues( $identityData );
        
        // prepares data received from custom fields to be transferred to the mailing service
        
        $identityData = $this->prepareDataToSave( null, null, $identityData );
        do_action('opanda_lead_catched', $identityData, $contextData);
    }
}


