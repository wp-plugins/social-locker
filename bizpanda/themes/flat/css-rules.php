<?php
/**
 * Returns CSS generating rules for the Flat theme.
 * 
 * @see OnpSL_ThemeManager::getRulesToGenerateCSS
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_flat_theme_css_rules() {

    $result = array(  
        
        // background
        'background_color' => array(
            array(
                'css' => 'background-color: {value|onp_to_rgba};',
                'selector' => '.onp-sl-flat .onp-sl-inner-wrap'   
            ),
            array(
                'css' => 'border-bottom: 3px solid {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-outer-wrap' 
            ),
            array(
                'css' => 'border-top-color: {value|onp_to_rgba};',
                'selector' => '.onp-sl-flat .onp-sl-flip:hover .onp-sl-overlay-back'
            )  
        ),
        'background_image' => array(
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sl-flat .onp-sl-flip:hover .onp-sl-overlay-back'
            ),
            array(
                'image' => array(
                    'function' => 'opanda_sr_rehue',
                    'args' => array (
                        '{url}',                                                // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => array(
                    'background-image: url("{image}");',
                    'background-repeat: repeat;'
                ),
                'selector' => '.onp-sl-flat .onp-sl-inner-wrap'          
            )
        ),
        'background_gradient' => array(
            array(
                'css' => array(
                    'background: {value|onp_to_gradient};',
                    'background: -webkit-{value|onp_to_gradient};',
                    'background: -moz-{value|onp_to_gradient};',
                    'background: -o-{value|onp_to_gradient};',
                ),
                'selector' => '.onp-sl-flat .onp-sl-inner-wrap'
            ),
            array(
                'css' => 'border-top-color: transparent;',
                'selector' => '.onp-sl-flat .onp-sl-flip:hover .onp-sl-overlay-back'
            ),
            array(
                'css' => 'border-bottom: 3px solid {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-outer-wrap' 
            ),
        ),
        // end background
  
        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sl-flat .onp-sl-strong::before, .onp-sl-flat .onp-sl-strong::after'
        ),
        'header_text' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sl-flat .onp-sl-strong'
            ),
            array(
                'image' => array(
                    'function' => 'opanda_sr_recolor',
                    'args' => array (
                        OPANDA_BIZPANDA_URL . '/assets/img/lock-icon.png',        // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sl-flat .onp-sl-text .onp-sl-strong:before, .onp-sl-flat .onp-sl-text .onp-sl-strong:after'                
            )
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sl-flat, .onp-sl-flat .onp-sl button, .onp-sl-flat input, .onp-sl-flat p'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sl-flat .onp-sl-social-buttons'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-flat .onp-sl-text .onp-sl-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sl-flat .onp-sl-text'
        ),
        // end paddings 

        // button backgrounds
        // - twitter
        'button_cover_twitter_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sl-flat .onp-sl-twitter .onp-sl-overlay-front, .onp-sl-flat .onp-sl-twitter .onp-sl-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-twitter .onp-sl-overlay-front' 
            ),
            'button_twitter_substrate_color' => array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sl-flat .onp-sl-twitter .onp-sl-overlay-header' 
            )
        ),

        // - facebook
        'button_cover_facebook_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sl-flat .onp-sl-facebook .onp-sl-overlay-front, .onp-sl-flat .onp-sl-facebook .onp-sl-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-facebook .onp-sl-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sl-flat .onp-sl-facebook .onp-sl-overlay-header' 
            )
        ),

        // - google
        'button_cover_google_color' => array(
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sl-flat .onp-sl-google .onp-sl-overlay-front, .onp-sl-flat .onp-sl-google .onp-sl-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-google .onp-sl-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sl-flat .onp-sl-google .onp-sl-overlay-header' 
            )
        ),

        // - linkedin
        'button_cover_linkedin_color' => array( 
            array(            
                'css'       => 'background: {value};',
                'selector'  => '.onp-sl-flat .onp-sl-linkedin .onp-sl-overlay-front, .onp-sl-flat .onp-sl-linkedin .onp-sl-overlay-back' 
            ),
            array(
                'css'       => 'border-bottom-color: {value|onp_smart_whiteout_color_15};',
                'selector'  => '.onp-sl-flat .onp-sl-linkedin .onp-sl-overlay-front' 
            ),
            array(
                'css'       => 'background: {value|onp_smart_blackout_color};',
                'selector'  => '.onp-sl-flat .onp-sl-linkedin .onp-sl-overlay-header' 
            )
        ),   
        // end button backgrounds

        // button text
        'button_cover_text_font' => array(
            array(
                'css' => array(
                    'font-family: {family|stripcslashes};',
                    'font-size: {size}px;',
                    'color: {color}; text-shadow:none;'
                ),
                'selector' => '.onp-sl-flat .onp-sl-overlay-text'
            ),
            array(
                'image' => array(
                    'function' => 'onp_sl_recolor',
                    'args' => array (
                        OPANDA_SR_PLUGIN_URL . '/assets/img/social-icons.png',     // an original image to recolor
                        '{color}',                                              // a color to use
                        array(                                                  // a set of options
                            'output' =>  OPANDA_SR_PLUGIN_DIR . '/assets/img/sr/',
                            'url' =>  OPANDA_SR_PLUGIN_URL . '/assets/img/sr/',        
                        )
                    )
                ),
                'css' => 'background-image: url("{image}");',
                'selector' => '.onp-sl-flat .onp-sl-control .onp-sl-overlay-icon'                
            )
        )
        // end button text
    );
    
    return $result;
}
