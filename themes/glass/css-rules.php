<?php
/**
 * Returns CSS generating rules for the Glass theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_glass_theme_css_rules() {

    return array( 
        
        // background 
        'background_color' => array(
            array(
                'css' => 'background: {value|onp_to_rgba};',
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-strong, .onp-sociallocker-glass .onp-sociallocker-message, .onp-sociallocker-glass .onp-sociallocker-timer'
            )
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
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-strong, .onp-sociallocker-glass .onp-sociallocker-message, .onp-sociallocker-glass .onp-sociallocker-timer'
            ),
        ),
        'background_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-strong, .onp-sociallocker-glass .onp-sociallocker-message, .onp-sociallocker-glass .onp-sociallocker-timer'
            )
        ),
        // end background
        
        // border
        'border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sociallocker-glass'
        ),     
        'border_image' => array(
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
            'selector' => '.onp-sociallocker-glass'
        ),
        'border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sociallocker-glass'
        ),
        'border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sociallocker-glass'
        ),
        'border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sociallocker-glass, .onp-sociallocker-glass .onp-sociallocker-outer-wrap'
        ),
        // end border

        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sociallocker-glass .onp-sociallocker-strong::before, .onp-sociallocker-glass .onp-sociallocker-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-strong'
            ),
            array(
                'image' => array(
                    'function' => 'onp_sl_recolor',
                    'args' => array (
                        ONP_SL_PLUGIN_URL . '/assets/img/lock-icon.png',        // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  ONP_SL_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  ONP_SL_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sociallocker-glass .onp-sociallocker-text .onp-sociallocker-strong:before, .onp-sociallocker-glass .onp-sociallocker-text .onp-sociallocker-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-glass .onp-sociallocker-message, .onp-sociallocker-glass .onp-sociallocker-timer'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sociallocker-glass .onp-sociallocker-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sociallocker-glass .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sociallocker-glass .onp-sociallocker-text + .onp-sociallocker-buttons'
        ),
        // end paddings 

        //button
        'button_mount_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sociallocker-glass .onp-sociallocker-button' 
        ),
        'button_mount_radius' => array(         
            'css'       => array(
                            'border-radius: {value}px;',
                            '-moz-border-radius:{value}px;',
                            '-webkit-border-radius:{value}px;'                           
                           ),
            'selector'  => '.onp-sociallocker-glass .onp-sociallocker-button, .onp-sociallocker-glass .onp-sociallocker-button-inner-wrap' 
        ) 
    );
}