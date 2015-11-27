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
        
        array( __('Locker Container', 'bizpanda'), 'locker-box', array(
            
            // accordion           
            array(
                'type'      => 'accordion',
                'items'     => array(
                    
                    // background                    
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Background', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'background_type',
                                'default' => 'gradient',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'bizpanda'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'background_color',
                                                'title' => __('Set up color and opacity:', 'bizpanda'),
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
                                                'name' => 'background_gradient',
                                                'title' => __('Set up gradient:', 'bizpanda'),
                                                'default' => '{"filldirection":"top","color_points":["#fff 0% 0.6", "#F0F0F0 100% 0.6"]}'
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
                    
                    // border   
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Border', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'outer_border_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'bizpanda'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'border_color',
                                                'title' => __('Set up color for outer border:', 'bizpanda'),
                                                'default' => array('color' => '#d8d8d8', 'opacity' => 3)
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
                                                'name' => 'border_gradient',
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
                                                'name' => 'border_image',
                                                'title' => __('Set up pattern for outer border:', 'bizpanda')
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'border_size',
                                'title' => __('Outer border width', 'bizpanda'),
                                'range' => array(0, 99),                              
                                'default' => 15,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'border_radius',
                                'title' => __('Outer border radius', 'bizpanda'),
                                'range' => array(0, 99),
                                'default' => 15,
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
                    )                 
                )
            )
        )),
            
        array( __('Locker Buttons', 'bizpanda'), 'buttons', array(
            
            // accordion
            
            array(
                'type'      => 'accordion',
                'items'     => array(
                    
                    // background options
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Mounts', 'bizpanda'),
                        'items' => array(
                            array(
                                'type' => 'color-and-opacity',
                                'name' => 'button_mount_color',
                                'title' => __('Color and opacity', 'bizpanda'),
                                'default' => array('color' => '#000000', 'opacity' => 3)
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