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
                'selector' => '.onp-sl-glass .onp-sl-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-glass .onp-sl-strong, .onp-sl-glass .onp-sl-message, .onp-sl-glass .onp-sl-timer'
            )
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
                'selector' => '.onp-sl-glass .onp-sl-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-glass .onp-sl-strong, .onp-sl-glass .onp-sl-message, .onp-sl-glass .onp-sl-timer'
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
                'selector' => '.onp-sl-glass .onp-sl-outer-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-glass .onp-sl-strong, .onp-sl-glass .onp-sl-message, .onp-sl-glass .onp-sl-timer'
            )
        ),
        // end background
        
        // border
        'border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sl-glass'
        ),     
        'border_image' => array(
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
            'selector' => '.onp-sl-glass'
        ),
        'border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sl-glass'
        ),
        'border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sl-glass'
        ),
        'border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sl-glass, .onp-sl-glass .onp-sl-outer-wrap'
        ),
        // end border

        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sl-glass .onp-sl-strong::before, .onp-sl-glass .onp-sl-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sl-glass .onp-sl-strong'
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
                'selector' => '.onp-sl-glass .onp-sl-text .onp-sl-strong:before, .onp-sl-glass .onp-sl-text .onp-sl-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-glass, .onp-sl-glass .onp-sl button, .onp-sl-glass input, .onp-sl-glass p'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sl-glass .onp-sl-social-buttons'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-glass .onp-sl-text .onp-sl-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-glass .onp-sl-text'
        ),
        // end paddings 

        //button
        'button_mount_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sl-glass .onp-sl-control' 
        ),
        'button_mount_radius' => array(         
            'css'       => array(
                            'border-radius: {value}px;',
                            '-moz-border-radius:{value}px;',
                            '-webkit-border-radius:{value}px;'                           
                           ),
            'selector'  => '.onp-sl-glass .onp-sl-control, .onp-sl-glass .onp-sl-button-inner-wrap' 
        ) 
    );
}