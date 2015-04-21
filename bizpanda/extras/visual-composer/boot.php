<?php

function opanda_register_vc_elements() {
    if ( !class_exists("WPBakeryShortCodesContainer") ) return;
    
    class WPBakeryShortCode_sociallocker extends WPBakeryShortCodesContainer {}
    class WPBakeryShortCode_emaillocker extends WPBakeryShortCodesContainer {} 
    class WPBakeryShortCode_signinlocker extends WPBakeryShortCodesContainer {}
    
    $options = array(
        "content_element" => true,
        "show_settings_on_create" => true,
        "as_parent" => array('except' => 'nothing_or_something'),
        "params" => array(
            array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __("Select locker to insert", "bizpanda"),
                "param_name" => "id",
                "description" => ""                                            
           )
        ), 
        "js_view" => 'VcColumnView'
    );

    if ( BizPanda::hasPlugin('sociallocker') ) {
        
        $options['category'] = BizPanda::isSinglePlugin() ? __('Social Locker', 'bizpanda') :  __('BizPanda', 'bizpanda');
        $options['icon'] = BizPanda::isSinglePlugin() ? 'opanda-social-locker-vc-icon' : 'opanda-bizpanda-vc-icon'; 
        $options['name'] = __('Social Locker', 'bizpanda'); 
        $options['base'] = 'sociallocker'; 
        $options['description'] = __('Adds one of existing Social Lockers.', 'bizpanda'); 

        $lockers = get_transient('opanda_vc_social_lockers');
        if ( false === $lockers ) {
            $lockers = opanda_get_lockers('social-locker', 'vc');
            set_transient ('opanda_vc_social_lockers', $lockers, 60 * 60 * 24);
        }
        
        $options['params'][0]['value'] = $lockers; 
        vc_map( $options ); 
    }
    
    if ( BizPanda::hasPlugin('optinpanda') ) {
        
        $options['category'] = BizPanda::isSinglePlugin() ? __('Opt-In Panda', 'bizpanda') :  __('BizPanda', 'bizpanda');
        $options['icon'] = 'opanda-bizpanda-vc-icon'; 
        $options['name'] = __('Email Locker', 'bizpanda'); 
        $options['base'] = 'emaillocker'; 
        $options['description'] = __('Adds one of existing Email Lockers.', 'bizpanda'); 

        $lockers = get_transient('opanda_vc_email_lockers');
        if ( false === $lockers ) {
            $lockers = opanda_get_lockers('email-locker', 'vc');
            set_transient ('opanda_vc_email_lockers', $lockers, 60 * 60 * 24);
        }
        
        $options['params'][0]['value'] = $lockers;  
        vc_map( $options );  
    }
    
    if ( BizPanda::isSinglePlugin() && BizPanda::hasPlugin('sociallocker') ) {
        $options['category'] = __('Social Locker', 'bizpanda');   
        $options['icon'] = 'opanda-social-locker-vc-icon'; 
    } elseif ( BizPanda::isSinglePlugin() && BizPanda::hasPlugin('optinpanda') ) {
        $options['category'] = __('Opt-In Panda', 'bizpanda');  
        $options['icon'] = 'opanda-bizpanda-vc-icon'; 
    } else {
        $options['category'] = __('BizPanda', 'bizpanda');   
        $options['icon'] = 'opanda-bizpanda-vc-icon'; 
    }

    $options['name'] = __('Sign-In Locker', 'bizpanda'); 
    $options['base'] = 'signinlocker'; 
    $options['description'] = __('Adds one of existing Sign-In Lockers.', 'bizpanda'); 
    
    $lockers = get_transient('opanda_vc_signin_lockers');
    if ( false === $lockers ) {
        $lockers = opanda_get_lockers('signin-locker', 'vc');
        set_transient ('opanda_vc_signin_lockers', $lockers, 60 * 60 * 24);
    }
        
    $options['params'][0]['value'] = $lockers;  
    vc_map( $options );
}

add_action('vc_before_init', 'opanda_register_vc_elements');

function opanda_vc_elements_css( $hook ) {
    if ( !class_exists("WPBakeryShortCodesContainer") ) return;
    if( $hook !== "post.php" && $hook !== "edit.php" && $hook !== "post-new.php" ) return;

    delete_transient('opanda_vc_social_lockers');
    delete_transient('opanda_vc_email_lockers');
    delete_transient('opanda_vc_signin_lockers');
    
    wp_enqueue_style( 'opdanda-vc-elements-css',  OPANDA_BIZPANDA_URL . "/extras/visual-composer/assets/style.css" );
}
add_action('admin_enqueue_scripts', 'opanda_vc_elements_css');