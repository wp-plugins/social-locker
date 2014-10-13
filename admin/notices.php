<?php

global $sociallocker;
add_filter('factory_notices_' . $sociallocker->pluginName, 'onp_sl_admin_premium_notices', 10, 2);

function onp_sl_admin_premium_notices( $notices ) {
    global $sociallocker;
    $forceToShowNotices = defined('ONP_DEBUG_SL_OFFER_PREMIUM') && ONP_DEBUG_SL_OFFER_PREMIUM;

    if ( ( !$sociallocker->license || $sociallocker->license->build !== 'free' || $sociallocker->build !== "free" ) && !$forceToShowNotices ) return $notices;
    
        
    $alreadyActivated = get_option('onp_trial_activated_' . $sociallocker->pluginName, false);
    
    if ( $alreadyActivated ) {
        $message = __('3 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more.', 'sociallocker');
        $header = __('Drive more traffic and build quality followers with Social Locker Premium!', 'sociallocker');
        
    } else {
        $message = __('3 extra stunning themes, 7 social buttons, the blurring effect, 8 advanced options, new features & updates every week, dedicated support and more. Drive more traffic and build quality followers with Social Locker Premium!', 'sociallocker');
        $header = __('Try the premium version for 7 days for free!', 'sociallocker');
    }

    $closed = get_option('factory_notices_closed', array());
    
    $lastCloase  = isset( $closed['onp-sl-offer-to-purchase'] ) 
        ? $closed['onp-sl-offer-to-purchase']['time'] 
        : 0;
    
    // shows every 7 days
    if ( ( time() - $lastCloase > 60*60*7 ) || $forceToShowNotices ) {
        
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
                        'title'     => '<i class="fa fa-arrow-circle-o-up"></i> ' . __('Learn More & Upgrade', 'sociallocker'),
                        'class'     => 'button button-primary',
                        'action'    => onp_licensing_324_get_purchase_url( $sociallocker )
                    ),
                    array(
                        'title'     => __('No, thanks, not now', 'sociallocker'),
                        'class'     => 'button',
                        'action'    => 'x'
                    )
                )
            ); 
        
        

    }
    
    return $notices;
}