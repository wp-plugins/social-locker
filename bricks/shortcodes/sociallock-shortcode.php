<?php

class SocialLockShortcode extends FactoryFR103Shortcode {
    
    /**
     * Shortcode name
     * @var string
     */
    public $shortcode = 'sociallocker';
        
    // -------------------------------------------------------------------------------------
    // Includes assets
    // -------------------------------------------------------------------------------------
    
    /**
     * Array used to store js params to call the jquery plugin.
     * @var type
     */
    public $jsCalls = array();

    /**
     * Defines what assets need to include.
     * The method is called separate from the Render method during shortcode registration.
     * @param FactoryScriptList $scripts
     * @param FactoryStyleList $styles
     */
    public function assets(FactoryFR103ScriptList $scripts, FactoryFR103StyleList $styles) {
        
        add_action('wp_head', array($this, 'facebookConnect'));
        
   	$facebookSDK = array( 
            'appId' => get_option('sociallocker_facebook_appid', '117100935120196' ),
            'lang' => get_option('sociallocker_lang', 'en_US' ) 
	); 
        
        $scripts->add('~/js/jquery.op.sociallocker.min.020013.js')
                ->request('jquery', 'jquery-effects-core', 'jquery-effects-highlight')
                ->localize('facebookSDK', $facebookSDK);

        // styles
        $styles->add('~/css/jquery.op.sociallocker.020006.css');
    }
    
    public function facebookConnect() {

        $appId = get_option('sociallocker_facebook_appid', '117100935120196' );
        $lang = get_option('sociallocker_lang', 'en_US' );
        $shortLang = get_option('sociallocker_short_lang', 'en' );
        
        ?>
        <!-- 
            Social Locker (Facebook SDK)
            for jQuery: http://onepress-media.com/plugin/social-locker-for-jquery/get
            for Wordpress: http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
            window.fbAsyncInit = function() {
                window.FB.init({
                    appId: <?php echo $appId ?>,
                    status: true,
                    cookie: true,
                    xfbml: true
                });
                window.FB.init = function(){};
            };
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/<?php echo $lang ?>/all.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- / -->
        <?php
    }
    
    // -------------------------------------------------------------------------------------
    // Content render
    // -------------------------------------------------------------------------------------
        
