<?php
/**
 * Ajax requests linked with collecting statistics.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

add_action('wp_ajax_opanda_statistics', 'opanda_statistics');
add_action('wp_ajax_nopriv_opanda_statistics', 'opanda_statistics');

/**
 * Increases counters in a database after unlocking content.
 * 
 * @since 1.0.0
 * @return void
 */
function opanda_statistics() {
    global $wpdb;
    
    $statsItem = isset( $_POST['opandaStats'] ) ? $_POST['opandaStats'] : array();
    $contextData = isset( $_POST['opandaContext'] ) ? $_POST['opandaContext'] : array();
    
    // event name
    
    $eventName = isset( $statsItem['eventName'] ) ? $statsItem['eventName'] : null;
    $eventName = opanda_normilize_value( $eventName );
    
    // sender type
    
    $eventType = isset( $statsItem['eventType'] ) ? $statsItem['eventType'] : null;
    $eventType = opanda_normilize_value( $eventType );
    
    // context data
    
    $context = isset( $_POST['opandaContext'] ) ? $_POST['opandaContext'] : array();
    $context = opanda_normilize_values( $context );
    
    $itemId = isset( $context['itemId'] ) ? $context['itemId'] : null;
    $postId = isset( $context['postId'] ) ? $context['postId'] : null;
    
    if ( empty( $itemId ) ) {
        echo json_encode( array( 'error' => __('Item ID is not specified.', 'optinpanda') ) );
        exit;
    }
    
    // counts the stats

    include_once(OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php');
    OPanda_Stats::processEvent( $itemId, $postId, $eventName, $eventType );
    
    echo json_encode( array('success' => true) );
    exit;
}

