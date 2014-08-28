<?php
/**
 * Returns CSS generating rules for the Secrets theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_secrets_theme_css_rules() {

    $result = array(
        
        // background 
        'background_color' => array(
            array(
                'css' => 'background-color: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-strong, .onp-sociallocker-secrets .onp-sociallocker-message, .onp-sociallocker-secrets .onp-sociallocker-timer'
            ),
        ),
        'background_image' => array(
            array(
                'image' => array(
                    'function' => 'onp_sl_rehue',
                    'args' => array (
                        '{url}',                                                
                        '{color}',                                              
                        array(                                                  
                            'output' =>  ONP_SL_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  ONP_SL_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => array(
                    'background-image: url("{image}");',
                    'background-repeat: repeat;'
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
            ),
            array(
                'css' => 'text-shadow:none;',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-strong, .onp-sociallocker-secrets .onp-sociallocker-message, .onp-sociallocker-secrets .onp-sociallocker-timer'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
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
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-flip:hover .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'text-shadow:none;',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-strong, .onp-sociallocker-secrets .onp-sociallocker-message, .onp-sociallocker-secrets .onp-sociallocker-timer'
            )
        ),
        // end background

        // border
        'outer_border_color' => array(
            'css' => 'border-color: {value|onp_to_rgba};',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-outer-wrap'
        ),
        'outer_border_size' => array(
            'css' => 'border-width: {value}px;',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-outer-wrap'
        ),
        'inner_border_color' => array(
            'css' => 'border-color: {value|onp_to_rgba};',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
        ),
        'inner_border_size' => array(
            'css' => 'border-width: {value}px;',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
        ),
        'outer_border_radius' => array(
            array(
                'css' => array(
                    'border-radius: {value}px;',
                    '-moz-border-radius:{value}px;',
                    '-webkit-border-radius:{value}px;'
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-outer-wrap'
            ),
        ),     
        'inner_border_radius' => array(
            array(
                'css' => array(
                    'border-radius: {value}px;',
                    '-moz-border-radius:{value}px;',
                    '-webkit-border-radius:{value}px;'
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
            ),
        ),
        
        // end border
        
        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-strong::before, .onp-sociallocker-secrets .onp-sociallocker-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-strong'
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
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-text .onp-sociallocker-strong:before, .onp-sociallocker-secrets .onp-sociallocker-text .onp-sociallocker-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-message, .onp-sociallocker-flat .onp-sociallocker-timer'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sociallocker-secrets .onp-sociallocker-text + .onp-sociallocker-buttons'
        ),
        // end paddings 
   
        // background twitter
        'button_twitter_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-header'
            )
        ),
        'button_twitter_substrate_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-twitter .onp-sociallocker-overlay-header'
            )
        ),
        //end background twitter

        // background facebook
        'button_facebook_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-header'
            )
        ),
        'button_facebook_substrate_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-facebook .onp-sociallocker-overlay-header'
            )
        ),
        //end background facebook 
  
        // background google
        'button_google_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-header'
            )
        ),
        'button_google_substrate_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-google .onp-sociallocker-overlay-header'
            )
        ),
        //end background google 

        // background linkedin
        'button_linkedin_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-header'
            )
        ),
        'button_linkedin_substrate_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-front, .onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_blackout_color};',
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button-linkedin .onp-sociallocker-overlay-header'
            )
        ),
        //end background linkedin  
         
        // button text
        'button_cover_text_font' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-overlay-text'
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
                'selector' => '.onp-sociallocker-secrets .onp-sociallocker-button .onp-sociallocker-overlay-icon'                
            )
        )
        // end button text
    );
    
    return $result;
}
