<?php

/**
 * Returns editable options for the Secrets theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_secrets_theme_editable_options() {

    return array(
        array(__('Locker Container', 'sociallocker'), 'locker-box', array(
                // accordion           
                array(
                    'type' => 'accordion',
                    'items' => array(
                        // background options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Background', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'control-group',
                                    'name' => 'backgroud_type',
                                    'default' => 'color',
                                    'title' => __('Background', 'sociallocker'),
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
                                                    'default' => array('color' => '#f7f7f7', 'opacity' => 100)
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
                                                    'title' => __('Set up gradient', 'sociallocker')
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
                                        )
                                    )
                                )
                            )
                        ),
                        // border options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Borders', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'color-and-opacity',
                                    'name' => 'outer_border_color',
                                    'title' => __('Outer border color', 'sociallocker'),
                                    'default' => array('color' => '#e6e6e6', 'opacity' => 100)
                                ),
                                array(
                                    'type' => 'integer',
                                    'way' => 'slider',
                                    'name' => 'outer_border_size',
                                    'title' => __('Outer border width', 'sociallocker'),
                                    'range' => array(0, 20),
                                    'step' => 1,
                                    'default' => 1,
                                    'units' => 'px'
                                ),
                                array(
                                    'type' => 'integer',
                                    'way' => 'slider',
                                    'name' => 'outer_border_radius',
                                    'title' => __('Outer border radius', 'sociallocker'),
                                    'range' => array(0, 100),
                                    'default' => 0,
                                    'units' => 'px'
                                ),
                                array(
                                    'type' => 'color-and-opacity',
                                    'name' => 'inner_border_color',
                                    'title' => __('Inner border color', 'sociallocker'),
                                    'default' => array('color' => '#fefefe', 'opacity' => 100)
                                ),
                                array(
                                    'type' => 'integer',
                                    'way' => 'slider',
                                    'name' => 'inner_border_size',
                                    'title' => __('Inner border width', 'sociallocker'),
                                    'range' => array(0, 20),
                                    'step' => 1,
                                    'default' => 3,
                                    'units' => 'px'
                                ),
                                array(
                                    'type' => 'integer',
                                    'way' => 'slider',
                                    'name' => 'inner_border_radius',
                                    'title' => __('Inner border radius', 'sociallocker'),
                                    'range' => array(0, 100),
                                    'default' => 0,
                                    'units' => 'px'
                                ),
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
                                ),
                                array(
                                    'type' => 'checkbox',
                                    'way' => 'buttons',
                                    'name' => 'header_icon',
                                    'title' => __('Header icons', 'sociallocker'),
                                    'default' => 1
                                )
                            )
                        ),
                        //  paddings options                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Paddings', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'paddings-editor',
                                    'name' => 'container_paddings',
                                    'title' => __('Box paddings', 'sociallocker'),
                                    'units' => 'px',
                                    'default' => '30px 30px 30px 30px'
                                ),
                                array(
                                    'type' => 'integer',
                                    'name' => 'after_header_margin',
                                    'way' => 'slider',
                                    'title' => __('Margin after header', 'sociallocker'),
                                    'units' => 'px',
                                    'default' => '0'
                                ),
                                array(
                                    'type' => 'integer',
                                    'name' => 'after_message_margin',
                                    'way' => 'slider',
                                    'title' => __('Margin after message', 'sociallocker'),
                                    'units' => 'px',
                                    'default' => '5'
                                ),
                            )
                        ),
                    )
                )
            )),
        array(__('Locker Buttons', 'sociallocker'), 'buttons', array(
                // accordion            
                array(
                    'type' => 'accordion',
                    'items' => array(
                        
                         array(
                            'type' => 'accordion-item',
                            'title' => __('Cover Text', 'sociallocker'),
                            'items' => array(
                                array(
                                    'type' => 'font',
                                    'name' => 'button_cover_text_font',
                                    'title' => __('Font', 'sociallocker'),
                                    'default' => array(
                                        'size' => 14, 
                                        'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                        'color' => '#ffffff'
                                    ),
                                    'units' => 'px'
                                )
                            )
                        ),
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Twitter Color', 'sociallocker'),
                            'items' => array(
                                // background options
                                array(
                                    'type' => 'control-group',
                                    'name' => 'button_twitter_substrate',
                                    'default' => 'gradient',
                                    'items' => array(
                                        array(
                                            'type' => 'control-group-item',
                                            'title' => __('Color', 'sociallocker'),
                                            'name' => 'color',
                                            'items' => array(
                                                array(
                                                    'type' => 'color-and-opacity',
                                                    'name' => 'button_twitter_substrate_color',
                                                    'title' => __('Set up color', 'sociallocker')
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
                                                    'name' => 'button_twitter_substrate_gradient',
                                                    'title' => __('Set up gradient', 'sociallocker'),
                                                    'default' => '{"filldirection":"top","color_points":["#00beec 0% 1","#00a0e0 100% 1"]}'
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        //end
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Facebook Color', 'sociallocker'),
                            'items' => array(
                                // background options                              
                                array(
                                    'type' => 'control-group',
                                    'name' => 'button_facebook_substrate',
                                    'default' => 'gradient',
                                    'items' => array(
                                        array(
                                            'type' => 'control-group-item',
                                            'title' => __('Color', 'sociallocker'),
                                            'name' => 'color',
                                            'items' => array(
                                                array(
                                                    'type' => 'color-and-opacity',
                                                    'name' => 'button_facebook_substrate_color',
                                                    'title' => __('Set up color', 'sociallocker')
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
                                                    'name' => 'button_facebook_substrate_gradient',
                                                    'title' => __('Set up gradient', 'sociallocker'),
                                                    'default' => '{"filldirection":"top","color_points":["#537fba 0% 1","#255b9d 100% 1"]}'
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        //end
                        array(
                            'type' => 'accordion-item',
                            'title' => __('Google Color', 'sociallocker'),
                            'items' => array(
                                // background options                              
                                array(
                                    'type' => 'control-group',
                                    'name' => 'button_facebook_substrate',
                                    'default' => 'gradient',
                                    'items' => array(
                                        array(
                                            'type' => 'control-group-item',
                                            'title' => __('Color', 'sociallocker'),
                                            'name' => 'color',
                                            'items' => array(
                                                array(
                                                    'type' => 'color-and-opacity',
                                                    'name' => 'button_google_substrate_color',
                                                    'title' => __('Set up color', 'sociallocker')
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
                                                    'name' => 'button_google_substrate_gradient',
                                                    'title' => __('Set up gradient', 'sociallocker'),
                                                    'default' => '{"filldirection":"top","color_points":["#5e5e5e 0% 1","#1c1012 100% 1"]}'
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        //end                    
                        array(
                            'type' => 'accordion-item',
                            'title' => __('LinkedIn Color', 'sociallocker'),
                            'items' => array(
                                // background options                              
                                array(
                                    'type' => 'control-group',
                                    'name' => 'button_facebook_substrate',
                                    'default' => 'gradient',
                                    'items' => array(
                                        array(
                                            'type' => 'control-group-item',
                                            'title' => __('Color', 'sociallocker'),
                                            'name' => 'color',
                                            'items' => array(
                                                array(
                                                    'type' => 'color-and-opacity',
                                                    'name' => 'button_linkedin_substrate_color',
                                                    'title' => __('Set up color', 'sociallocker')
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
                                                    'name' => 'button_linkedin_substrate_gradient',
                                                    'title' => __('Set up gradient', 'sociallocker'),
                                                    'default' => '{"filldirection":"top","color_points":["#0076a0 0% 1","#005673 100% 1"]}',
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    //end
                    )
                )
            ))
    );
}
