<?php

/**
 * Re-creating the leads table, adding the fields table for leads, adds the option 'opanda_catch_leads' for lockers.
 * 
 * @since 4.1.2
 */
class SocialLockerUpdate040105 extends Factory325_Update {

    public function install() {
        
        $lockers = get_posts(array(
            'post_type' => OPANDA_POST_TYPE,
            'meta_key' => 'opanda_item',
            'meta_value' => 'social-locker',
            'numberposts' => -1
        ));
        
        foreach( $lockers as $locker ) {
            $url = get_post_meta( $locker->ID, 'opanda_common_url', true );
            if ( empty( $url ) ) continue;
            
            $facebookUrl = get_post_meta( $locker->ID, 'opanda_facebook_like_url', true );
            $twitterUrl = get_post_meta( $locker->ID, 'opanda_twitter_tweet_url', true );
            $googleUrl = get_post_meta( $locker->ID, 'opanda_google_plus_url', true );
            
            if ( empty( $facebookUrl ) ) update_post_meta( $locker->ID, 'opanda_facebook_like_url', $url );
            if ( empty( $twitterUrl ) ) update_post_meta( $locker->ID, 'opanda_twitter_tweet_url', $url );
            if ( empty( $googleUrl ) ) update_post_meta( $locker->ID, 'opanda_google_plus_url', $url );    
        }
    }
}