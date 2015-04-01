<?php

$rules = array(  

    // ---
    // Forms Buttons :: Background
    // ---

    'form_buttons_background_color' => array(
        array(
            'css' => array (
                'background: {value|onp_to_rgba};',
                'css' => 'text-shadow: none;',
                'box-shadow: 0 2px 1px rgba(0,0,0,.07);',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'   
        ),
        array(
            'css' => array (
                'opacity: 0.95;',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button:hover'   
        ) 
    ),
    'form_buttons_background_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
                'text-shadow: none;',
                'box-shadow: 0 2px 1px rgba(0,0,0,.07);'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'
        ),
        array(
            'css' => array (
                'opacity: 0.95;',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button:hover'   
        )
    ),
    'form_buttons_background_image' => array(
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
                'box-shadow: 0 2px 1px rgba(0,0,0,.07);'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'
        ),
        array(
            'css' => array (
                'opacity: 0.8;',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button:hover'   
        )
    ),

    // ---
    // Forms Buttons :: Border
    // ---

    'form_buttons_border_color' => array(
        array(
            'css' => array (
                'border-color: {value|onp_to_rgba};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'   
        )
    ),
    'form_buttons_border_size' => array(
        array(
            'css' => array (
                'border-width: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'   
        )
    ), 
    'form_buttons_border_radius' => array(
        array(
            'css' => array (
                'border-radius: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button, .onp-sl-great-attractor .onp-sl-form-button:disabled'   
        )
    ),

    // ---
    // Forms Buttons :: Font
    // ---      

    'form_buttons_text' => array(
        array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button'
        )
    ),        

    // ---
    // Forms Buttons :: Paddings
    // ---      

    'form_buttons_paddings' => array(
        array(
            'css' => array(
                'padding: {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-form-button'
        )
    )
);

$result = array_merge( $result, $rules );