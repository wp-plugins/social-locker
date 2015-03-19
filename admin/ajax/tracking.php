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

add_action('wp_ajax_sociallocker_tracking', 'onp_sl_tracking');
add_action('wp_ajax_nopriv_sociallocker_tracking', 'onp_sl_tracking');

/**
 * Increases counters in a database after unlocking content.
 * 
 * @since 1.0.0
 * @return void
 */
function onp_sl_tracking() {
    global $wpdb;
    
    $postId = isset( $_POST['targetId'] ) ? intval($_POST['targetId']) : 0;
    if (!$postId) exit;

    $sender = isset( $_POST['sender'] ) ? $_POST['sender'] : null;
    if (empty($sender) || !in_array($sender, array('button', 'timer', 'cross', 'na'))) exit;
    
    $senderName = !empty($_POST['senderName']) ? $_POST['senderName'] : false;
    
    include_once(ONP_SL_PLUGIN_DIR . '/includes/classes/stats.class.php');
    
    $statsManager = new StatsManager();
    $statsManager->saveTrack($postId, $sender, $senderName);
    
    echo json_encode( array('success' => true) );
    exit;
}

