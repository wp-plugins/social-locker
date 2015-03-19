<?php

$rules = array(  

    // ---
    // Forms Fields :: Background
    // ---

    'form_fields_background_color' => array(
        array(
            'css' => array (
                'background: {value|onp_to_rgba};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'
        )
    ),
    'form_fields_background_gradient' => array(
        array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'
        )
    ),
    'form_fields_background_image' => array(
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
            'selector' => '.onp-sl-great-attractor .onp-sl-input'
        )
    ),

    // ---
    // Forms Fields :: Border
    // ---

    'form_fields_border_color' => array(
        array(
            'css' => array (
                'border-color: {value|onp_to_rgba};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'   
        )
    ),
    'form_fields_border_size' => array(
        array(
            'css' => array (
                'border-width: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'  
        )
    ), 
    'form_fields_border_radius' => array(
        array(
            'css' => array (
                'border-radius: {value}px;'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'  
        )
    ),

    // ---
    // Forms Fields :: Font
    // ---      

    'form_fields_text' => array(
        array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'line-height: 100%;',           
                'color: {color};',
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'
        )          
    ),        

    // ---
    // Forms Fields :: Paddings
    // ---      

    'form_fields_paddings' => array(
        array(
            'css' => array(
                'padding: {value};'
            ),
            'selector' => '.onp-sl-great-attractor .onp-sl-input'
        )
    )
);

$result = array_merge( $result, $rules );
