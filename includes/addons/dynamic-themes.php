<?php

// Enabling lockers if we use the dynamic theme

add_action('init', 'onp_sociallocker_dt_init');
function onp_sociallocker_dt_init() {
    add_action( 'wp_head', 'onp_sociallocker_dt_calls', 1000 ); 
    add_action( 'wp_footer', 'onp_sociallocker_script', 9999 );
    
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
        SOCIALLOCKER_PLUGIN_URL . '/assets/js/jquery.op.sociallocker.min.020204.js', 
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
 * Inits rendering of the locker scripts.
 */
function onp_sociallocker_script() {
    ?>
    <script>
    (function($){
        if ( window.onp_create_sociallcoker ) {
            $(".onp-sociallocker-call").each(function(){
                onp_create_sociallcoker( $(this) );
            });  
        } else {
            $(function(){
                $(".onp-sociallocker-call").each(function(){
                    onp_create_sociallcoker( $(this) );
                });  
            })
        }
    })(jQuery);
    </script>
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
        window.onpDynamicTheme = '<?php echo $dynamicTheme ?>';
        window.onpDynamicThemeEvent = '<?php echo $dynamicThemeEvent ?>';
    </script>
    <?php
}