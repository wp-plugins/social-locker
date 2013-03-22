<?php
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/common-settings.php');

include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/unlocking-statistics.php');
include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/ajax/tracking.php');

add_action('fy_expired_message_sociallocker-next', 'socaillocker_expired_message', 10);
function socaillocker_expired_message() {
    ?>
        <div class="error onp-alert onp-alert-danger not-visible-in-manager">
            <h4 class="onp-alert-heading">The license key for "Social Locker" has expired.</h4>
            <p>
                Please purchase another key or 
                delete the current key to use the free version of the plugin.
            </p>
            <p style="position: relative; left: -2px;">
                <a href="<?php onepress_fr101_link_license_manager('index') ?>" class="onp-btn onp-btn-danger onp-btn-small">
                    Visit the license manager
                </a>   
            </p>
        </div>
    <?php
}

add_action('fy_change_build_message_sociallocker-next', 'socaillocker_change_build_message', 10);
function socaillocker_change_build_message() {
    
    ?>
        <script>
            jQuery(document).ready(function($){

            });
        </script>
        <div class="error onp-alert onp-alert-danger not-visible-in-manager">
            <h4 class="onp-alert-heading">One small step...</h4>
            <p>
                You changed a license type of the <strong>Social Locker</strong> plugin. But the license you use now
                requries another assembly of the plugin.<br />
                The plugin will not work fully, until you download the proper assembly. All your data will be saved.
           </p>
            <p style="position: relative; left: -2px;">
               <a href="plugins.php" class="onp-btn onp-btn-danger onp-btn-small">Check available updates</a>        
           </p>
        </div>
    <?php
}

add_action('fy_estimate_message_sociallocker-next', 'socaillocker_estimate_message', 10, 1);
function socaillocker_estimate_message( $remained ) {
    if ( $_COOKIE['fy_estimate_message'] ) return;
    ?>
    <?php
    if ( $remained <= 1 ) {
    ?>
        <div class="error onp-alert onp-alert-danger not-visible-in-manager">
            <h4 class="onp-alert-heading">The trial key for "Social Locker" will expire during the day.</h4>
                <p>
                Don't forget to purchase the premium key or 
                delete the trial key to use the free version of the plugin.</p>
                <p style="position: relative; left: -2px;">
                    <a href="<?php onepress_fr101_link_license_manager('index') ?>" class="onp-btn onp-btn-danger onp-btn-small">
                        Visit the license manager
                    </a>
                    <span class="onp-btn onp-btn-small onp-close-alert" data-cookie="estimate_message">or close this message</span>
                </p>
        </div>
    <?php
    } else {
        $remained = round($remained);
        ?>
            <div class="error onp-alert onp-alert-danger not-visible-in-manager">
                <h4 class="onp-alert-heading">The trial key for "Social Locker" will expire in <?php echo $remained ?> day.</h4>
                <p>
                    Don't forget to purchase the premium key or 
                    delete the trial key to use the free version of the plugin.</p>
                <p style="position: relative; left: -2px;">
                    <a href="<?php onepress_fr101_link_license_manager('index') ?>" class="onp-btn onp-btn-danger onp-btn-small">
                        Visit the license manager
                    </a>
                    <span class="onp-btn onp-btn-small onp-close-alert" data-cookie="estimate_message">or close this message</span>
                </p>
            </div>
        <?php  
    }
}

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