    public function render($attr, $content) { 
        global $post;
        
        if (!function_exists('sociallocker_get_meta')) {
            function sociallocker_get_meta($id, $name) {
                $value = get_post_meta($id, 'sociallocker_' . $name, true);
                return empty( $value ) ? $value : stripslashes( $value );
            }
        }
        
        // - Options loading 

        // locker id
        $id = isset( $attr['id'] ) ? (int)$attr['id'] : get_option('default_' . $this->shortcode . '_locker_id');

        if ( empty($id) ) {
            echo '<div><strong>[Social Locker] The locked doesn\'t exist or the default lockers was deleted.</strong></div>'; 
            return;
        }
      
        $lockerMeta = get_post_meta($id, '');
        
        global $wp_embed;
        $content = $wp_embed->autoembed($content);
        $content = do_shortcode( $content );
        
        $content = preg_replace( '/^<br \/>/', '', $content );
        $content = preg_replace( '/<br \/>$/', '', $content );
        
        $headerText = sociallocker_get_meta($id, 'header');
        $messageText = sociallocker_get_meta($id, 'message');
        
        // Builds array of options to set into the jquery plugin
            
            // FREE build options
            $params = array(
                'demo' => get_option('sociallocker_debug', false ),
                
                'text' => array(
                    'header' => empty($headerText) ? '' : $headerText, 
                    'message' => empty($messageText) ? '' : $messageText         
                ),

                'theme' => 'secrets',

                'facebook' => array(
                    'url' => sociallocker_get_meta($id, 'common_url' ),
                    'appId' => get_option('sociallocker_facebook_appid', '117100935120196' ),
                    'lang' => get_option('sociallocker_lang', 'en_GB' ),
                ),
                'twitter' => array(
                    'url' => sociallocker_get_meta($id, 'common_url' ),     
                    'lang' => get_option('sociallocker_short_lang', 'en' ),
                    'counturl' => sociallocker_get_meta($id, 'twitter_counturl' )
                ),  
                'google' => array(
                    'url' => sociallocker_get_meta($id, 'common_url' ),    
                    'lang' => get_option('sociallocker_short_lang', 'en' ),
                ),        
                'events' => array(
                    'ready' => 'function(state){}',             
                    'lock' => 'function(sender, senderName){}',
                    'unlock' => 'function(sender, senderName){}',    
                )     
            );

        

        
        // - Replaces shortcodes in the locker message and twitter text
        
        $postTitle = $post != null ? $post->post_title : '';
        $postUrl = $post != null ? get_permalink($post->ID) : '';
        
        if ( !empty($params['twitter']['text'] ) ) {
            $params['twitter']['text'] = str_replace('[post_title]', $postTitle, $params['twitter']['text']);
        }
        
        if ( !empty($params['text'] ) ) {
            $params['text'] = str_replace('[post_title]', $postTitle, $params['text']);
            $params['text'] = str_replace('[post_url]', $postUrl, $params['text']);  
        }
        
        if (empty( $params['text']['header'] )) {
            $params['text'] = $params['text']['message'];
        }
	
        $this->clearParams( $params );

        // - Markup and script generation 

        $blockId = "lock-" . rand(100000, 999999);
        $resultSelector = '#' . $blockId;
            
            ?>
              <div id='<?php echo $blockId ?>' style="display: none;">
              <p><?php echo  $content ?></p>
              </div>
            <?php
            
            $this->jsCalls = array(
                'id' => $id,
                'ajax'      => false,
                'selector'  => $resultSelector,
                'params'    => $params
            );
        


        add_action('wp_footer', array(&$this, 'callLocker'), 1000);
    }

    public function callLocker() {
        $call = $this->jsCalls;
        
        $readyEvent = $call['params']['events']['ready'];
        $lockEvent = $call['params']['events']['lock'];
        $unlockEvent = $call['params']['events']['unlock'];

        unset($call['params']['events']);

        $params = $call['params'];
        unset($params['buttons']);
        ?>
              
        <!-- 
            Social Locker
            for jQuery: http://onepress-media.com/plugin/social-locker-for-jquery/get
            for Wordpress: http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
            (function($){
                $(function(){
                    
                    var onpSL = <?php echo json_encode( $params ) ?>;
                    <?php if ( isset($call['params']['buttons'])) { ?>
                        
                    onpSL['buttons'] = {};
                    onpSL['buttons']['order'] = <?php echo json_encode( $call['params']['buttons']['order'] ) ?>;
                    <?php } ?>
                        
                    <?php if (!empty($call['ajax'])) { ?>
                        
                    onpSL.content = {
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        type: 'POST',
                        data: {
                            lockerId: '<?php echo $call['id'] ?>',
                            action: 'sociallocker_loader',
                            hash: '<?php echo $call['contentHash'] ?>'
                        }
                    }
                    <?php } ?>
                
                    onpSL.events = {
                        ready: <?php echo $readyEvent ?>,
                        lock: <?php echo $lockEvent ?>,
                        unlock: <?php echo $unlockEvent ?>
                        
                    };
                    
                    $("<?php echo $call['selector'] ?>").socialLock( onpSL );
                    
                })
            })(jQuery);
        </script>
        <!-- / -->
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
            : get_option('default_' . $shortcode . '2_locker_id');
        
        $lockerMeta = get_post_meta($id, '');
        if (empty($lockerMeta)) return;
        
        foreach($lockerMeta as $metaKey => $metaValue) {
            if (strpos($metaKey, 'sociallocker_locker_content_hash_') === 0) {
                delete_post_meta($id, $metaKey);
            }
        }
    }
}