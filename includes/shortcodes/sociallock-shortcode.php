<?php

class SocialLockerShortcode extends FactoryPR108Shortcode {
    
    /**
     * Shortcode name
     * @var string
     */
    public $shortcode = array( 
        'sociallocker', 
        'sociallocker-1', 
        'sociallocker-2', 
        'sociallocker-3',
        'sociallocker-4',
        'sociallocker-5');  
        
    // -------------------------------------------------------------------------------------
    // Includes assets
    // -------------------------------------------------------------------------------------

    /**
     * Defines what assets need to include.
     * The method is called separate from the Render method during shortcode registration.
     * @param FactoryScriptList $scripts
     * @param FactoryStyleList $styles
     */
    public function assets(FactoryPR108ScriptList $scripts, FactoryPR108StyleList $styles) {
        
        $dynamicTheme = get_option('sociallocker_dynamic_theme', false );
        if ( $dynamicTheme ) return;
        
        add_action('wp_head', 'onp_sociallocker_facebook_sdk');
        
   	$facebookSDK = array( 
            'appId' => get_option('sociallocker_facebook_appid', '117100935120196' ),
            'lang' => get_option('sociallocker_lang', 'en_US' ) 
	); 
        
        $scripts->add('~/js/jquery.op.sociallocker.min.020202.js')
                ->request('jquery', 'jquery-effects-core', 'jquery-effects-highlight')
                ->localize('facebookSDK', $facebookSDK);

        // styles
        $styles->add('~/css/jquery.op.sociallocker.020006.css');
    }
    
    // -------------------------------------------------------------------------------------
    // Content render
    // -------------------------------------------------------------------------------------
        
