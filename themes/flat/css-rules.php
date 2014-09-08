<?php
/**
 * Returns CSS generating rules for the Flat theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_flat_theme_css_rules() {

    $result = array(  
        
        // background
        'background_color' => array(
            array(
                'css' => 'background-color: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-inner-wrap'   
            ),
            array(
                'css' => 'border-bottom: 3px solid {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-outer-wrap' 
            ),
            array(
                'css' => 'border-top-color: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
            )  
        ),
        'background_image' => array(
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
            ),
            array(
                'image' => array(
                    'function' => 'onp_sl_rehue',
                    'args' => array (
                        '{url}',                                                // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  ONP_SL_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  ONP_SL_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => array(
                    'background-image: url("{image}");',
                    'background-repeat: repeat;'
                ),
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-inner-wrap'          
            )
        ),
        'background_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
            ) 
        ),
        // end background
  
        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sociallocker-flat .onp-sociallocker-strong::before, .onp-sociallocker-flat .onp-sociallocker-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-strong'
            ),
            array(
                'image' => array(
                    'function' => 'onp_sl_recolor',
                    'args' => array (
                        ONP_SL_PLUGIN_URL . '/assets/img/lock-icon.png',      // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  ONP_SL_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  ONP_SL_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-text .onp-sociallocker-strong:before, .onp-sociallocker-flat .onp-sociallocker-text .onp-sociallocker-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-flat .onp-sociallocker-message, .onp-sociallocker-flat .onp-sociallocker-timer'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sociallocker-flat .onp-sociallocker-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sociallocker-flat .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sociallocker-flat .onp-sociallocker-text + .onp-sociallocker-buttons'
        ),
        // end paddings 

        // button backgrounds
        // - twitter
        'button_cover_twitter_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-twitter .onp-sociallocker-overlay-front, .onp-sociallocker-flat .onp-sociallocker-button-twitter .onp-sociallocker-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-twitter .onp-sociallocker-overlay-front' 
            ),
            'button_twitter_substrate_color' => array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-twitter .onp-sociallocker-overlay-header' 
            )
        ),

        // - facebook
        'button_cover_facebook_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-facebook .onp-sociallocker-overlay-front, .onp-sociallocker-flat .onp-sociallocker-button-facebook .onp-sociallocker-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-facebook .onp-sociallocker-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-facebook .onp-sociallocker-overlay-header' 
            )
        ),

        // - google
        'button_cover_google_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-google .onp-sociallocker-overlay-front, .onp-sociallocker-flat .onp-sociallocker-button-google .onp-sociallocker-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-google .onp-sociallocker-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-google .onp-sociallocker-overlay-header' 
            )
        ),

        // - linkedin
        'button_cover_linkedin_color' => array( 
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-front, .onp-sociallocker-flat .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sociallocker-flat .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-header' 
            )
        ),   
        // end button backgrounds

        // button text
        'button_cover_text_font' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-overlay-text'
            ),
            array(
                'image' => array(
                    'function' => 'onp_sl_recolor',
                    'args' => array (
                        ONP_SL_PLUGIN_URL . '/assets/img/social-icons.png',     // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  ONP_SL_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  ONP_SL_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sociallocker-flat .onp-sociallocker-button .onp-sociallocker-overlay-icon'                
            )
        )
        // end button text
    );
    
    return $result;
}
