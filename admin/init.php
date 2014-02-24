<?php

// actiovation
include_once(ONP_SL_PLUGIN_DIR . '/admin/activation.php');

// metaboxes
include_once(ONP_SL_PLUGIN_DIR . '/includes/metaboxes/basic-options.php');
include_once(ONP_SL_PLUGIN_DIR . '/includes/metaboxes/preview.php');
include_once(ONP_SL_PLUGIN_DIR . '/includes/metaboxes/manual-locking.php');
include_once(ONP_SL_PLUGIN_DIR . '/includes/metaboxes/bulk-locking.php');
    include_once(ONP_SL_PLUGIN_DIR . '/includes/metaboxes/more-features.php');



// view tables
include_once(ONP_SL_PLUGIN_DIR . '/includes/viewtables/locker-viewtable.class.php');

// pages and ajax calls
include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/common-settings.php');
include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/statistics.php');
include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/preview.php');
include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/how-to-use.php');
    include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/license-manager.php');



include_once(ONP_SL_PLUGIN_DIR . '/admin/ajax/tracking.php');
include_once(ONP_SL_PLUGIN_DIR . '/admin/ajax/shortcode.php');


/**
 * Adds scripts and styles in the admin area.
 */
function sociallocker_admin_assets( $hook ) {
    
    if ( $hook == 'index.php' || $hook == 'plugins.php' )
        wp_enqueue_style( 'onp-sl-notices', ONP_SL_PLUGIN_URL . '/assets/admin/css/notices.030100.css' ); 
}
add_action('admin_enqueue_scripts', 'sociallocker_admin_assets');

add_filter('mce_buttons', 'sociallocker_register_button'); 
add_filter('mce_external_plugins', 'sociallocker_add_plugin'); 

function sociallocker_register_button($buttons) {  
   if ( !current_user_can('edit_social-locker') ) return $buttons;
   array_push($buttons, "sociallocker");
   return $buttons;  
}  

function sociallocker_add_plugin($plugin_array) {  
   if ( !current_user_can('edit_social-locker') ) return $plugin_array;
   $plugin_array['sociallocker'] = ONP_SL_PLUGIN_URL . '/assets/admin/js/sociallocker.tinymce.js';  
   return $plugin_array;  
}  
    
add_action('wp_ajax_get_sociallocker_lockers', 'sociallocker_get_lockers');
function sociallocker_get_lockers() {

    $lockers = get_posts(array('post_type' => 'social-locker'));

    $result = array();
    foreach($lockers as $locker)
    {
        $result[] = array(
            'title' => empty( $locker->post_title ) ? 'No title [' . $locker->ID . ']' : $locker->post_title,
            'id' => $locker->ID,
            'defaultType' => get_post_meta( $locker->ID, 'sociallocker_is_default', true )
        );
    }

    echo json_encode($result);
    die();
}

add_action('admin_print_footer_scripts',  'sociallocker_quicktags');
function sociallocker_quicktags()
{ ?>
    <script type="text/javascript">
        (function(){
            if (!window.QTags) return;
            window.QTags.addButton( 'sociallocker', 'sociallocker', '[sociallocker]', '[/sociallocker]' );
        }());
    </script>
<?php 
}

/**
 * Returns an URL where we should redirect a user after success activation of the plugin.
 * 
 * @since 3.1.0
 * @return string
 */
function onp_sl_license_manager_success_button() {
    return 'Learn how to use the plugin <i class="fa fa-lightbulb-o"></i>';
}
add_action('onp_license_manager_success_button_sociallocker-next', 'onp_sl_license_manager_success_button');

/**
 * Returns an URL where we should redirect a user after success activation of the plugin.
 * 
 * @since 3.1.0
 * @return string
 */
function onp_sl_license_manager_success_redirect() {
    $args = array(
        'fy_page'      => 'how-to-use',
        'fy_action'    => 'index',  
        'fy_plugin'    => 'sociallocker-next'
    );
    
    return '?' . http_build_query( $args );
}
add_action('onp_license_manager_success_redirect_sociallocker-next',  'onp_sl_license_manager_success_redirect');