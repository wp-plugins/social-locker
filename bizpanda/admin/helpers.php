<?php

/**
 * Returns an URL for purchasing a premium version of the plugin.
 * 
 * @since 1.1.4
 * 
 * @param string $name plugin or item name.
 * @return string|false the URL to purchase
 */
function opanda_get_premium_url( $name = null, $campaign = 'na' ) {
    if ( empty( $name ) ) $name = OPanda_Items::getCurrentItemName ();
    
    $url = null;
    $url = apply_filters('opanda_premium_url', $url, $name, $campaign );
    if ( !empty( $url ) ) return $url;
    
    $url = OPanda_Items::getPremiumUrl( $name );
    if ( !empty( $url ) ) return $url;
    
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
    
    $url = OPanda_Plugins::getPremiumUrl( $name );
    if ( !empty( $url ) ) return $url;
    
    return OPanda_Items::getPremiumUrl( $name );
}

/**
 * Returns HTML offering to go premium. 
 * 
 * @since 1.1.4
 */
function opanda_get_premium_note( $wrap = true, $campaign = 'na' ) {
    
    $url = opanda_get_premium_url( null, $campaign );
    $content = '';
    
    if ( $wrap ) {
        $content .= '<div class="factory-fontawesome-320 opanda-overlay-note opanda-premium-note">';
    }

    $content .= sprintf( __( '<i class="fa fa-star-o"></i> <strong>Go Premium</strong> <i class="fa fa-star-o"></i><br />To Unlock These Features<br /><a href="%s" class="opnada-button" target="_blank">Learn More</a>', 'bizpanda' ), $url );
    
    if ( $wrap ) {
        $content .= '</div>';
    }

    return $content;
}