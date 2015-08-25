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
            'selector' => '.onp-sl-starter'
        ),
        'background_image' => array(
            'image' => array(
                'function' => 'opanda_sr_rehue',
                'args' => array (
                    '{url}',                                                
                    '{color}',                                              
                    array(                                                  
                        'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                        'url' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',        
                    )
                )
            ),
            'css' => array(
                'background-image: url("{image}");',
                'background-repeat: repeat;'
            ),
            'selector' => '.onp-sl-starter'
        ),
        'background_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sl-starter'
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
                'selector' => '.onp-sl-starter .onp-sl-strong'
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
                'selector' => '.onp-sl-starter .onp-sl-text .onp-sl-strong:before, .onp-sl-starter .onp-sl-text .onp-sl-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-starter, .onp-sl-starter .onp-sl button, .onp-sl-starter input, .onp-sl-starter p'
        ),
        // end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sl-starter .onp-sl-social-buttons'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-starter .onp-sl-text .onp-sl-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-starter .onp-sl-text'
        ),
        // end paddings 

        // button
        'button_base_color' => array(
            'css' => 'background-color: {value};',
            'selector' => '.onp-sl-starter .onp-sl-social-buttons .onp-sl-control'
        )
        // end button
    );
}
