<?php

add_action('wp_ajax_sociallocker_tracking', 'sociallocker_tracking');
add_action('wp_ajax_nopriv_sociallocker_tracking', 'sociallocker_tracking');

function sociallocker_tracking() {
    global $wpdb;
    
    $postId = intval($_POST['targetId']);
    if (!$postId) exit;

    $sender = $_POST['sender']; 
    if (empty($sender) || !in_array($sender, array('button', 'timer', 'cross'))) exit;

    $senderName = !empty($_POST['senderName']) ? $_POST['senderName'] : false;
    
    $insertPart = false;
    $updatePart = false;
    
    switch($sender) {
        
        case 'timer':
            $insertPart = '0,0,0,0,1,0';
            $updatePart = 'TimerCount = TimerCount + 1';
            break;
        
        case 'cross':
            $insertPart = '0,0,0,0,0,1';
            $updatePart = 'CrossCount = CrossCount + 1';
            break;
        
        case 'button':
            
            switch($senderName) {
            
                case 'facebook':
                    $insertPart = '1,1,0,0,0,0';
                    $updatePart = 'LikeCount = LikeCount + 1, TotalCount = TotalCount + 1';
                    break;
                case 'twitter':
                    $insertPart = '1,0,1,0,0,0'; 
                    $updatePart = 'TweetCount = TweetCount + 1, TotalCount = TotalCount + 1';
                    break;
                case 'google':
                    $insertPart = '1,0,0,1,0,0'; 
                    $updatePart = 'PlusCount = PlusCount + 1, TotalCount = TotalCount + 1';
                    break;
            }
            break;
    }

    if (!$insertPart || !$updatePart) exit;
    
    $hrsOffset = get_option('gmt_offset');
    if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
    $hrsOffset .= ' hours';
    
    $time = strtotime($hrsOffset, time());
    
    $date = date("Y-m-d", $time);
    
    $sql = "INSERT INTO {$wpdb->prefix}so_tracking 
            (AggregateDate, PostID, TotalCount, LikeCount, TweetCount, PlusCount, TimerCount, CrossCount) 
            VALUES ('$date',$postId, $insertPart)
            ON DUPLICATE KEY UPDATE $updatePart";
    
    $wpdb->query($sql);    
    exit;
}