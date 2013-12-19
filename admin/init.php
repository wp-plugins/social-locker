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
    include_once(ONP_SL_PLUGIN_DIR . '/admin/pages/license-manager.php');



include_once(ONP_SL_PLUGIN_DIR . '/admin/ajax/tracking.php');
include_once(ONP_SL_PLUGIN_DIR . '/admin/ajax/shortcode.php');


/**
 * Adds scripts and styles in the admin area.
 */
function sociallocker_admin_assets() {
    global $sociallocker;
    
    if ( $sociallocker->license && !$sociallocker->license->hasKey() ) {     
        if ( $sociallocker->license->default['Build'] == 'premium' ) {
        ?>
        <style>
            .factory-notices-300-notice.sociallocker-next.factory-offer {  
                background: #e9ebee url("<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/offer-background-color.png' ?>");
                color: #262729;
                -webkit-text-shadow: none;
                -moz-text-shadow: none;
                text-shadow: none;
                border-color: #bbbbbb;
                padding: 10px;
            }
            .factory-notices-300-notice.sociallocker-next.factory-offer .factory-message-container a {
                color: #262729;
            }
            .factory-notices-300-notice.sociallocker-next.factory-offer .factory-button-primary {
                background: #5672ad;
                border: 1px solid #2e3847;
                color: #fff;
            }
            .factory-notices-300-notice.sociallocker-next.factory-offer .highlighted {
                background-color: rgba(0,0,0,0.05);
            }
            .factory-notices-300-notice.sociallocker-next.factory-offer .factory-inner-wrap {
                padding-left: 90px !important;
                background: url("<?php echo ONP_SL_PLUGIN_URL . '/assets/admin/img/offer-logo.png' ?>") 5px center no-repeat;
            }
            .factory-notices-300-notice.sociallocker-next.factory-offer .factory-buttons {
                bottom: 15px;
            }
        </style>
        <?php
        }
    }
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

