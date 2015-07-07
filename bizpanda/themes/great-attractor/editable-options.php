<?php
/**
 * Returns editable options for the Flat theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_great_attractor_theme_editable_options() {

    $containerOptions = array( __('Container', 'bizpanda'), 'locker-box', array(
            
        // accordion           
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Backgrounds', 'bizpanda'),
                    'items'     => array(
                        opanda_background_editor_options('primary_background', array(
                            'title' => __('Primary background', 'bizpanda'),
                            'default' => array(
                                'type' => 'color',
                                'value' => array('color' => '#ffffff', 'opacity' => 100)
                            )
                        )),
                        opanda_background_editor_options('secondary_background', array(
                            'title' => __('Secondary background', 'bizpanda'),
                            'default' => array(
                                'type' => 'color',
                                'value' => array('color' => '#f9f9f9', 'opacity' => 100)
                            )
                        )),
                    )
                ),
                
                // border options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Borders', 'bizpanda'),
                    'items'     => array(
                        opanda_background_editor_options('top_border', array(
                            'title' => __('Top border background', 'bizpanda'),
                            'default' => array(
                                'type' => 'image',
                                'value' => array( 
                                    'url' => OPANDA_SR_PLUGIN_URL . '/assets/img/ga-top-border.png',
                                    'color' => null
                                )
                            ),
                            'patterns' => array(
                                array(
                                    'preview' => OPANDA_SR_PLUGIN_URL . '/assets/img/ga-top-border-preview.png',
                                    'pattern' => OPANDA_SR_PLUGIN_URL . '/assets/img/ga-top-border.png'
                                )
                            )
                        )),
                        array(
                             'type' => 'integer',
                             'way' => 'slider',
                             'name' => 'top_border_size',
                             'title' => __('Top border height', 'bizpanda'),
                             'range' => array(0, 50),                              
                             'default' => 5,
                             'units' => 'px'
                         ),
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'outer_border_color',
                            'title' => __('Outer border color', 'bizpanda'),
                            'default' => array('color' => '#c1c1c1', 'opacity' => 100)
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
                            'default' => 7,
                            'units' => 'px'
                        )
                    )
                ),

                // font options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Text', 'bizpanda'),
                    'items' => array(
                        array(
                            'type'      => 'google-font',                                
                            'name'      => 'header_text',
                            'title'     => __('Header text', 'bizpanda'),
                            'default'   => array(
                                'size' => 18, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#111111'
                            ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'google-font',                                
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
                            'type'      => 'google-font',                                
                            'name'      => 'service_text',
                            'title'     => __('Service text', 'bizpanda'),
                            'default'   => array(
                                'size' => 13, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#111'
                            ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'google-font',                                
                            'name'      => 'note_text',
                            'title'     => __('Note text', 'bizpanda'),
                            'default'   => array(
                                'size' => 12, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#aaaaaa'
                            ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'google-font',                                
                            'name'      => 'terms_text',
                            'title'     => __('Footer text', 'bizpanda'),
                            'default'   => array(
                                'size' => 12, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#555'
                            ),
                            'units'     => 'px'
                        )
                    )
                )
            )
        )
    ));
    
    $socialButtons = array( __('Social Buttons', 'bizpanda'), 'buttons', array(

        // accordion            
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Background', 'bizpanda'),
                    'items'     => array(                           
                        opanda_background_editor_options('social_button_background', array(
                            'default' => array(
                                'type' => 'gradient',
                                'value' => '{"filldirection":"top","color_points":["#f6f6f6 100% 1","#fdfdfd 0% 1"]}'
                            )
                        ))      
                    )
                ),
                
                // borders                   
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Borders', 'bizpanda'),
                    'items'     => array(                           
                        array(
                            'type'      => 'color',
                            'name'      => 'social_button_facebook_border_color',
                            'title'     => __('Facebook left border color', 'bizpanda'),
                            'default'   => '#989de1'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'social_button_twitter_border_color',
                            'title'     => __('Twitter left border color', 'bizpanda'),
                            'default'   => '#55acee'
                        ),   
                        array(
                            'type'      => 'color',
                            'name'      => 'social_button_google_border_color',
                            'title'     => __('Google left border color', 'bizpanda'),
                            'default'   => '#f47665'
                        ),
                        array(
                            'type'      => 'color',
                            'name'      => 'social_button_linkedin_border_color',
                            'title'     => __('LinkedIn left border color', 'bizpanda'),
                            'default'   => '#0077b5'
                        ),
                        
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'social_button_border_color',
                            'title' => __('Outer border color', 'bizpanda'),
                            'default' => array('color' => '#c9c9c9', 'opacity' => 100)
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'social_button_border_size',
                            'title' => __('Outer border width', 'bizpanda'),
                            'range' => array(0, 20),
                            'step' => 1,
                            'default' => 1,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'social_button_border_radius',
                            'title' => __('Outer border radius', 'bizpanda'),
                            'range' => array(0, 100),
                            'default' => 3,
                            'units' => 'px'
                        )
                    )
                ),

                // font options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Text', 'bizpanda'),
                    'items' => array(
                        array(
                            'type' => 'google-font',
                            'name' => 'social_button_text',
                            'title' => __('Font', 'bizpanda'),
                            'default' => array(
                                'size' => 13, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#111111'
                            ),
                            'units' => 'px'
                        )
                    )
                )
            )
        )
    ));
    
    $formButtonsOptions = array( __('Form Buttons', 'bizpanda'), 'form-buttons-box', array(
            
        // accordion           
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Background', 'bizpanda'),
                    'items'     => array(
                        opanda_background_editor_options('form_buttons_background', array(
                            'default' => array(
                                'type' => 'gradient',
                                'value' => '{"filldirection":"top","color_points":["#f2f2f2 0% 1","#fefefe 100% 1"]}'
                            )
                        ))
                    )
                ),
                
                // border options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Border', 'bizpanda'),
                    'items'     => array(
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'form_buttons_border_color',
                            'title' => __('Border color', 'bizpanda'),
                            'default' => array('color' => '#c9c9c9', 'opacity' => 100)
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'form_buttons_border_size',
                            'title' => __('Border width', 'bizpanda'),
                            'range' => array(0, 20),
                            'step' => 1,
                            'default' => 1,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'form_buttons_border_radius',
                            'title' => __('Border radius', 'bizpanda'),
                            'range' => array(0, 100),
                            'default' => 3,
                            'units' => 'px'
                        )
                    )
                ),

                // font options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Text', 'bizpanda'),
                    'items' => array(
                        array(
                            'type'      => 'google-font',                                
                            'name'      => 'form_buttons_text',
                            'title'     => __('Font', 'bizpanda'),
                            'default'   => array(
                                'size' => 13, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#363636'
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
                            'name'      => 'form_buttons_paddings',
                            'title'     => __('Inner paddings', 'bizpanda'),
                            'units'     => 'px',
                            'default'   => '10px 10px 6px 0px'
                        )                        
                    )
                )
            )
        )
    ));
    
    $formFieldsOptions = array( __('Form Textboxes', 'bizpanda'), 'form-fields-box', array(
            
        // accordion           
        array(
            'type'      => 'accordion',
            'items'     => array(

                // background options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Background', 'bizpanda'),
                    'items'     => array(
                        opanda_background_editor_options('form_fields_background', array(
                            'title' => __('Standard background', 'bizpanda'),
                            'default' => array(
                                'type' => 'color',
                                'value' => array('color' => '#ffffff', 'opacity' => 100)
                            )
                        ))
                    )
                ),
                
                // border options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Border', 'bizpanda'),
                    'items'     => array(
                        array(
                            'type' => 'color-and-opacity',
                            'name' => 'form_fields_border_color',
                            'title' => __('Border color', 'bizpanda'),
                            'default' => array('color' => '#c4c4c4', 'opacity' => 100)
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'form_fields_border_size',
                            'title' => __('Border width', 'bizpanda'),
                            'range' => array(0, 20),
                            'step' => 1,
                            'default' => 1,
                            'units' => 'px'
                        ),
                        array(
                            'type' => 'integer',
                            'way' => 'slider',
                            'name' => 'form_fields_border_radius',
                            'title' => __('Border radius', 'bizpanda'),
                            'range' => array(0, 100),
                            'default' => 3,
                            'units' => 'px'
                        )
                    )
                ),

                // font options                    
                array(
                    'type' => 'accordion-item',
                    'title' => __('Text', 'bizpanda'),
                    'items' => array(
                        array(
                            'type'      => 'google-font',                                
                            'name'      => 'form_fields_text',
                            'title'     => __('Font', 'bizpanda'),
                            'default'   => array(
                                'size' => 13, 
                                'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                'color' => '#363636'
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
                            'name'      => 'form_fields_paddings',
                            'title'     => __('Inner paddings', 'bizpanda'),
                            'units'     => 'px',
                            'default'   => '10px 10px 10px 10px'
                        )                        
                    )
                )
            )
        )
    ));
                
    return array( $containerOptions, $socialButtons, $formButtonsOptions, $formFieldsOptions );
}