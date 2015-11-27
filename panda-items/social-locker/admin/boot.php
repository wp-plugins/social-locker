<?php
/**
 * Boots the code for the admin part of the Social Locker
 * 
 * @since 1.0.0
 * @package core
 */

/**
 * Registers metaboxes for Social Locker.
 * 
 * @see opanda_item_type_metaboxes
 * @since 1.0.0
 */
function opanda_socail_locker_metaboxes( $metaboxes ) {
   
    $metaboxes[] = array(
        'class' => 'OPanda_SocialOptionsMetaBox',
        'path' => BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/metaboxes/social-options.php'
    );
    
    if ( OPanda_Items::isCurrentFree() ) {
        
        $metaboxes[] = array(
            'class' => 'OPanda_SocialLockerMoreFeaturesMetaBox',
            'path' => BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/metaboxes/more-features.php'
        );   
    }
    
    return $metaboxes;
}

add_filter('opanda_social-locker_type_metaboxes', 'opanda_socail_locker_metaboxes', 10, 1);


/**
 * Prepares the Social Locker to use while activation.
 * 
 * @since 1.0.0
 */
function opanda_social_locker_activation( $plugin, $helper ) {
    
    // imports the old social lockers
    
    global $wpdb;

    $sociallockerIDs = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type='social-locker'" );
    if ( !empty( $sociallockerIDs) ) {

        // Converts the old Social Lockers to the Opt-In Panda Items of the type 'Social Locker'

        foreach( $sociallockerIDs as $postID ) {
                $wpdb->query("INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ($postID, 'opanda_item', 'social-locker')");  
        }
    }

    $wpdb->query("UPDATE {$wpdb->posts} SET post_type='" . OPANDA_POST_TYPE . "' WHERE post_type='social-locker'");
    $wpdb->query("UPDATE {$wpdb->posts} SET post_title='" . __('Social Locker', 'optionpanda') . "', post_name='opanda_default_social_locker' WHERE post_name='default_sociallocker_locker'");
        $defaulTheme = 'secrets';
    

    
    // default social locker
    $helper->addPost(
        'opanda_default_social_locker_id',
        array(
            'post_type' => OPANDA_POST_TYPE,
            'post_title' => __('Social Locker (default)', 'sociallocker'),
            'post_name' => 'opanda_default_social_locker'
        ),
        array(
            'opanda_item' => 'social-locker',
            'opanda_header' => __('This content is locked', 'sociallocker'),       
            'opanda_message' => __('Please support us, use one of the buttons below to unlock the content.', 'sociallocker'),
            'opanda_style' => $defaulTheme,
            'opanda_mobile' => 1,          
            'opanda_highlight' => 1,                   
            'opanda_is_system' => 1,
            'opanda_is_default' => 1
        )
    );
}

add_action('after_bizpanda_activation', 'opanda_social_locker_activation', 10, 2);


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
function opanda_register_social_locker_themes() {
        
        OPanda_ThemeManager::registerTheme(array(
            'name' => 'starter',
            'title' => 'Starter',
            'path' => OPANDA_BIZPANDA_DIR . '/themes/starter',
            'items' => array('social-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'secrets',
            'title' => 'Secrets',
            'path' => OPANDA_BIZPANDA_DIR . '/themes/secrets',
            'items' => array('social-locker') 
        )); 

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'dandyish',
            'title' => 'Dandyish [Premium]',
            'preview' => 'https://cconp.s3.amazonaws.com/bizpanda/social-locker/preview/dansyish.png',
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'sociallocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('social-locker') 
        )); 

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'glass',
            'title' => 'Glass [Premium]',
            'preview' => 'https://cconp.s3.amazonaws.com/bizpanda/social-locker/preview/glass.png',
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'sociallocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('social-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'flat',
            'title' => 'Flat [Premium]',
            'preview' => 'https://cconp.s3.amazonaws.com/bizpanda/social-locker/preview/flat.png',
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'sociallocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('social-locker') 
        ));
        
    

}

add_action('onp_sl_register_themes', 'opanda_register_social_locker_themes');

/**
 * Shows the help page 'What is it?' for the Social Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_usage_what_is_social_locker( $manager ) {
    require BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/help/what-is-it.php';
}

add_action('opanda_help_page_what-is-social-locker', 'opanda_help_page_usage_what_is_social_locker');


/**
 * Shows the help page 'Usage Example' for the Social Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_usage_example_social_locker( $manager ) {
    require BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/help/usage-example.php';
}

add_action('opanda_help_page_usage-example-social-locker', 'opanda_help_page_usage_example_social_locker');


/**
 * Shows the help page 'Other Notes' for the Social Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_other_notes_social_locker( $manager ) {
    require BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/help/other-notes.php';
}

add_action('opanda_help_page_other-notes-social-locker', 'opanda_help_page_other_notes_social_locker');


/**
 * Registers the quick tags for the wp editors.
 * 
 * @see admin_print_footer_scripts
 * @since 1.0.0
 */
function opanda_quicktags_for_social_locker()
{ ?>
    <script type="text/javascript">
        (function(){
            if (!window.QTags) return;
            window.QTags.addButton( 'sociallocker', 'sociallocker', '[sociallocker]', '[/sociallocker]' );
        }());
    </script>
<?php 
}

add_action('admin_print_footer_scripts',  'opanda_quicktags_for_social_locker');

/**
 * Registers stats screens for Email Locker.
 * 
 * @since 1.0.0
 */
function opanda_social_locker_stats_screens( $screens ) { 
    global $optinpanda;
    
    $screens = array(
        
        // The Summary Screen
        
        'summary' => array (
            'title' => __('<i class="fa fa-search"></i> Summary', 'sociallocker'),
            'description' => __('The page shows the total number of unlocks for the current locker.', 'sociallocker'),

            'chartClass' => 'OPanda_SocialLocker_Summary_StatsChart',
            'tableClass' => 'OPanda_SocialLocker_Summary_StatsTable',
            'path' => BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/stats/summary.php'
        ),
        
        // The Channels Screen        
  
        'channels' => array(
            'title' => __('<i class="fa fa-search-plus"></i> Detailed', 'sociallocker'), 
            'description' => __('The page shows which ways visitors used to unlock the content.', 'sociallocker'),
            
            'chartClass' => 'OPanda_SocialLocker_Detailed_StatsChart',
            'tableClass' => 'OPanda_SocialLocker_Detailed_StatsTable',
            'path' => BIZPANDA_SOCIAL_LOCKER_DIR . '/admin/stats/detailed.php' 
        )
    );
    
    return $screens;
}

add_filter('opanda_social-locker_stats_screens', 'opanda_social_locker_stats_screens', 10, 1);