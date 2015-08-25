<?php
/**
 * Returns editable options for the Dandyish theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_dandyish_theme_editable_options() {
    
    return array(
        array(__('Locker Container', 'bizpanda'), 'locker-box', array(
            
            // accordion           
            array(
                'type' => 'accordion',
                'items' => array(

                    // background                    
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Background', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'background_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'bizpanda'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color',
                                                'name' => 'background_color',
                                                'title' => __('Set up color and opacity:', 'bizpanda'),
                                                'default' => '#f9f9f9'
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'bizpanda'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'background_gradient',
                                                'title' => __('Set up gradient:', 'bizpanda')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'bizpanda'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'background_image',
                                                'title' => __('Set up pattern', 'bizpanda')
                                            )
                                        )
                                    ),
                                )
                            )
                        )
                    ),

                    // outer borders     
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Outer Border', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'outer_border_type',
                                'default' => 'image',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'bizpanda'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'outer_border_color',
                                                'title' => __('Set up color for outer border:', 'bizpanda'),
                                                'default' => array('color' => '#e6e6e6', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'bizpanda'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'outer_border_gradient',
                                                'title' => __('Set up gradient for outer border:', 'bizpanda')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'bizpanda'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'outer_border_image',
                                                'title' => __('Set up pattern for outer border:', 'bizpanda'),
                                                'default' => array( 
                                                    'url' => OPANDA_BIZPANDA_DIR . '/assets/img/dandysh-border.png',
                                                    'color' => null
                                                ),
                                                'patterns' => array(
                                                    array(
                                                        'preview' => OPANDA_BIZPANDA_URL . '/assets/img/dandysh-border.png',
                                                        'pattern' => OPANDA_BIZPANDA_URL . '/assets/img/dandysh-border.png'
                                                    )
                                                )
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'outer_border_size',
                                'title' => __('Outer border width', 'bizpanda'),
                                'range' => array(0, 99),                              
                                'default' => 7,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'outer_border_radius',
                                'title' => __('Outer border radius', 'bizpanda'),
                                'range' => array(0, 99),
                                'default' => 12,
                                'units' => 'px'
                            )
                        )
                    ),

                    // inner borders     
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Inner Border', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'inner_border_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'bizpanda'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'inner_border_color',
                                                'title' => __('Set up color for inner border:', 'bizpanda'),
                                                'default' => array('color' => '#ffffff', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'bizpanda'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'inner_border_gradient',
                                                'title' => __('Set up gradient for inner border:', 'bizpanda')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'bizpanda'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'inner_border_image',
                                                'title' => __('Set up pattern for inner border:', 'bizpanda')
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'inner_border_size',
                                'title' => __('Inner border width', 'bizpanda'),
                                'range' => array(0, 99),                              
                                'default' => 5,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'inner_border_radius',
                                'title' => __('Inner border radius', 'bizpanda'),
                                'range' => array(0, 99),
                                'default' => 10,
                                'units' => 'px'
                            )
                        )
                    ),

                // font options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Text', 'bizpanda'),
                    'items'     => array(
                        array(
                            'type'      => 'font',                                
                            'name'      => 'header_text',
                            'title'     => __('Header text', 'bizpanda'),
                            'default'   => array(
                                            'size' => 16, 
                                            'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                            'color' => '#111111'
                                        ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'font',                                
                            'name'      => 'message_text',
                            'title'     => __('Message text', 'bizpanda'),
                            'default'   => array(
                                            'size' => 13, 
                                            'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                            'color' => '#111111'
                                           ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'checkbox',
                            'way'       => 'buttons',
                            'name'      => 'header_icon',
                            'title'     => __('Header icons', 'bizpanda'),
                            'default'   => 1
                        )
                    )
                ),

                //  paddings options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Paddings', 'bizpanda'),
                    'items'     => array(
                        array(
                            'type'      => 'paddings-editor',
                            'name'      => 'container_paddings',
                            'title'     => __('Box paddings', 'bizpanda'),
                            'units'     => 'px',
                            'default'   => '30px 30px 30px 30px'
                        ),
                        array(
                            'type'      => 'integer',
                            'name'      => 'after_header_margin',
                            'way'       => 'slider',
                            'title'     => __('Margin after header', 'bizpanda'),
                            'units'     => 'px',
                            'default'   => '0'
                        ),
                        array(
                            'type'      => 'integer',
                            'name'      => 'after_message_margin',
                            'way'       => 'slider',
                            'title'     => __('Margin after message', 'bizpanda'),
                            'units'     => 'px',
                            'default'   => '5'
                        ),                            
                    )
                ))
            )
        )),
    
        array(__('Locker Buttons', 'bizpanda'), 'buttons', array(
            
            // accordion
            array(
                'type' => 'accordion',
                'items' => array(

                    // background options
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Mounts', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'color',
                                'name' => 'button_mount_color',
                                'title' => __('Color and opacity', 'bizpanda'),
                                'default' => '#ffffff'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'button_mount_radius',
                                'title' => __('Border radius', 'bizpanda'),
                                'range' => array(0, 99),
                                'default' => 7,
                                'units' => 'px'
                            )
                        )
                    )
                )
            )
        ))
    );
}

/**
 * Registers links for the Secrets theme between form controls and CSS.
 */
function onp_sl_register_dandyish_theme_options_to_css( $rules, $theme ) {
    if ( $theme !== 'dandyish') return $rules;
    
    return array(   
        
        // background 
        'background_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'
        ),
        'background_image' => array(
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector' => '.onp-sl-dandyish .onp-sl-inner-wrap'
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
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector'  => '.onp-sl-dandyish'
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
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector'  => '.onp-sl-dandyish .onp-sl-outer-wrap'
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
            'selector' => '.onp-sl-dandyish .onp-sl-outer-wrap, .onp-sl-dandyish onp-sociallocker-inner-wrap'
        ),
        // end inner border

        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sl-dandyish .onp-sl-strong::before, .onp-sl-dandyish .onp-sl-strong::after'
        ),
        'header_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-dandyish .onp-sl-text .onp-sl-strong'
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-dandyish .onp-sl-text, .onp-sl-dandyish .onp-sl-timer'
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
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sl-dandyish .onp-sl-text + .onp-sl-buttons'
        ),
        // end paddings 
     
        //button
        'button_mount_color' => array(
            'css' => 'background: {value};',
            'selector'  => '.onp-sl-dandyish .onp-sl-button-inner-wrap' 
        ),
        'button_mount_radius' => array(         
            'css'       => array(
                            'border-radius: {value}px;',
                            '-moz-border-radius:{value}px;',
                            '-webkit-border-radius:{value}px;'                           
                           ),
            'selector'  => '.onp-sl-dandyish .onp-sl-button, .onp-sl-dandyish .onp-sl-button-inner-wrap' 
        )       
        
    );
}

