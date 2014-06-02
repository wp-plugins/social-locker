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
        array(__('Locker Container', 'sociallocker'), 'locker-box', array(
            
                // accordion           
                array(
                    'type' => 'accordion',
                    'items' => array(
                        
                        // background                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Background', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'control-group',
                                    'name' => 'background_type',
                                    'default' => 'color',
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
                                                    'default' => array('color' => '#f9f9f9', 'opacity' => 100)
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
                                                    'title' => __('Set up gradient:', 'sociallocker')
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
                        
                        // font options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Text', 'sociallocker'),
                            'items' => array(
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
        
        array(__('Locker Buttons', 'sociallocker'), 'buttons', array(
                // accordion            
                array(
                    'type' => 'accordion',
                    'items' => array(
                        // background options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Mounts', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'color',
                                    'name' => 'button_base_color',
                                    'title' => __('Color', 'sociallocker'),
                                    'default' => '#f2f2f2'
                                )
                            )
                        )
                    )
                )
        ))
    );
}