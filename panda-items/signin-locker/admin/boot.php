<?php
/**
 * Boots the code for the admin part of the Sign-In Locker
 * 
 * @since 1.0.0
 * @package core
 */

/**
 * Registers metaboxes for Sign-In Locker.
 * 
 * @see opanda_item_type_metaboxes
 * @since 1.0.0
 */
function opanda_signin_locker_metaboxes( $metaboxes ) {
   
    $metaboxes[] = array(
        'class' => 'OPanda_ConnectOptionsMetaBox',
        'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/metaboxes/connect-options.php'
    );

    $metaboxes[] = array(
        'class' => 'OPanda_TermsPrivacyMetaBox',
        'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/terms-privacy.php'
    );
      
        $metaboxes[] = array(
            'class' => 'OPanda_SigninLockerMoreFeaturesMetaBox',
            'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/metaboxes/more-features.php'
        );  
    


    return $metaboxes;
}

add_filter('opanda_signin-locker_type_metaboxes', 'opanda_signin_locker_metaboxes', 10, 1);

/**
 * Prepares the Sign-In Locker to use while activation.
 * 
 * @since 1.0.0
 */
function opanda_signin_locker_activation( $plugin, $helper ) {
    
    // default email locker

    $helper->addPost(
        'opanda_default_signin_locker_id',
        array(
            'post_type' => OPANDA_POST_TYPE,
            'post_title' => __('Sign-In Locker (default)', 'signinlocker'),
            'post_name' => 'opanda_default_signin_locker'
        ),
        array(
            'opanda_item' => 'signin-locker',
            'opanda_header' => __('Sign In To Unlock This Content', 'signinlocker'),       
            'opanda_message' => __('Please sign in. It\'s free. Just click one of the buttons below to get instant access.', 'signinlocker'),
            'opanda_style' => 'great-attractor',
            'opanda_catch_leads' => 1,
            'opanda_connect_buttons' => 'facebook,twitter,google',
            'opanda_facebook_actions' => BizPanda::hasPlugin('optinpanda') ? 'signup' : 'signup',
            'opanda_twitter_actions' => BizPanda::hasPlugin('optinpanda') ? 'signup' : 'signup',
            'opanda_google_actions' => BizPanda::hasPlugin('optinpanda') ? 'signup' : 'signup',
            'opanda_linkedin_actions' => BizPanda::hasPlugin('optinpanda') ? 'signup' : 'signup',
            'opanda_email_actions' => BizPanda::hasPlugin('optinpanda') ? 'signup' : 'signup',
            'opanda_mobile' => 1,          
            'opanda_highlight' => 1,                   
            'opanda_is_system' => 1,
            'opanda_is_default' => 1
        )
    );
}

add_action('after_bizpanda_activation', 'opanda_signin_locker_activation', 10, 2);


/**
 * Registers default themes.
 * 
 * We don't need to include the file containing the file OPanda_ThemeManager because this function will
 * be called from the hook defined inside the class OPanda_ThemeManager.
 * 
 * @see onp_sl_register_themes
 * @see OPanda_ThemeManager
 * 
 * @since 1.0.0
 * @return void 
 */
function opanda_register_signin_locker_themes( $item = 'signin-locker' ) {
        
        OPanda_ThemeManager::registerTheme(array(
            'name' => 'great-attractor',
            'title' => 'Great Attractor',
            'path' => OPANDA_BIZPANDA_DIR . '/themes/great-attractor',
            'items' => array('signin-locker', 'email-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'friendly-giant',
            'title' => 'Friendly Giant [Premium]',
            'preview' => 'https://cconp.s3.amazonaws.com/bizpanda/signin-locker/preview/friendly-giant.png',
            'previewHeight' => 400,   
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'signinlocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('signin-locker', 'email-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'dark-force',
            'title' => 'Dark Force [Premium]',
            'preview' => 'https://cconp.s3.amazonaws.com/bizpanda/signin-locker/preview/dark-force.png',
            'previewHeight' => 400,
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'signinlocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('signin-locker', 'email-locker') 
        ));
    

}

add_action('onp_sl_register_themes', 'opanda_register_signin_locker_themes');


/**
 * Shows the help page 'What it it?' for the Sign-In Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_what_is_signin_locker( $manager ) {
    require BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/help/what-is-it.php';
}

add_action('opanda_help_page_what-is-signin-locker', 'opanda_help_page_what_is_signin_locker');


/**
 * Shows the help page 'Usage Example' for the Sign-In Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_usage_example_signin_locker( $manager ) {
    require BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/help/usage-example.php';
}

add_action('opanda_help_page_usage-example-signin-locker', 'opanda_help_page_usage_example_signin_locker');


/**
 * Registers the quick tags for the wp editors.
 * 
 * @see admin_print_footer_scripts
 * @since 1.0.0
 */
function opanda_quicktags_for_signin_locker()
{ ?>
    <script type="text/javascript">
        (function(){
            if (!window.QTags) return;
            window.QTags.addButton( 'signinlocker', 'signinlocker', '[signinlocker]', '[/signinlocker]' );
        }());
    </script>
<?php 
}

add_action('admin_print_footer_scripts',  'opanda_quicktags_for_signin_locker');

/**
 * Registers stats screens for Sign-In.
 * 
 * @since 1.0.0
 */
function opanda_signin_locker_stats_screens( $screens ) { 

    $screens = array(
        
        // The Summary Screen
        
        'summary' => array (
            'title' => __('<i class="fa fa-search"></i> Summary', 'signinlocker'),
            'description' => __('The page shows the total number of unlocks for the current locker.', 'signinlocker'),

            'chartClass' => 'OPanda_SignInLocker_Summary_StatsChart',
            'tableClass' => 'OPanda_SignInLocker_Summary_StatsTable',
            'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/stats/summary.php'
        ),
        
        // The Profits Screen
        
        'profits' => array(
            'title' => __('<i class="fa fa-usd"></i> Benefits', 'signinlocker'), 
            'description' => __('The page shows benefits the locker brought for your website.', 'signinlocker'),

            'chartClass' => 'OPanda_SignInLocker_Profits_StatsChart',
            'tableClass' => 'OPanda_SignInLocker_Profits_StatsTable',
            'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/stats/profits.php'
        ),   

        // The Channels Screen        
  
        'channels' => array(
            'title' => __('<i class="fa fa-search-plus"></i> Channels', 'signinlocker'), 
            'description' => __('The page shows which ways visitors used to unlock the content.', 'signinlocker'),
            
            'chartClass' => 'OPanda_SignInLocker_Channels_StatsChart',
            'tableClass' => 'OPanda_SignInLocker_Channels_StatsTable',
            'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/stats/channels.php' 
        ),
        
        // The Bounces Screen        
  
        'bounces' => array(
            'title' => __('<i class="fa fa-sign-out"></i> Bounces', 'signinlocker'), 
            'description' => __('The page shows major weaknesses of the locker which lead to bounces. Hover your mouse pointer on [?] in the table, to know more about a particular metric.', 'signinlocker'),
            
            'chartClass' => 'OPanda_SignInLocker_Bounces_StatsChart',
            'tableClass' => 'OPanda_SignInLocker_Bounces_StatsTable',
            'path' => BIZPANDA_SIGNIN_LOCKER_DIR . '/admin/stats/bounces.php' 
        )
    );
    
    return $screens;
}

add_filter('opanda_signin-locker_stats_screens', 'opanda_signin_locker_stats_screens', 10, 1);

