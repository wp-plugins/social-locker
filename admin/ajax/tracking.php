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
            $insertPart = '0,0,0,0,1,0,0,0,0,0';
            $updatePart = 'timer_count = timer_count + 1';
            break;
        
        case 'cross':
            $insertPart = '0,0,0,0,0,1,0,0,0,0';
            $updatePart = 'cross_count = cross_count + 1';
            break;
        
        case 'button':
            
            switch($senderName) {
            
                case 'facebook-like':
                    $insertPart = '1,1,0,0,0,0,0,0,0,0';
                    $updatePart = 'facebook_like_count = facebook_like_count + 1, total_count = total_count + 1';
                    break;
                case 'twitter-tweet':
                    $insertPart = '1,0,1,0,0,0,0,0,0,0'; 
                    $updatePart = 'twitter_tweet_count = twitter_tweet_count + 1, total_count = total_count + 1';
                    break;
                case 'google-plus':
                    $insertPart = '1,0,0,1,0,0,0,0,0,0'; 
                    $updatePart = 'google_plus_count = google_plus_count + 1, total_count = total_count + 1';
                    break;
                
                case 'facebook-share':
                    $insertPart = '1,0,0,0,0,0,1,0,0,0';
                    $updatePart = 'facebook_share_count = facebook_share_count + 1, total_count = total_count + 1';
                    break;
                case 'twitter-follow':
                    $insertPart = '1,0,0,0,0,0,0,1,0,0'; 
                    $updatePart = 'twitter_follow_count = twitter_follow_count + 1, total_count = total_count + 1';
                    break;
                case 'google-share':
                    $insertPart = '1,0,0,0,0,0,0,0,1,0'; 
                    $updatePart = 'google_share_count = google_share_count + 1, total_count = total_count + 1';
                    break;
                
                case 'linkedin-share':
                    $insertPart = '1,0,0,0,0,0,0,0,0,1'; 
                    $updatePart = 'linkedin_share_count = linkedin_share_count + 1, total_count = total_count + 1';
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
            (AggregateDate, PostID, total_count, facebook_like_count, twitter_tweet_count, google_plus_count, timer_count, cross_count, facebook_share_count, twitter_follow_count, google_share_count, linkedin_share_count) 
            VALUES ('$date',$postId, $insertPart)
            ON DUPLICATE KEY UPDATE $updatePart";
    
    $wpdb->query($sql);    
    exit;
}