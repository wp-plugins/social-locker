<?php

define('BIZPANDA_SOCIAL_LOCKER_DIR', dirname(__FILE__));
define('BIZPANDA_SOCIAL_LOCKER_URL', plugins_url( null, __FILE__ ));

if ( is_admin() ) require BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/boot.php';
global $bizpanda;

/**
 * Registers the Email Locker item.
 * 
 * @since 1.0.0
 */
function opanda_register_social_locker( $items ) {
    global $sociallocker;
    
    $title =  __('Social Locker', 'sociallocker');
        
        $items['social-locker'] = array(
            'name' => 'social-locker',
            'type' => 'free',
            'title' => $title,
            'help' => opanda_get_help_url('sociallocker'),
            'description' => __('<p>Asks users to "pay with a like" or share to unlock content.</p><p>Perfect way to get more followers, attract social traffic and improve some social metrics.</p>', 'sociallocker'),
            'shortcode' => 'sociallocker',
            'plugin' => $sociallocker
        ); 
        
    


    return $items;
}
add_filter('opanda_items', 'opanda_register_social_locker', 1);

/**
 * Adds options to print at the frontend.
 * 
 * @since 1.0.0
 */
function opanda_social_locker_options( $options, $id ) {
    global $post;

    $options['groups'] = array('social-buttons');
    $options['socialButtons'] = array();
        $buttonOrder = 'twitter-tweet,facebook-like,google-plus';
    

     
    $actualUrls = opanda_get_option('actual_urls', false);

    $postUrl = !empty($post) ? get_permalink( $post->ID ) : null;
    $postUrl = $actualUrls ? null : $postUrl;

    $fbLikeUrl = opanda_get_dynamic_url( $id, 'facebook_like_url', $postUrl);
    $fbShareUrl = opanda_get_dynamic_url( $id, 'facebook_share_url', $postUrl);
    $twTweetUrl = opanda_get_dynamic_url( $id, 'twitter_tweet_url', $postUrl);
    $twFollowUrl = opanda_get_dynamic_url( $id, 'twitter_follow_url', $postUrl);
    $glPlusUrl = opanda_get_dynamic_url( $id, 'google_plus_url', $postUrl);
    $glShareUrl = opanda_get_dynamic_url( $id, 'google_share_url', $postUrl);
    $lnShareUrl = opanda_get_dynamic_url( $id, 'linkedin_share_url', $postUrl);

    $options['socialButtons']['counters'] = opanda_get_item_option($id, 'show_counters', false, 1);
    $options['socialButtons']['order'] = opanda_get_item_option($id, 'buttons_order', false, $buttonOrder);

    $options['socialButtons']['facebook'] = array(
        'appId' => opanda_get_option('facebook_appid', '117100935120196'),
        'lang' => opanda_get_option('lang', 'en_GB'),
        'version' => opanda_get_option('facebook_version', 'v1.0'),
        'like' => array(
            'url' => $fbLikeUrl,
            'title' => opanda_get_item_option($id, 'facebook_like_title' ),
            'theConfirmIssue' => opanda_get_item_option($id, 'facebook_like_confirm_issue' )
        )
    );
    
    $options['socialButtons']['twitter'] = array(
        'lang' => opanda_get_option('short_lang', 'en'),
        'tweet' => array(
            'url' => $twTweetUrl,
            'text' => opanda_get_item_option($id, 'twitter_tweet_text' ),
            'counturl' => opanda_get_item_option($id, 'twitter_tweet_counturl' ),
            'title' => opanda_get_item_option($id, 'twitter_tweet_title' ),
            'via' => opanda_get_item_option($id, 'twitter_tweet_via' )
        )
    );

    $options['socialButtons']['google'] = array(   
        'lang' => opanda_get_option('google_lang', opanda_get_option('short_lang', 'en' )),
        'plus' => array(
            'url' => $glPlusUrl,
            'title' => opanda_get_item_option($id, 'google_plus_title' ) 
        )
    );
        
        if ( 'blurring' === $options['overlap']['mode'] ) {
            $options['overlap']['mode'] = 'transparence';
        }   
        
        if ( !in_array( $options['theme'] , array('default', 'secrets')) ) {
            $options['theme'] = 'secrets';
        }
      
    

    
    $allowedButtons = array('facebook-like', 'facebook-share', 'twitter-tweet', 'twitter-follow', 'google-plus', 'google-share', 'youtube-subscribe', 'linkedin-share');
    $allowedButtons = apply_filters('opanda_social-locker_allowed_buttons', $allowedButtons);
    
    if ( $options['socialButtons']['order'] ) {
        $options['socialButtons']['order'] = explode( ',', $options['socialButtons']['order'] );
    }
    
    if ( empty( $options['socialButtons']['order'] ) ) {
        unset( $options['socialButtons']['order'] );
    } else {
        $filteredButtons = array();
        foreach( $options['socialButtons']['order'] as $buttonName ) {
            if ( !in_array( $buttonName, $allowedButtons ) ) continue;
            $filteredButtons[] = $buttonName;
        }
        $options['socialButtons']['order'] = $filteredButtons;
    }
    
    // - Replaces shortcodes in the locker message and twitter text

    $postTitle = $post != null ? $post->post_title : '';
    $postUrl = $post != null ? get_permalink($post->ID) : '';

    if ( !empty( $options['socialButtons']['twitter']['tweet']['text'] ) ) {
        $options['socialButtons']['twitter']['tweet']['text'] = str_replace('[post_title]', $postTitle, $options['socialButtons']['twitter']['tweet']['text'] );
    }

    return $options;
}

add_filter('opanda_social-locker_item_options', 'opanda_social_locker_options', 10, 2);

/**
 * Requests assets for email locker.
 */
function opanda_social_locker_assets( $lockerId, $options, $fromBody, $fromHeader ) {
    OPanda_AssetsManager::requestLockerAssets();

    // Miscellaneous
    OPanda_AssetsManager::requestTextRes(array(
        'misc_close',
        'misc_or_wait'
    ));

    if ( isset( $options['opanda_buttons_order'] ) && strpos( $options['opanda_buttons_order'], 'facebook' ) !== false ) {
        OPanda_AssetsManager::requestFacebookSDK();  
    }
}

add_action('opanda_request_assets_for_social-locker', 'opanda_social_locker_assets', 10, 4);

/**
 * A shortcode for the Social Locker
 * 
 * @since 1.0.0
 */
class OPanda_SocialLockerShortcode extends OPanda_LockerShortcode {
    
    /**
     * Shortcode name
     * @var string
     */
    public $shortcodeName = array( 
        'sociallocker', 'sociallocker-1', 'sociallocker-2', 'sociallocker-3', 'sociallocker-4', 'sociallocker-bulk'
    );
    
    protected function getDefaultId() {
        return get_option('opanda_default_social_locker_id');
    }
}

FactoryShortcodes320::register( 'OPanda_SocialLockerShortcode', $bizpanda );