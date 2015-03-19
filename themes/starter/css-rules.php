<?php
/**
 * Returns CSS generating rules for the Starter theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_starter_theme_css_rules() {
    
    return array(
        
        // background 
        'background_color' => array(
            'css' => 'background-color: {value|onp_to_rgba};',
            'selector' => '.onp-sociallocker-starter'
        ),
        'background_image' => array(
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
            'selector' => '.onp-sociallocker-starter'
        ),
        'background_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sociallocker-starter'
        ),
        // end background

        // text
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sociallocker-starter .onp-sociallocker-strong'
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-starter .onp-sociallocker-message, .onp-sociallocker-starter .onp-sociallocker-timer'
        ),
        // end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sociallocker-starter .onp-sociallocker-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sociallocker-starter .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sociallocker-starter .onp-sociallocker-text + .onp-sociallocker-buttons'
        ),
        // end paddings 

        // button
        'button_base_color' => array(
            'css' => 'background-color: {value};',
            'selector' => '.onp-sociallocker-starter .onp-sociallocker-button-inner-wrap'
        )
        // end button
    );
}
