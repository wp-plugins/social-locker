<?php

$rules = array(  

    // ---
    // Social Buttons :: Background
    // ---

    'social_button_background_color' => array(
        array(
            'css' => array (
                'background: {value|onp_to_rgba};',
                'text-shadow: none;',
                'box-shadow: none;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-button, .onp-sl-great-attractor .onp-sl-control:hover .onp-sl-connect-button'
        ),
    ),
    'social_button_background_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
                'text-shadow: none;',
                'box-shadow: none;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button'
        ),
        array(
            'css' => array(
                'background: {value|onp_smart_blackout_color_5};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-control:hover .onp-sl-connect-button'
        ), 
    ),
    'social_button_background_image' => array(
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
                'background-repeat: repeat;',
                'text-shadow: none;',
                'box-shadow: none;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button, .onp-sl-great-attractor .onp-sl-control:hover .onp-sl-connect-button'
        )
    ),

    // ---
    // Social Buttons :: Borders
    // ---

    'social_button_facebook_border_color' => array(
        array(
            'css' => array (
                'background-color: {value};',
                'border: 1px solid {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-facebook .onp-sl-icon' 
        )
    ),
    'social_button_twitter_border_color' => array(
        array(
            'css' => array (
                'background-color: {value};',
                'border: 1px solid {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-twitter .onp-sl-icon' 
        )
    ),
    'social_button_google_border_color' => array(
        array(
            'css' => array (
                'background-color: {value};',
                'border: 1px solid {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-google .onp-sl-icon' 
        )
    ),
    'social_button_linkedin_border_color' => array(
        array(
            'css' => array (
                'background-color: {value};',
                'border: 1px solid {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-linkedin .onp-sl-icon' 
        )
    ),

    'social_button_border_color' => array(
        array(
            'css' => array (
                'border-color: {value|onp_to_rgba};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button' 
        )
    ),
    'social_button_border_size' => array(
        array(
            'css' => array (
                'border-width: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button' 
        )
    ), 
    'social_button_border_radius' => array(
        array(
            'css' => array (
                'border-top-right-radius: {value}px;',
                'border-bottom-right-radius: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button'
        ),
        array(
            'css' => array (
                'border-top-left-radius: {value}px;',
                'border-bottom-left-radius: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-icon'
        ),  
        array(
            'css' => array (
                'border-radius: {value}px'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-control'
        )         
    ),

    // ---
    // Social Buttons :: Font
    // ---      

    'social_button_text' => array(
        array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',         
                'color: {color};',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-connect-buttons .onp-sl-connect-button'
        )          
    )
);

$result = array_merge( $result, $rules );