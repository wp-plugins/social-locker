<?php
/**
 * Returns CSS generating rules for the Dandyish theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_dandyish_theme_css_rules() {

    return array(   
        
        // background 
        'background_color' => array( 
            array(
                'css' => 'background: {value};',
                'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-dandyish .onp-sl-strong, .onp-sl-dandyish .onp-sl-message, .onp-sl-dandyish .onp-sl-timer'
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
                'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'    
            ),
            array(
                'css' => 'text-shadow: none;',
                'selector' => '.onp-sl-dandyish .onp-sl-strong, .onp-sl-dandyish .onp-sl-message, .onp-sl-dandyish .onp-sl-timer'
            ),
        ),
        'background_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'
        ),
        // end background
        
        // outer border
        'outer_border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sl-dandyish'
        ),
        'outer_border_image' => array(
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
            'selector' => '.onp-sl-dandyish'          
        ),
        'outer_border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sl-dandyish'
        ),
        'outer_border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sl-dandyish'
        ),
        'outer_border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sl-dandyish'
        ),
        // end outer border
        
        // inner border
        'inner_border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sl-dandyish .onp-sl-outer-wrap'
        ),
        'inner_border_image' => array(
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
            'selector' => '.onp-sl-dandyish .onp-sl-outer-wrap'          
        ),
        'inner_border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sl-dandyish .onp-sl-outer-wrap'
        ),
        'inner_border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sl-dandyish .onp-sl-outer-wrap'
        ),
        'inner_border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sl-dandyish .onp-sl-outer-wrap, .onp-sl-dandyish .onp-sl-inner-wrap'
        ),
        // end inner border

        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sl-dandyish .onp-sl-strong::before, .onp-sl-dandyish .onp-sl-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sl-dandyish .onp-sl-strong'
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
                'selector' => '.onp-sl-dandyish .onp-sl-text .onp-sl-strong:before, .onp-sl-dandyish .onp-sl-text .onp-sl-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-dandyish, .onp-sl-dandyish .onp-sl button, .onp-sl-dandyish input, .onp-sl-dandyish p'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-dandyish .onp-sl-text .onp-sl-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-dandyish .onp-sl-text'
        ),
        // end paddings 
     
        //button
        'button_mount_color' => array(
            'css' => 'background: {value};',
            'selector'  => '.onp-sl-dandyish .onp-sl-control' 
        ),
        'button_mount_radius' => array(         
            'css'       => array(
                            'border-radius: {value}px;',
                            '-moz-border-radius:{value}px;',
                            '-webkit-border-radius:{value}px;'                           
                           ),
            'selector'  => '.onp-sl-dandyish .onp-sl-control, .onp-sl-dandyish .onp-sl-button-inner-wrap' 
        )       
    );
}

