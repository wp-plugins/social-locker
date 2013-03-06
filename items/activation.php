<?php

class SocialLockerActivate extends OnePressFR100Activation {
    
    public function activate() {
        parent::activate();
        
        // options
        
	add_option('sociallocker_facebook_appid', '117100935120196');
	add_option('sociallocker_lang', 'en_US');
        add_option('sociallocker_short_lang', 'en');
        add_option('sociallocker_tracking', 'true');

        // pages and posts
        
        $baseLockerInfo = $this->addPost(
            'default_sociallocker_locker_id',
            array(
                'post_type' => 'social-locker',
                'post_title' => 'Default Locker',
                'post_name' => 'default_sociallocker_locker'
            ),
            array(
                'sociallocker_text_header' => 'This content is locked!',       
                'sociallocker_text_message' => 'Please support us, use one of the buttons below to unlock the content.',
                'sociallocker_style' => 'ui-social-locker-secrets',
                'sociallocker_close' => false,
                'sociallocker_mobile' => true,          
                'sociallocker_timer' => 0,
                'sociallocker_ajax' => false,
                'sociallocker_highlight' => true,
                'sociallocker_rss' => false,
                
                'sociallocker_buttons_order' => 'twitter,facebook,google',
                'sociallocker_facebook_available' => true,  
                'sociallocker_twitter_available' => true, 
                'sociallocker_google_available' => true,              
                
                'sociallocker_is_system' => true,
                'sociallocker_is_default' => 'block'
            )
        );
    } 
}