    public function render($attr, $content) { 
        global $post;
        
        $lockData = array();
        $lockData['ajaxUrl'] = admin_url( 'admin-ajax.php' );
        
        if (!function_exists('sociallocker_get_meta')) {
            function sociallocker_get_meta($id, $name, $default = null) {
                $value = get_post_meta($id, 'sociallocker_' . $name, true);
                return empty( $value ) ? $default : stripslashes( $value );
            }
        }
        
        // - Options loading 

        // locker id
        $lockData['lockerId'] = $id = isset( $attr['id'] ) 
            ? (int)$attr['id'] 
            : get_option('default_sociallocker_locker_id');
        
        if ( empty($id) ) {
            echo '<div><strong>[Social Locker] The locked doesn\'t exist or the default lockers was deleted.</strong></div>'; 
            return;
        }
      
        $lockerMeta = get_post_meta($id, '');
        
        global $wp_embed;
        $content = $wp_embed->autoembed($content);
        $content = do_shortcode( $content );
            
            // - RSS and Members
            // if it's a premium build, check premium features such 
            // as RSS feeds and logged in users.
            
            if (is_feed()) {

                if (sociallocker_get_meta($id, 'rss') == 'true') {
                    echo $content;
                    return;
                } else {
                    return;
                }
            }

            if ( is_user_logged_in() && sociallocker_get_meta($id, 'hide_for_member') == 'true' ) {
                echo $content;
                return;
            }
        

        
        $content = preg_replace( '/^<br \/>/', '', $content );
        $content = preg_replace( '/<br \/>$/', '', $content );

        $isAjax = false;
        $lockData['ajax'] = false;
        
            // - AJAX
            // if it's a premium build, check is ajax required?

            $contentHash = null;

            if (isset( $lockerMeta['sociallocker_ajax'] ) && $lockerMeta['sociallocker_ajax'][0] == 'true') {
                $isAjax = true;

                $ajaxContent = '<p>' . $content . '</p>';
                $lockData['contentHash'] = $contentHash = md5( $ajaxContent );
                $lockData['ajax'] = true;
                
                $metaKey = 'sociallocker_locker_content_hash_' . $contentHash;

                if ( !isset( $lockerMeta[$metaKey] ) ) {
                    add_post_meta($id, $metaKey, $ajaxContent, true);
                }
            }
        

        
        $headerText = sociallocker_get_meta($id, 'header');
        $messageText = sociallocker_get_meta($id, 'message');
        
        // - Stats
        // Check tracking request

        $lockData['tracking'] = get_option('sociallocker_tracking', true);
        $lockData['postId'] = !empty($post) ? $post->ID : false;
        
        // Builds array of options to set into the jquery plugin
            
            $postUrl = !empty($post) ? get_permalink( $post->ID ) : null;
            
            $fbLikeUrl = sociallocker_get_meta($id, 'facebook_like_url', $postUrl );
            $fbShareUrl = sociallocker_get_meta($id, 'facebook_share_url', $postUrl );
            $twTweetUrl = sociallocker_get_meta($id, 'twitter_tweet_url', $postUrl );
            $twFollowUrl = sociallocker_get_meta($id, 'twitter_follow_url', $postUrl );
            $glPlusUrl = sociallocker_get_meta($id, 'google_plus_url', $postUrl );      
            $glShareUrl = sociallocker_get_meta($id, 'google_share_url', $postUrl );
            $lnShareUrl = sociallocker_get_meta($id, 'linkedin_share_url', $postUrl );            
            
            // PREMIUM build options
            $params = array(
                'demo' => get_option('sociallocker_debug', false ),
                
                'text' => array(
                    'header' => empty($headerText) ? ' ' : $headerText, 
                    'message' => empty($messageText) ? ' ' : $messageText             
                ),

                'theme' => sociallocker_get_meta($id, 'style'),

                'effects' => array(
                    'highlight' => sociallocker_get_meta($id, 'highlight')
                ),

                'buttons' => array(
                    'order' => explode( ',', sociallocker_get_meta($id, 'buttons_order') )
                ),       

                'locker' => array(
                    'close'     => sociallocker_get_meta($id, 'close'),
                    'timer'     => sociallocker_get_meta($id, 'timer'),
                    'mobile'    => sociallocker_get_meta($id, 'mobile'),        
                ),
                'facebook' => array(
                    'appId' => get_option('sociallocker_facebook_appid', '117100935120196' ),
                    'lang' => get_option('sociallocker_lang', 'en_GB' ),
                    'like' => array(
                        'url' => $fbLikeUrl,
                        'title' => sociallocker_get_meta($id, 'facebook_like_title' ) 
                    ),
                    'share' => array(
                        'url' => $fbShareUrl,
                        'counter' => sociallocker_get_meta($id, 'facebook_share_counter_url' ),
                        'title' => sociallocker_get_meta($id, 'facebook_share_title' ),
                        'name' => sociallocker_get_meta($id, 'facebook_share_message_name' ),
                        'caption' => sociallocker_get_meta($id, 'facebook_share_message_caption' ),
                        'description' => sociallocker_get_meta($id, 'facebook_share_message_description' ),
                        'image' => sociallocker_get_meta($id, 'facebook_share_message_image' )
                    ),
                ),
                'twitter' => array(
                    'lang' => get_option('sociallocker_short_lang', 'en' ),
                    'tweet' => array(
                        'url' => $twTweetUrl,
                        'text' => sociallocker_get_meta($id, 'twitter_tweet_text' ),
                        'counturl' => sociallocker_get_meta($id, 'twitter_tweet_counturl' ),
                        'title' => sociallocker_get_meta($id, 'twitter_tweet_title' ) 
                    ),
                    'follow' => array(
                        'url' => $twFollowUrl,
                        'title' => sociallocker_get_meta($id, 'twitter_follow_title' ) 
                    )
                ),  
                'google' => array(   
                    'lang' => get_option('sociallocker_google_lang', get_option('sociallocker_short_lang', 'en' )),
                    'plus' => array(
                        'url' => $glPlusUrl,
                        'title' => sociallocker_get_meta($id, 'google_plus_title' ) 
                    ),
                    'share' => array(
                        'url' => $glShareUrl,
                        'title' => sociallocker_get_meta($id, 'google_share_title' ) 
                    ),
                ),  
                'linkedin' => array(   
                    'share' => array(
                        'url' => $lnShareUrl,
                        'title' => sociallocker_get_meta($id, 'linkedin_share_title' ) 
                    ),
                )
            );
        

        
        if ( 
           !isset( $params['buttons'] ) || 
           !isset( $params['buttons']['order'] ) || 
            empty( $params['buttons']['order'] ) ) {
            
            unset( $params['buttons'] );
        }
        
        // - Replaces shortcodes in the locker message and twitter text
        
        $postTitle = $post != null ? $post->post_title : '';
        $postUrl = $post != null ? get_permalink($post->ID) : '';
        
        if ( !empty($params['twitter']['tweet']['text'] ) ) {
            $params['twitter']['tweet']['text'] = str_replace('[post_title]', $postTitle, $params['twitter']['tweet']['text']);
        }
        
        if ( !empty($params['text'] ) ) {
            $params['text'] = str_replace('[post_title]', $postTitle, $params['text']);
            $params['text'] = str_replace('[post_url]', $postUrl, $params['text']);  
        }
        
        if (empty( $params['text']['header'] )) {
            $params['text'] = $params['text']['message'];
        }
	
        $this->clearParams( $params );
        $lockData['options'] = $params;
        
        $dynamicTheme = get_option('sociallocker_dynamic_theme', false );
        $this->lockId = "onpLock" . rand(100000, 999999);
        $this->lockData = $lockData;

        if ($isAjax) { ?>
            <div class="onp-sociallocker-call" style="display: none;" data-lock-id="<?php echo $this->lockId ?>"></div>
        <?php } else { ?>
            <div class="onp-sociallocker-call" style="display: none;" data-lock-id="<?php echo $this->lockId ?>">
                <p><?php echo  $content ?></p>
            </div>
        <?php } ?> 

        <?php 
        
        if ( $dynamicTheme ) { ?>
            <div class="onp-sociallocker-params" style="display: none;">
                <?php echo json_encode( $lockData ) ?>
            </div>
        <?php } else {
           add_action('wp_footer', array($this, 'wp_footer'), 1);
        }
    }
    
