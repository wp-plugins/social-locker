<?php

// actiovation
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/activation.php');

// metaboxes
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/metaboxes/sociallocker-basic-options.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/metaboxes/sociallocker-support.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/metaboxes/socialocker-preview.php');
    include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/metaboxes/sociallocker-function-options.php');
    include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/metaboxes/sociallocker-social-options.php');



// view tables
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/viewtables/locker-viewtable.class.php');

// pages and ajax calls
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/common-settings.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/unlocking-statistics.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/license-manager.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/ajax/tracking.php');

/**
 * Adds scripts and styles in the admin area.
 */
function sociallocker_admin_assets() {
    global $socialLocker;
    
    if ( $socialLocker->license && !$socialLocker->license->hasKey() ) {     
        if ( $socialLocker->license->default['Build'] == 'premium' ) {
        ?>
        <style>
            .onp-notice.sociallocker-next.onp-offer {  
                background: #e9ebee url("<?php echo SOCIALLOCKER_PLUGIN_URL . '/assets/admin/img/offer-background-color.png' ?>");
                color: #262729;
                -webkit-text-shadow: none;
                -moz-text-shadow: none;
                text-shadow: none;
                border-color: #bbbbbb;
                padding: 10px;
            }
            .onp-notice.sociallocker-next.onp-offer .onp-message-container a {
                color: #262729;
            }
            .onp-notice.sociallocker-next.onp-offer .onp-notice-button-primary {
                background: #5672ad;
                border: 1px solid #2e3847;
                color: #fff;
            }
            .onp-notice.sociallocker-next.onp-offer .highlighted {
                background-color: rgba(0,0,0,0.05);
            }

            .onp-notice.sociallocker-next .onp-notice-inner-wrap {
                padding-left: 90px !important;
                background: url("<?php echo SOCIALLOCKER_PLUGIN_URL . '/assets/admin/img/offer-logo.png' ?>") 5px 5px no-repeat;
            }
            .onp-notice.sociallocker-next.onp-offer .onp-notice-buttons {
                bottom: 15px;
            }
        </style>
        <?php
        }
    }
}
add_action('admin_enqueue_scripts', 'sociallocker_admin_assets');

add_action('admin_menu', 'sociallocker_admin_menu');
function sociallocker_admin_menu() {
    if ( current_user_can('edit_social-locker') ) {
    
        add_submenu_page( 
            'edit.php?post_type=social-locker', 
            'Settings', 
            'Common Settings', 
            'manage_options', 
            'sociallocker_settings', 
            'sociallocker_settings' );


        add_submenu_page( 
            'edit.php?post_type=social-locker', 
            'Settings', 
            'Usage Statistics', 
            'manage_options', 
            'sociallocker_statistics', 
            'sociallocker_statistics' );  

    }  
}
// This condition is responsible for the license and features that it provides.
// Sure you can change it and get all features for free. Do it if you want.
// But please remember, we could encrypt this sources to protect it.
// But we entrusted you, the man who use our plugins, and we hope you will enjoy it.
// Thank you! Yours faithfully, OnePress.
// < condition start
global $socialLocker;
if ( in_array( $socialLocker->license->type, array( 'free' ) ) ) {

        return;
    
}
// condition end >




add_filter('mce_buttons', 'sociallocker_register_button'); 
add_filter('mce_external_plugins', 'sociallocker_add_plugin'); 

function sociallocker_register_button($buttons) {  
   array_push($buttons, "sociallocker");
   return $buttons;  
}  

function sociallocker_add_plugin($plugin_array) {  
   $plugin_array['sociallocker'] = SOCIALLOCKER_PLUGIN_URL . '/assets/admin/js/sociallocker.tinymce.js';  
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

