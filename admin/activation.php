<?php

class OnpSL_Activation extends Factory321_Activator {
    
    public function activate() {       
        // sets the default licence
        // the default license is a license that is used when a license key is not activated

            $this->plugin->license->setDefaultLicense( array(
                'Category'      => 'free',
                'Build'         => 'free',
                'Title'         => __('OnePress Public License', 'sociallocker'),
                'Description'   => __('Public License is a GPLv2 compatible license. 
                                    It allows you to change this version of the plugin and to
                                    use the plugin free. Please remember this license 
                                    covers only free edition of the plugin. Premium versions are 
                                    distributed with other type of a license.', 'sociallocker')
            ));
        


        // options
        
	add_option('sociallocker_facebook_appid', '117100935120196');
	add_option('sociallocker_lang', 'en_US');
        add_option('sociallocker_short_lang', 'en');

        // pages and posts
        
        $baseLockerInfo = $this->addPost(
            'onp_sl_default_locker_id',
            array(
                'post_type' => 'social-locker',
                'post_title' => __('Default Locker', 'sociallocker'),
                'post_name' => 'default_sociallocker_locker'
            ),
            array(
                'sociallocker_header' => __('This content is locked!', 'sociallocker'),       
                'sociallocker_message' => __('Please support us, use one of the buttons below to unlock the content.', 'sociallocker'),
                'sociallocker_style' => 'secrets',
                'sociallocker_mobile' => 1,          
                'sociallocker_highlight' => 1,                   
                'sociallocker_is_system' => 1,
                'sociallocker_is_default' => 'block'
            )
        );
        
        add_option('sociallocker_tracking', 'true');
        add_option('sociallocker_just_social_buttons', 'false');        

        // tables
        global $wpdb;
            
        $sql = "
            CREATE TABLE {$wpdb->prefix}so_tracking (
              ID BIGINT(20) NOT NULL AUTO_INCREMENT,
              AggregateDate DATE NOT NULL,
              PostID BIGINT(20) NOT NULL,
              total_count INT(11) NOT NULL DEFAULT 0,
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

$sociallocker->registerActivation('OnpSL_Activation');