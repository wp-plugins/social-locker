<?php #comp-page builds: premium

/**
 * Updates for altering the table used to store statistics data.
 * Adds new columns and renames existing ones in order to add support for the new social buttons.
 */
class SocialLockerUpdate020006 extends Factory325_Update {

    public function install() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $tablefields = $wpdb->get_results("DESCRIBE {$wpdb->prefix}so_tracking");
        if ( count($tablefields) > 0 ) {
            
            $names = array();
            foreach($tablefields as $column) {
                $names[] = $column->Field;
            }

            if ( in_array('LikeCount', $names) ) {
            
                // renames the old columns to save data after chaning the table
                $sql = "ALTER TABLE {$wpdb->prefix}so_tracking
                CHANGE LikeCount facebook_like_count INT(11) NOT NULL DEFAULT 0,
                CHANGE TweetCount twitter_tweet_count INT(11) NOT NULL DEFAULT 0,
                CHANGE PlusCount google_plus_count INT(11) NOT NULL DEFAULT 0,
                CHANGE TimerCount timer_count INT(11) NOT NULL DEFAULT 0,
                CHANGE CrossCount cross_count INT(11) NOT NULL DEFAULT 0,
                CHANGE TotalCount total_count INT(11) NOT NULL DEFAULT 0,
                ADD facebook_share_count INT(11) NOT NULL DEFAULT 0,
                ADD twitter_follow_count INT(11) NOT NULL DEFAULT 0,
                ADD google_share_count INT(11) NOT NULL DEFAULT 0,
                ADD linkedin_share_count INT(11) NOT NULL DEFAULT 0";

                $result = $wpdb->query($sql);

                if ( $result === false ) {
                   $wpdb->show_errors();
                   $wpdb->print_error(); 
                   exit;
                }   
            }
        }
    }
}