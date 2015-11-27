<?php

/**
 * The class to proxy the request to the LinkedIn API.
 */
class OPanda_LinkedinHandler extends OPanda_Handler {

    /**
     * Handles the proxy request.
     */
    public function handleRequest() {
        
        // the request type is to determine which action we should to run
        $requestType = !empty( $_REQUEST['opandaRequestType'] ) ? $_REQUEST['opandaRequestType'] : null;

        // allowed request types, others will trigger an error
        $allowed = array('init', 'callback', 'user_info', 'follow', 'tweet');
        
        if ( empty( $requestType ) || !in_array($requestType, $allowed) )
            throw new Opanda_HandlerException('Invalid request type.'); 
        
        $accessToken = !empty( $_REQUEST['opandaAccessToken'] ) ? $_REQUEST['opandaAccessToken'] : null;

        require_once( 'libs/Client.php');

        switch( $requestType ) {
            
            case 'init':
                $this->doInit(); 
 
            case 'callback':
                $this->doCallback(); 
            
            case 'user_info':
                $this->getUserData( $accessToken );   
        }
    }
    
    /**
     * Build the callback URL for Twitter.
     */
    public function getCallbackUrl() {
        $proxy = $this->options['proxy'];
        $prefix = ( strpos( $proxy, '?') === false) ? '?' : '&';
        return $proxy . $prefix . 'opandaHandler=linkedin&opandaRequestType=callback';
    }
    
    /**
     * Inits an OAuth request.
     */
    public function doInit() {
        $options = $this->options;

        $client = new OPanda_LinkedIn_Client($options['client_id'], $options['client_secret']);
        $authorizeURL = $client->getAuthorizationUrl( $this->getCallbackUrl() );

        header("Location: $authorizeURL");
        exit;
    }
    
    /**
     * Handles a callback from Twitter (when the user has been redirected)
     */
    public function doCallback() {
        $options = $this->options;
        
        $denied = isset( $_REQUEST['error'] );
        if ( $denied ) { 
        ?>
            <script>
                if( window.opener ) window.opener.OPanda_LinkedInOAuthDenied( '<?php echo $visitorId ?>' );                
                window.close();                
            </script>
        <?php
        exit;
        }
        
        $code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : false;
        if ( empty( $code ) ) throw new Opanda_HandlerException('Invalid code.');
        
        $client = new OPanda_LinkedIn_Client($options['client_id'], $options['client_secret']);
        $response = $client->fetchAccessToken($code, $this->getCallbackUrl());

        if ( !isset( $response['access_token'] ) ) {
            throw new Opanda_HandlerException('Invalid request.');
        }
        
        $accessToken = $response['access_token'];
        
        ?>
            <script>
                if( window.opener ) window.opener.OPanda_LinkedInOAuthCompleted( '<?php echo $accessToken ?>' );                
                window.close();                
            </script>
        <?php
        
        exit;
    }
    
    public function getUserData( $accessToken  ) {
        require_once( 'libs/Client.php');
                
        $options = $this->options;
        
        $client = new OPanda_LinkedIn_Client($options['client_id'], $options['client_secret']);
        $client->setAccessToken( $accessToken );

        $fields = array("firstName", "lastName", "emailAddress", "publicProfileUrl", "pictureUrls::(original)");

        $response = $client->fetch('/v1/people/~:(' . implode(',', $fields) . ')');
        echo json_encode($response);

        exit;
    }
}