    public function wp_footer() {
        $dynamicTheme = get_option('sociallocker_dynamic_theme', false );
        if ( !$dynamicTheme ) $this->print_options();
    }
    
    public function print_options() { 
    ?>
        <script>
            window['<?php echo $this->lockId; ?>'] = <?php echo json_encode( $this->lockData ) ?>;
        </script>
    <?php
    }
    
    public function clearParams( &$params ) {
        
        foreach( $params as $key => &$item ) {
            
            if ( $item === '' || $item === null || $item === 0 ) {
                unset( $params[$key] );
                continue;
            }
            
            if ( $item === 'true' ) {
                $params[$key] = true;
                continue;
            }            
            
            if ( $item === 'false' ) {
                $params[$key] = false;
                continue;
            }               
            
            if ( gettype($item) == 'array' ) {
                $this->clearParams( $params[$key] );
            }
        }
    }
    
    // -------------------------------------------------------------------------------------
    // Loading content via ajax if required
    // -------------------------------------------------------------------------------------
    
    public function registerForAdmin() {

        add_action('wp_ajax_sociallocker_loader', array($this, 'loadAjaxContent'));
        add_action('wp_ajax_nopriv_sociallocker_loader', array($this, 'loadAjaxContent'));
    }
    
    public function loadAjaxContent() {

        $hash = $_POST['hash'];
        $lockerId = @intval( $_POST['lockerId'] );
        
        if (empty($hash) || empty($lockerId)) return "";
        
        echo get_post_meta($lockerId, 'sociallocker_locker_content_hash_' . $hash, true);
        die();
    }
    
    // -------------------------------------------------------------------------------------
    // Shortcode Tracking
    // -------------------------------------------------------------------------------------
    
    /**
     * Defines whether the changes of post what includes shortcodes are tracked.
     * @var boolean 
     */
    public $tracking = true;
    
    /**
     * The function that will be called when a post containing a current shortcode is changed. 
     * @param string $shortcode
     * @param mixed[] $attr
     * @param string $content
     * @param integer $postId
     */
    public function trackingCallback($shortcode, $attr, $content, $postId) { 
 
        $id = isset( $attr['id'] ) 
            ? (int)$attr['id'] 
            : get_option('default_sociallocker_locker_id');
        
        $lockerMeta = get_post_meta($id, '');
        if (empty($lockerMeta)) return;
        
        foreach($lockerMeta as $metaKey => $metaValue) {
            if (strpos($metaKey, 'sociallocker_locker_content_hash_') === 0) {
                delete_post_meta($id, $metaKey);
            }
        }
    }
}

$socialLocker->registerShortcode('SocialLockerShortcode');