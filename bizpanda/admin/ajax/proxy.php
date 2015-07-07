<?php

add_action('wp_ajax_opanda_connect', 'opanda_connect');
add_action('wp_ajax_nopriv_opanda_connect', 'opanda_connect');

/**
 * Proccess all the requests from the jQuery version of the Opt-In Panda.
 * 
 * @since 1.0.0
 * @return void
 */
function opanda_connect() {    
    define('OPANDA_PROXY', true);
    error_reporting(1);  
    
    $handlerName = isset( $_REQUEST['opandaHandler'] ) ? $_REQUEST['opandaHandler'] : null;
    $allowed = array('twitter', 'subscription', 'signup', 'lead');

    if ( empty( $handlerName ) || !in_array( $handlerName, $allowed ) ) {
        header( 'Status: 403 Forbidden' );
        header( 'HTTP/1.1 403 Forbidden' );
        exit;
    }

    if ( defined( 'OPANDA_WORDPRESS' ) ) {
        $options = opanda_get_handler_options( $handlerName );
    } else {
        require "config.php";
        $options = $options[$handlerName];
    }

    require OPANDA_BIZPANDA_DIR . "/admin/includes/connect-handlers/class.handler.php";
    require OPANDA_BIZPANDA_DIR . "/admin/includes/connect-handlers/handlers/$handlerName/$handlerName.php";

    $handlerClass = 'OPanda_' . ucwords( $handlerName ) . 'Handler';
    $handler = new $handlerClass( $options );

    try {
        $result = $handler->handleRequest();
        echo json_encode( $result );

    } catch (Opanda_HandlerInternalException $ex) {
        echo json_encode(array('error' => $ex->getMessage(), 'detailed' => $ex->getDetailed()));
    } catch (Opanda_HandlerException $ex) {
        echo json_encode(array('error' => $ex->getMessage()));
    } catch(Exception $ex) {
        echo json_encode(array('error' => $ex->getMessage())); 
    }
    
    exit;
}

/**
 * Returns the lists available for the current subscription service.
 * 
 * @since 1.0.0
 * @return void
 */
function opanda_get_subscrtiption_lists() {

    require OPANDA_BIZPANDA_DIR.'/admin/includes/subscriptions.php';    
    
    try {
        
        $service = OPanda_SubscriptionServices::getCurrentService();

        $lists = $service->getLists();
        echo json_encode($lists); 
        
    } catch (Exception $ex) {
        echo json_encode( array('error' => 'Unable to get the lists: ' . $ex->getMessage() ) ); 
    }

    exit;
}

add_action( 'wp_ajax_opanda_get_subscrtiption_lists', 'opanda_get_subscrtiption_lists' );

/**
 * Returns the lists available for the current subscription service.
 * 
 * @since 1.0.0
 * @return void
 */
function opanda_get_custom_fields() {

    require OPANDA_BIZPANDA_DIR.'/admin/includes/subscriptions.php';    
    
    try {
        
        $listId = isset( $_POST['opanda_list_id'] ) ? $_POST['opanda_list_id'] : null;
        $service = OPanda_SubscriptionServices::getCurrentService();

        $fields = $service->getCustomFields( $listId );
        echo json_encode($fields); 
        
    } catch (Exception $ex) {
        echo json_encode( array('error' => $ex->getMessage() ) ); 
    }

    exit;
}

add_action( 'wp_ajax_opanda_get_custom_fields', 'opanda_get_custom_fields' );