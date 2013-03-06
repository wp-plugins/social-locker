<?php


include_once(SOCIALLOCKER_PLUGIN_ROOT . '/admin/pages/common-settings.php');

add_action('fy_expired_message_sociallocker-next', 'socaillocker_expired_message', 10);
function socaillocker_expired_message() {
    ?>
        <div class="error not-visible-in-manager" style="padding: 10px;">
            <strong>The license key for "Social Locker" has expired.</strong>
            <p style="margin: 5px 0 0 0;">
                Please purchase another key or 
                delete the current key to use the free version of the plugin.</p>
            <p style="margin: 0;">Visit the <a href="<?php onepress_fr100_link_license_manager('index') ?>">license manager</a>.</p>
        </div>
    <?php
}

add_action('fy_estimate_message_sociallocker-next', 'socaillocker_estimate_message', 10, 1);
function socaillocker_estimate_message( $remained ) {
    if ( $_COOKIE['fy_estimate_message'] ) return;
    ?>
    <style>
        #close-estimate-message {
            display: inline-block;
            border-bottom: 1px dotted #c00;
            color: #c00;
            cursor: pointer;
            line-height: 12px;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
            $("#close-estimate-message").click(function(){
                var exdate=new Date();
                exdate.setDate(exdate.getDate() + 1);
                document.cookie = "fy_estimate_message=1; expires="+exdate.toUTCString();
                $(this).parents('.error').fadeOut();
            });
        });
    </script>
    <?php
    if ( $remained < 1 ) {
    ?>
        <div class="error not-visible-in-manager" style="padding: 10px;">
            <strong>The trial key for "Social Locker" will expire during the day.</strong>
                <p style="margin: 5px 0 0 0;">
                Don't forget to purchase the premium key or 
                delete the trial key to use the free version of the plugin.</p>
                <p style="margin: 0;">Visit the <a href="<?php onepress_fr100_link_license_manager('index') ?>">license manager</a> or just <span id="close-estimate-message">close this message</span>.</p>
        </div>
    <?php
    } else {
        $remained = round($remained);
        ?>
            <div class="error not-visible-in-manager" style="padding: 10px;">
                <strong>The trial key for "Social Locker" will expire in <?php echo $remained ?> day(s).</strong>
                <p style="margin: 5px 0 0 0;">
                    Don't forget to purchase the premium key or 
                    delete the trial key to use the free version of the plugin.</p>
                <p style="margin: 0;">Visit the <a href="<?php onepress_fr100_link_license_manager('index') ?>">license manager</a> or just <span id="close-estimate-message">close this message</span>.</p>
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

