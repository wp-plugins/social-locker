<?php
/**
 * Returns editable options for the Starter theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_starter_theme_editable_options() {

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
                                                    'type' => 'color-and-opacity',
                                                    'name' => 'background_color',
                                                    'title' => __('Set up color and opacity:', 'bizpanda'),
                                                    'default' => array('color' => '#f9f9f9', 'opacity' => 100)
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
                        
                        // font options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Text', 'bizpanda'),
                            'items' => array(
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
                                    'name' => 'button_base_color',
                                    'title' => __('Color', 'bizpanda'),
                                    'default' => '#f2f2f2'
                                )
                            )
                        )
                    )
                )
        ))
    );
}