<?php
/**
 * Returns editable options for the Glass theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_glass_theme_editable_options() {

    return array(
        
        array( __('Locker Container', 'sociallocker'), 'locker-box', array(
            
            // accordion           
            array(
                'type'      => 'accordion',
                'items'     => array(
                    
                    // background                    
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Background', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'background_type',
                                'default' => 'gradient',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'sociallocker'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'background_color',
                                                'title' => __('Set up color and opacity:', 'sociallocker'),
                                                'default' => array('color' => '#e6e6e6', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'sociallocker'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'background_gradient',
                                                'title' => __('Set up gradient:', 'sociallocker'),
                                                'default' => '{"filldirection":"top","color_points":["#fff 0% 0.6", "#F0F0F0 100% 0.6"]}'
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'sociallocker'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'background_image',
                                                'title' => __('Set up pattern', 'sociallocker')
                                            )
                                        )
                                    ),
                                )
                            )
                        )
                    ),
                    
                    // border   
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Border', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'outer_border_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'sociallocker'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'border_color',
                                                'title' => __('Set up color for outer border:', 'sociallocker'),
                                                'default' => array('color' => '#d8d8d8', 'opacity' => 3)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'sociallocker'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'border_gradient',
                                                'title' => __('Set up gradient for outer border:', 'sociallocker')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'sociallocker'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'border_image',
                                                'title' => __('Set up pattern for outer border:', 'sociallocker')
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'border_size',
                                'title' => __('Outer border width', 'sociallocker'),
                                'range' => array(0, 99),                              
                                'default' => 15,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'border_radius',
                                'title' => __('Outer border radius', 'sociallocker'),
                                'range' => array(0, 99),
                                'default' => 15,
                                'units' => 'px'
                            )
                        )
                    ),
                    
                    // font options                    
                    array(
                        'type'      => 'accordion-item',
                        'title'     => __('Text', 'sociallocker'),
                        'items'     => array(
                            array(
                                'type'      => 'font',                                
                                'name'      => 'header_text',
                                'title'     => __('Header text', 'sociallocker'),
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
                                'title'     => __('Message text', 'sociallocker'),
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
                                'title'     => __('Header icons', 'sociallocker'),
                                'default'   => 1
                            )
                        )
                    ),
                    
                    //  paddings options                    
                    array(
                        'type'      => 'accordion-item',
                        'title'     => __('Paddings', 'sociallocker'),
                        'items'     => array(
                            array(
                                'type'      => 'paddings-editor',
                                'name'      => 'container_paddings',
                                'title'     => __('Box paddings', 'sociallocker'),
                                'units'     => 'px',
                                'default'   => '30px 30px 30px 30px'
                            ),
                            array(
                                'type'      => 'integer',
                                'name'      => 'after_header_margin',
                                'way'       => 'slider',
                                'title'     => __('Margin after header', 'sociallocker'),
                                'units'     => 'px',
                                'default'   => '0'
                            ),
                            array(
                                'type'      => 'integer',
                                'name'      => 'after_message_margin',
                                'way'       => 'slider',
                                'title'     => __('Margin after message', 'sociallocker'),
                                'units'     => 'px',
                                'default'   => '5'
                            ),                            
                        )
                    )                 
                )
            )
        )),
            
        array( __('Locker Buttons', 'sociallocker'), 'buttons', array(
            
            // accordion
            
            array(
                'type'      => 'accordion',
                'items'     => array(
                    
                    // background options
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Mounts', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'color-and-opacity',
                                'name' => 'button_mount_color',
                                'title' => __('Color and opacity', 'sociallocker'),
                                'default' => array('color' => '#000000', 'opacity' => 3)
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'button_mount_radius',
                                'title' => __('Border radius', 'sociallocker'),
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