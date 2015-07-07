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

    $lockerContainer = array(__('Locker Container', 'bizpanda'), 'locker-box', array(
        
        // accordion           
        array(
            'type' => 'accordion',
            'items' => array(
                // background options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Background', 'bizpanda'),
                    'items' => array(
                        array(
                            'type' => 'control-group',
                            'name' => 'backgroud_type',
                            'default' => 'color',
                            'title' => __('Background', 'bizpanda'),
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
                                            'default' => array('color' => '#f7f7f7', 'opacity' => 100)
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
                                            'title' => __('Set up gradient', 'bizpanda')
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
                                )
                            )
                        )
                    )
                ),
                // border options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Borders', 'bizpanda'),
                    'items' => array(
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'outer_border_color',
                            'title' => __('Outer border color', 'bizpanda'),
                            'default' => array('color' => '#e6e6e6', 'opacity' => 100)
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'outer_border_size',
                            'title' => __('Outer border width', 'bizpanda'),
                            'range' => array(0, 20),
                            'step' => 1,
                            'default' => 1,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'outer_border_radius',
                            'title' => __('Outer border radius', 'bizpanda'),
                            'range' => array(0, 100),
                            'default' => 0,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'inner_border_color',
                            'title' => __('Inner border color', 'bizpanda'),
                            'default' => array('color' => '#fefefe', 'opacity' => 100)
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'inner_border_size',
                            'title' => __('Inner border width', 'bizpanda'),
                            'range' => array(0, 20),
                            'step' => 1,
                            'default' => 3,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'inner_border_radius',
                            'title' => __('Inner border radius', 'bizpanda'),
                            'range' => array(0, 100),
                            'default' => 0,
                            'units' => 'px'
                        ),
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
                        ),
                        array(
                            'type' => 'checkbox',
                            'way' => 'buttons',
                            'name' => 'header_icon',
                            'title' => __('Header icons', 'bizpanda'),
                            'default' => 1
                        )
                    )
                ),
                //  paddings options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Paddings', 'bizpanda'),
                    'items' => array(
                        array(
                            'type' => 'paddings-editor',
                            'name' => 'container_paddings',
                            'title' => __('Box paddings', 'bizpanda'),
                            'units' => 'px',
                            'default' => '30px 30px 30px 30px'
                        ),
                        array(
                            'type' => 'integer',
                            'name' => 'after_header_margin',
                            'way' => 'slider',
                            'title' => __('Margin after header', 'bizpanda'),
                            'units' => 'px',
                            'default' => '0'
                        ),
                        array(
                            'type' => 'integer',
                            'name' => 'after_message_margin',
                            'way' => 'slider',
                            'title' => __('Margin after message', 'bizpanda'),
                            'units' => 'px',
                            'default' => '5'
                        ),
                    )
                ),
            )
        )
    ));
    
    $lockerButtons = array(__('Locker Buttons', 'bizpanda'), 'buttons', array(
        
        // accordion            
        array(
            'type' => 'accordion',
            'items' => array(

                 array(
                    'type' => 'accordion-item',
                    'title' => __('Cover Text', 'bizpanda'),
                    'items' => array(
                        array(
                            'type' => 'font',
                            'name' => 'button_cover_text_font',
                            'title' => __('Font', 'bizpanda'),
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
                    'title' => __('Twitter Color', 'bizpanda'),
                    'items' => array(
                        // background options
                        array(
                            'type' => 'control-group',
                            'name' => 'button_twitter_substrate',
                            'default' => 'gradient',
                            'items' => array(
                                array(
                                    'type' => 'control-group-item',
                                    'title' => __('Color', 'bizpanda'),
                                    'name' => 'color',
                                    'items' => array(
                                        array(
                                            'type' => 'color-and-opacity',
                                            'name' => 'button_twitter_substrate_color',
                                            'title' => __('Set up color', 'bizpanda')
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
                                            'name' => 'button_twitter_substrate_gradient',
                                            'title' => __('Set up gradient', 'bizpanda'),
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
                    'title' => __('Facebook Color', 'bizpanda'),
                    'items' => array(
                        // background options                              
                        array(
                            'type' => 'control-group',
                            'name' => 'button_facebook_substrate',
                            'default' => 'gradient',
                            'items' => array(
                                array(
                                    'type' => 'control-group-item',
                                    'title' => __('Color', 'bizpanda'),
                                    'name' => 'color',
                                    'items' => array(
                                        array(
                                            'type' => 'color-and-opacity',
                                            'name' => 'button_facebook_substrate_color',
                                            'title' => __('Set up color', 'bizpanda')
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
                                            'name' => 'button_facebook_substrate_gradient',
                                            'title' => __('Set up gradient', 'bizpanda'),
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
                    'title' => __('Google Color', 'bizpanda'),
                    'items' => array(
                        // background options                              
                        array(
                            'type' => 'control-group',
                            'name' => 'button_facebook_substrate',
                            'default' => 'gradient',
                            'items' => array(
                                array(
                                    'type' => 'control-group-item',
                                    'title' => __('Color', 'bizpanda'),
                                    'name' => 'color',
                                    'items' => array(
                                        array(
                                            'type' => 'color-and-opacity',
                                            'name' => 'button_google_substrate_color',
                                            'title' => __('Set up color', 'bizpanda')
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
                                            'name' => 'button_google_substrate_gradient',
                                            'title' => __('Set up gradient', 'bizpanda'),
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
                    'title' => __('LinkedIn Color', 'bizpanda'),
                    'items' => array(
                        // background options                              
                        array(
                            'type' => 'control-group',
                            'name' => 'button_facebook_substrate',
                            'default' => 'gradient',
                            'items' => array(
                                array(
                                    'type' => 'control-group-item',
                                    'title' => __('Color', 'bizpanda'),
                                    'name' => 'color',
                                    'items' => array(
                                        array(
                                            'type' => 'color-and-opacity',
                                            'name' => 'button_linkedin_substrate_color',
                                            'title' => __('Set up color', 'bizpanda')
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
                                            'name' => 'button_linkedin_substrate_gradient',
                                            'title' => __('Set up gradient', 'bizpanda'),
                                            'default' => '{"filldirection":"top","color_points":["#0076a3 0% 1","#005575 100% 1"]}',
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
                // end
            )
        )
    ));

    return array( $lockerContainer, $lockerButtons );
}
