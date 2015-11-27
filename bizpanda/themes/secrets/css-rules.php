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
                'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: {value|onp_to_rgba};',
                'selector' => '.onp-sl-secrets .onp-sl-flip:hover .onp-sl-overlay-back'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-secrets .onp-sl-strong, .onp-sl-secrets .onp-sl-message, .onp-sl-secrets .onp-sl-timer'
            ),
        ),
        'background_image' => array(
            array(
                'image' => array(
                    'function' => 'opanda_sr_rehue',
                    'args' => array (
                        '{url}',                                                
                        '{color}',                                              
                        array(                                                  
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => array(
                    'background-image: url("{image}");',
                    'background-repeat: repeat;'
                ),
                'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
            ),
            array(
                'css' => 'text-shadow:none;',
                'selector' => '.onp-sl-secrets .onp-sl-strong, .onp-sl-secrets .onp-sl-message, .onp-sl-secrets .onp-sl-timer'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sl-secrets .onp-sl-flip:hover .onp-sl-overlay-back'
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
                'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sl-secrets .onp-sl-flip:hover .onp-sl-overlay-back'
            ),
            array(
                'css' => 'text-shadow:none;',
                'selector' => '.onp-sl-secrets .onp-sl-strong, .onp-sl-secrets .onp-sl-message, .onp-sl-secrets .onp-sl-timer'
            )
        ),
        // end background

        // border
        'outer_border_color' => array(
            'css' => 'border-color: {value|onp_to_rgba};',
            'selector' => '.onp-sl-secrets .onp-sl-outer-wrap'
        ),
        'outer_border_size' => array(
            'css' => 'border-width: {value}px;',
            'selector' => '.onp-sl-secrets .onp-sl-outer-wrap'
        ),
        'inner_border_color' => array(
            'css' => 'border-color: {value|onp_to_rgba};',
            'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
        ),
        'inner_border_size' => array(
            'css' => 'border-width: {value}px;',
            'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
        ),
        'outer_border_radius' => array(
            array(
                'css' => array(
                    'border-radius: {value}px;',
                    '-moz-border-radius:{value}px;',
                    '-webkit-border-radius:{value}px;'
                ),
                'selector' => '.onp-sl-secrets .onp-sl-outer-wrap'
            ),
        ),     
        'inner_border_radius' => array(
            array(
                'css' => array(
                    'border-radius: {value}px;',
                    '-moz-border-radius:{value}px;',
                    '-webkit-border-radius:{value}px;'
                ),
                'selector' => '.onp-sl-secrets .onp-sl-inner-wrap'
            ),
        ),
        
        // end border
        
        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sl-secrets .onp-sl-strong::before, .onp-sl-secrets .onp-sl-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sl-secrets .onp-sl-strong'
            ),
            array(
                'image' => array(
                    'function' => 'opanda_sr_recolor',
                    'args' => array (
                        OPANDA_BIZPANDA_URL . '/assets/img/lock-icon.png',        // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sl-secrets .onp-sl-text .onp-sl-strong:before, .onp-sl-secrets .onp-sl-text .onp-sl-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-secrets, .onp-sl-secrets .onp-sl button, .onp-sl-secrets input, .onp-sl-secrets p'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sl-secrets .onp-sl-social-buttons'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-secrets .onp-sl-text .onp-sl-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-secrets .onp-sl-text'
        ),
        // end paddings 
   
        // background twitter
        'button_twitter_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-header'
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
                'selector' => '.onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-twitter .onp-sl-overlay-header'
            )
        ),
        //end background twitter

        // background facebook
        'button_facebook_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-header'
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
                'selector' => '.onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-facebook .onp-sl-overlay-header'
            )
        ),
        //end background facebook 
  
        // background google
        'button_google_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sl-secrets .onp-sl-google .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-google .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-google .onp-sl-overlay-header'
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
                'selector' => '.onp-sl-secrets .onp-sl-google .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-google .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-google .onp-sl-overlay-header'
            )
        ),
        //end background google 

        // background linkedin
        'button_linkedin_substrate_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-header'
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
                'selector' => '.onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-front, .onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-back'
            ),
            array(
                'css' => 'background: {value|onp_smart_whiteout_color_15};',
                'selector' => '.onp-sl-secrets .onp-sl-linkedin .onp-sl-overlay-header'
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
                'selector' => '.onp-sl-secrets .onp-sl-overlay-text'
            ),
            array(
                'image' => array(
                    'function' => 'opanda_sr_recolor',
                    'args' => array (
                        OPANDA_SR_PLUGIN_URL . '/assets/img/social-icons.png',     // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sl-secrets .onp-sl-button .onp-sl-overlay-icon'                
            )
        )
        // end button text
    );
    
    return $result;
}
