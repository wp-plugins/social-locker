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

    $lockerContainer = array( __('Locker Container', 'sociallocker'), 'locker-box', array(
            
        // accordion           
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Background', 'sociallocker'),
                    'items'     => array(

                        array(
                            'type'      => 'control-group',
                            'name'      => 'backgroud_type',
                            'default'   => 'color',
                            'title'     => __('Background', 'sociallocker'),
                            'items'     => array(
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Color', 'sociallocker'),
                                        'name'      => 'color',
                                        'items'     => array(
                                            array(
                                                'type'      => 'color-and-opacity',
                                                'name'      => 'background_color',
                                                'title'     => __('Set up color and opacity:', 'sociallocker'),
                                                'default'   => array('color' => '#f9f9f9', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Gradient', 'sociallocker'),
                                        'name'      => 'gradient',
                                        'items'     => array(
                                            array(
                                                'type'      => 'gradient',
                                                'name'      => 'background_gradient',
                                                'title'     => __('Set up gradient', 'sociallocker')                                                    
                                            )
                                        )
                                    ),
                                    array(
                                        'type'      => 'control-group-item',
                                        'title'     => __('Pattern', 'sociallocker'),
                                        'name'      => 'image',
                                        'items'     => array(
                                            array(
                                                'type'      => 'pattern',
                                                'name'      => 'background_image',
                                                'title'     => __('Set up pattern', 'sociallocker')
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
    ));
    
    $lockerButtons = array( __('Locker Buttons', 'sociallocker'), 'buttons', array(

        // accordion            
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Cover Backgrounds', 'sociallocker'),
                    'items'     => array(                           
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_twitter_color',
                            'title'     => __('Twitter cover color', 'sociallocker'),
                            'default'   => '#4086cc'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_facebook_color',
                            'title'     => __('Facebook cover color', 'sociallocker'),
                            'default'   => '#3c5a9a'
                        ),   
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_google_color',
                            'title'     => __('Google cover color', 'sociallocker'),
                            'default'   => '#ca4639'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'button_cover_linkedin_color',
                            'title'     => __('LinkedIn cover color', 'sociallocker'),
                            'default'   => '#286b8d'
                        )       
                    )
                ),

                // font options                    
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
                )
            )
        )
    ));
            
    return array( $lockerContainer, $lockerButtons );
}