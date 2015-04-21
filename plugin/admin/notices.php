<?php

/**
 * Includes the CSS file for the plugin notices.
 * 
 * @see admin_enqueue_scripts
 * @since 1.0.0
 */
function sociallocker_admin_assets( $hook ) {
    return;
    
    // sytles for the plugin notices
    if ( $hook == 'index.php' || $hook == 'plugins.php' || $hook == 'edit.php' )
        wp_enqueue_style( 'sociallocker-notices', SOCIALLOCKER_URL . '/assets/admin/css/notices.010000.css' ); 
    
}

add_action('admin_enqueue_scripts', 'sociallocker_admin_assets');

// the notices below is only for the free version 

/**
 * Adds the trial and premium notices.
 * 
 * @see factory_notices
 * @since 1.0.0
 */
function sociallocker_premium_notices( $notices ) {
    global $sociallocker;
    $forceToShowNotices = defined('ONP_DEBUG_SL_OFFER_PREMIUM') && ONP_DEBUG_SL_OFFER_PREMIUM;

    if ( ( !$sociallocker->license || $sociallocker->license->build !== 'free' || $sociallocker->build !== "free" ) && !$forceToShowNotices ) return $notices;
      
    $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);

    if ( $alreadyActivated ) {
        $message = __('5 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more.', 'optinpanda');
        $header = __('Drive more traffic and build quality followers with Social Locker Premium!', 'optinpanda');
        $url = onp_licensing_325_get_purchase_url( $sociallocker );
                
    } else {
        $message = __('5 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more. Drive more traffic and build quality followers with Social Locker Premium!', 'optinpanda');
        $header = __('Try the premium version for 7 days for free!', 'optinpanda');
        $url = onp_licensing_325_manager_link($sociallocker->pluginName, 'activateTrial', false);
    }

    $closed = get_option('factory_notices_closed', array());
    
    $lastCloase  = isset( $closed['onp-sl-offer-to-purchase'] ) 
        ? $closed['onp-sl-offer-to-purchase']['time'] 
        : 0;
    
    // shows every 7 days
    if ( ( time() - $lastCloase > 60*60*7 ) || $forceToShowNotices ) {
            
            if ( !$alreadyActivated ) {
            
                $notices[] = array(
                    'id'        => 'onp-sl-offer-to-purchase',

                    'class'     => 'call-to-action ',
                    'icon'      => 'fa fa-arrow-circle-o-up',
                    'header'    => '<span class="onp-hightlight">' . $header . '</span>',
                    'message'   => $message,   
                    'plugin'    => $sociallocker->pluginName,
                    'where'     => array('plugins','dashboard', 'edit'),

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => '<i class="fa fa-arrow-circle-o-up"></i> ' . __('Activate Premium', 'optinpanda'),
                            'class'     => 'button button-primary',
                            'action'    => $url
                        ),
                        array(
                            'title'     => __('No, thanks, not now', 'optinpanda'),
                            'class'     => 'button',
                            'action'    => 'x'
                        )
                    )
                ); 
            
            } else {
                
                $notices[] = array(
                    'id'        => 'onp-sl-offer-to-purchase',

                    'class'     => 'call-to-action ',
                    'icon'      => 'fa fa-arrow-circle-o-up',
                    'header'    => '<span class="onp-hightlight">' . $header . '</span>',
                    'message'   => $message,   
                    'plugin'    => $sociallocker->pluginName,
                    'where'     => array('plugins','dashboard', 'edit'),

                    // buttons and links
                    'buttons'   => array(
                        array(
                            'title'     => '<i class="fa fa-arrow-circle-o-up"></i> ' . __('Learn More & Upgrade', 'optinpanda'),
                            'class'     => 'button button-primary',
                            'action'    => $url
                        ),
                        array(
                            'title'     => __('No, thanks, not now', 'optinpanda'),
                            'class'     => 'button',
                            'action'    => 'x'
                        )
                    )
                ); 
            }
        
        

    }
    
    return $notices;
}

add_filter('factory_notices_' . $sociallocker->pluginName, 'sociallocker_premium_notices', 10, 2);
