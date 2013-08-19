<?php

// Enabling lockers if we use the dynamic theme

add_action('init', 'onp_sociallocker_dt_init');
function onp_sociallocker_dt_init() {
    add_action( 'wp_footer', 'onp_sociallocker_dt_calls', 1000 ); 
    
    $dynamicTheme = get_option('sociallocker_dynamic_theme', false );
    if ( !$dynamicTheme ) return;
    
    add_action( 'wp_enqueue_scripts', 'onp_sociallocker_dt_enqueue_scripts' );
    add_action( 'wp_head', 'onp_sociallocker_facebook_sdk' );
}

/**
 * Including scripts and styles that needed when we use a dynamic theme.
 */
function onp_sociallocker_dt_enqueue_scripts() {
    
    wp_enqueue_style( 
        'onp-sociallocker', 
        SOCIALLOCKER_PLUGIN_URL . '/assets/css/jquery.op.sociallocker.020006.css'
    );  

    wp_enqueue_script( 
        'onp-sociallocker', 
        SOCIALLOCKER_PLUGIN_URL . '/assets/js/jquery.op.sociallocker.min.020016.js', 
        array('jquery', 'jquery-effects-core', 'jquery-effects-highlight'), false, true
    );  
    
    $facebookSDK = array( 
        'appId' => get_option('sociallocker_facebook_appid' ),
        'lang' => get_option('sociallocker_lang', 'en_US' ) 
    ); 
    
    wp_localize_script( 'onp-sociallocker', 'facebookSDK', $facebookSDK );
}

/**
 * Loading and initiing Facebook SDK when we use a dynamic theme.
 */
function onp_sociallocker_facebook_sdk() {
    
    $appId = get_option('sociallocker_facebook_appid' );
    $lang = get_option('sociallocker_lang', 'en_US' );

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

/**
 * Script that is placed at the bottom when a locker shortcode is rendering 
 * or when we use a dynamic theme.
 */
function onp_sociallocker_dt_calls() {
    $dynamicThemeEvent = get_option('sociallocker_dynamic_theme_event', false);
    $dynamicTheme = get_option('sociallocker_dynamic_theme', false );
    ?>
    <script>
        (function($){
            function onp_create_sociallcoker( $target ) {
                var lockId = $target.data('lock-id');
                var data = window[lockId] ? window[lockId] : $.parseJSON( $target.next().text() );

                var options = data.options;

                if ( data.ajax ) {
                    options.content = {
                        url: data.ajaxUrl, type: 'POST', data: {
                            lockerId: data.lockerId,
                            action: 'sociallocker_loader',
                            hash: data.contentHash
                        }
                    }
                }

                if ( data.postId && data.tracking ) {
                    if ( !options.events ) options.events = {};

                    options.events.unlock = function(sender, senderName){
                        if ( $.inArray(sender, ['cross', 'button', 'timer']) == -1 ) return;
 
                        $.ajax({ url: data.ajaxUrl, type: 'POST', data: {
                            action: 'sociallocker_tracking',
                            targetId: data.postId,
                            sender: sender,
                            senderName: senderName
                            }
                        });
                    }
                }

                $target.removeClass("onp-sociallocker-call");
                if ( !window[lockId] ) $target.next().remove();
                
                $target.sociallocker( options );
            }
        
            $(".onp-sociallocker-call").each(function(){
                onp_create_sociallcoker( $(this) );
            });
            
            <?php if ( $dynamicTheme ) { ?>
                <?php if ( !empty($dynamicThemeEvent) ) { ?> 
                $(document).bind('<?php echo $dynamicThemeEvent ?>', function(){
                    $(".onp-sociallocker-call").each(function(){
                        onp_create_sociallcoker( $(this) );
                    });
                });
                <?php } else { ?>
                $(document).ajaxComplete(function() {
                    $(".onp-sociallocker-call").each(function(){
                        onp_create_sociallcoker( $(this) );
                    });
                });
                <?php } ?>
            <?php } ?>
        })(jQuery);
    </script>
    <?php
}