<?php

/**
 * Adds a new column 'na_count'.
 * 
 * @since 3.7.2
 */
class SocialLockerUpdate030701 extends Factory325_Update {

    public function install() {
        global $wpdb;
            
        $sql = "
            CREATE TABLE {$wpdb->prefix}so_tracking (
              ID BIGINT(20) NOT NULL AUTO_INCREMENT,
              AggregateDate DATE NOT NULL,
              PostID BIGINT(20) NOT NULL,
              total_count INT(11) NOT NULL DEFAULT 0,  
              na_count INT(11) NOT NULL DEFAULT 0,
              facebook_like_count INT(11) NOT NULL DEFAULT 0,
              twitter_tweet_count INT(11) NOT NULL DEFAULT 0,
              google_plus_count INT(11) NOT NULL DEFAULT 0,
              timer_count INT(11) NOT NULL DEFAULT 0,
              cross_count INT(11) NOT NULL DEFAULT 0,  
              facebook_share_count INT(11) NOT NULL DEFAULT 0,
              twitter_follow_count INT(11) NOT NULL DEFAULT 0,
              google_share_count INT(11) NOT NULL DEFAULT 0,
              linkedin_share_count INT(11) NOT NULL DEFAULT 0,    
              PRIMARY KEY  (ID),
              KEY IX_wp_so_tracking_PostID (PostID),
              UNIQUE KEY UK_wp_so_tracking (AggregateDate,PostID)
            );";
        


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);   
    }
}