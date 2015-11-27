<?php
/**
 * Returns editable options for the Flat theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_flat_theme_editable_options() {

    $lockerContainer = array( __('Locker Container', 'bizpanda'), 'locker-box', array(
            
        // accordion           
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Background', 'bizpanda'),
                    'items'     => array(

                        array(
                            'type'      => 'control-group',
                            'name'      => 'backgroud_type',
                            'default'   => 'color',
                            'title'     => __('Background', 'bizpanda'),
                            'items'     => array(
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Color', 'bizpanda'),
                                        'name'      => 'color',
                                        'items'     => array(
                                            array(
                                                'type'      => 'color-and-opacity',
                                                'name'      => 'background_color',
                                                'title'     => __('Set up color and opacity:', 'bizpanda'),
                                                'default'   => array('color' => '#f9f9f9', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Gradient', 'bizpanda'),
                                        'name'      => 'gradient',
                                        'items'     => array(
                                            array(
                                                'type'      => 'gradient',
                                                'name'      => 'background_gradient',
                                                'title'     => __('Set up gradient', 'bizpanda')                                                    
                                            )
                                        )
                                    ),
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Pattern', 'bizpanda'),
                                        'name'      => 'image',
                                        'items'     => array(
                                            array(
                                                'type'      => 'pattern',
                                                'name'      => 'background_image',
                                                'title'     => __('Set up pattern', 'bizpanda')
                                            )
                                        )
                                    )
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
    ));
    
    $lockerButtons = array( __('Locker Buttons', 'bizpanda'), 'buttons', array(

        // accordion            
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Cover Backgrounds', 'bizpanda'),
                    'items'     => array(                           
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_twitter_color',
                            'title'     => __('Twitter cover color', 'bizpanda'),
                            'default'   => '#4086cc'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_facebook_color',
                            'title'     => __('Facebook cover color', 'bizpanda'),
                            'default'   => '#3c5a9a'
                        ),   
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_google_color',
                            'title'     => __('Google cover color', 'bizpanda'),
                            'default'   => '#ca4639'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_linkedin_color',
                            'title'     => __('LinkedIn cover color', 'bizpanda'),
                            'default'   => '#286b8d'
                        )       
                    )
                ),

                // font options                    
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
                )
            )
        )
    ));
            
    return array( $lockerContainer, $lockerButtons );
}