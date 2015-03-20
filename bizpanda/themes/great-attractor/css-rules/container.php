<?php

$rules = array(  

    // ---
    // Container :: Backgrounds
    // ---

    // Container :: Backgrounds :: Primary Background

    'primary_background_color' => array(
        array(
            'css' => 'background-color: {value|onp_to_rgba};',
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap, .onp-sl-great-attractor .onp-sl-inner-wrap'   
        ),
        array(
            'css' => 'border: 1px solid {value|onp_smart_whiteout_color_10};',
            'selector' => '.onp-sl-great-attractor .onp-sl-subscription.onp-sl-last-group.onp-sl-separator-shows'   
        ),            
    ),
    'primary_background_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-inner-wrap'
        ),
    ),
    'primary_background_image' => array(
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
            'selector' => '.onp-sl-great-attractor .onp-sl-inner-wrap'
        ),
    ),

    // Container :: Backgrounds :: Seconday Background

    'secondary_background_color' => array(
        array(
            'css' => 'background-color: {value|onp_to_rgba};',
            'selector' => '.onp-sl-great-attractor .onp-sl-terms'   
        ),
        array(
            'css' => 'border-top: 1px solid {value|onp_smart_blackout_color_10};',
            'selector'  => '.onp-sl-great-attractor .onp-sl-terms-inner-wrap' 
        ),            
        array(
            'css' => 'border-top: 1px solid {value|onp_smart_blackout_color_15};',
            'selector'  => '.onp-sl-great-attractor .onp-sl-terms' 
        ),
    ), 
    'secondary_background_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-terms'
        ),
        array(
            'css' => 'border-top: 1px solid {value|onp_smart_blackout_color_10};',
            'selector'  => '.onp-sl-great-attractor .onp-sl-terms-inner-wrap' 
        ),            
        array(
            'css' => 'border-top: 1px solid {value|onp_smart_blackout_color_15};',
            'selector'  => '.onp-sl-great-attractor .onp-sl-terms' 
        ),
    ),
    'secondary_background_image' => array(
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
            'selector' => '.onp-sl-great-attractor .onp-sl-terms'
        )
    ),

    // ---
    // Container :: Borders
    // ---

    // Container :: Borders :: Top Border

    'top_border_color' => array(
        array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
        ),
    ),
    'top_border_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
        )
    ),
    'top_border_image' => array(
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
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
        )
    ),
    'top_border_size' => array(
        'css' => 'padding-top: {value}px;',
        'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
    ),

    // Container :: Borders :: Outer Border

    'outer_border_color' => array(
        'css' => 'border-color: {value|onp_to_rgba};',
        'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
    ),
    'outer_border_size' => array(
        'css' => 'border-width: {value}px;',
        'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
    ),
    'outer_border_radius' => array(
        array(
            'css' => 'border-radius: {value}px;',
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
        ),
        array(
            'css' => 'border-radius: {value}px;', 
            'selector' => '.onp-sl-great-attractor .onp-sl-inner-wrap'
        ),
        array(
            'css' => 'overflow: hidden',
            'selector' => '.onp-sl-great-attractor .onp-sl-outer-wrap'
        ),
        array(
            'css' => 'border-radius: 0px;',
            'selector' => '.onp-sl-great-attractor .onp-sl-terms'
        ),            
    ),

    // ---
    // Container :: Text
    // ---

    'header_text' => array(
        array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-text .onp-sl-strong'
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
            'selector' => '.onp-sl-great-attractor .onp-sl-text .onp-sl-strong:before, .onp-sl-great-attractor .onp-sl-text .onp-sl-strong:after'                
        )
    ),
    'message_text' => array(
        'css' => array(
            'font-family: {family|stripcslashes};',
            'font-size: {size}px;',
            'color: {color}; text-shadow:none;'
        ),
        'selector' => '.onp-sl-great-attractor .onp-sl-text'
    ),
    'service_text' => array(
        'css' => array(
            'font-family: {family|stripcslashes};',
            'font-size: {size}px;',
            'color: {color}; text-shadow:none;',
            'border-color: {color}'
        ),
        'selector' => '.onp-sl-great-attractor .onp-sl-timer, .onp-sl-great-attractor .onp-sl-hiding-link-separator .onp-sl-title'
    ),
    'note_text' => array(
        'css' => array(
            'font-family: {family|stripcslashes};',
            'font-size: {size}px;',
            'color: {color}; text-shadow:none;'
        ),
        'selector' => '.onp-sl-great-attractor .onp-sl-subscription .onp-sl-nospam'
    ),
    'terms_text' => array(
        'css' => array(
            'font-family: {family|stripcslashes};',
            'font-size: {size}px;',
            'color: {color}; text-shadow:none;'
        ),
        'selector' => '.onp-sl-great-attractor .onp-sl-terms, .onp-sl-great-attractor .onp-sl-terms a'
    )
);

$result = array_merge( $result, $rules );
