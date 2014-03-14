<?php

/**
 * Changes names of options because since the 3th version we introduced a new agreement of names.
 * 
 * @since 3.0.0
 */
class SocialLockerUpdate030000 extends Factory307_Update {

    public function install() {
        global $wpdb;
        
        // options which we should rename
        
        $optionsToRename = array(
            array( 'fy_license_sociallocker-next', 'onp_license_sociallocker-next' ),
            array( 'fy_default_license_sociallocker-next', 'onp_default_license_sociallocker-next' ),
            array( 'fy_version_check_sociallocker-next', 'onp_version_check_sociallocker-next' ),            
            array( 'fy_license_site_secret', 'onp_site_secret' ),
            array( 'fy_license_gate_token', 'onp_gate_token' ),
            array( 'fy_license_gate_expired', 'onp_gate_expired' ),
            array( 'default_sociallocker_locker_id', 'onp_sl_default_locker_id'),
            array( 'fy_version_check_sociallocker-next', 'onp_version_check_sociallocker-next'),
            array( 'fy_last_dcheck_sociallocker-next', 'onp_last_check_sociallocker-next')
        );
        
        foreach($optionsToRename as $item) {
            $wpdb->query(
                $wpdb->prepare( 
                    "UPDATE {$wpdb->prefix}options SET option_name = %s WHERE option_name = %s ", 
                     $item[1], $item[0])
            );
        }

        // meta values which we should fix
        
        $metaValueToFixBool = array(
            '"sociallocker_close"', '"sociallocker_ajax"',
            '"sociallocker_mobile"', '"sociallocker_highlight"',
            '"sociallocker_hide_for_member"', '"sociallocker_rss"',
            '"sociallocker_facebook-like_available"',
            '"sociallocker_twitter-tweet_available"',
            '"sociallocker_google-plus_available"',
            '"sociallocker_facebook-share_available"',
            '"sociallocker_twitter-follow_available"',
            '"sociallocker_google-share_available"',
            '"sociallocker_linkedin-share_available"'
        );
        
        $inClause = join(',', $metaValueToFixBool);
        $wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_value = 0 WHERE meta_key IN ($inClause) AND meta_value = 'false'");
        $wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_value = 1 WHERE meta_key IN ($inClause) AND meta_value = 'true'");
    }
}