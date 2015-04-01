<?php
/**
 * Dropdown List Control
 * 
 * Main options:
 *  name            => a name of the control
 *  value           => a value to show in the control
 *  default         => a default value of the control if the "value" option is not specified
 *  items           => a callback to return items or an array of items to select
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

class FactoryForms328_GoogleFontControl extends FactoryForms328_FontControl 
{
    public $type = 'google-font';
    const APIKEY = 'AIzaSyB-3vazYv7Q-5QZA04bmSKFrWcw_VhC40w';

    public function __construct($options, $form, $provider = null) {
        parent::__construct($options, $form, $provider);
        $this->addCssClass('factory-font');
        
        $optionGoogleFontData = array(
            'name' => $this->options['name'] . '__google_font_data',
            'cssClass' => 'factory-google-font-data'
        );       
               
        $this->googleFontData = new FactoryForms328_HiddenControl( $optionGoogleFontData, $form, $provider );
        $this->innerControls[] = $this->googleFontData;
    }
    
    public function getDefaultFonts() {
        
        $googleFonts = $this->getGoogleFonts();

        $fonts = array(
            array( 'inherit', __( '(use default website font)', 'factory_forms_328' ) )
        );
        
        $fontsCommon = array( 'group', __('Standard:', 'factory_forms_328'), array(
            
            array( 'Arial, "Helvetica Neue", Helvetica, sans-serif', 'Arial' ),
            array( '"Helvetica Neue", Helvetica, Arial, sans-serif', 'Helvetica' ),
            array( 'Tahoma, Verdana, Segoe, sans-serif', 'Tahoma' ),
            array( 'Verdana, Geneva, sans-serif', 'Verdana' ),      
            
        ));       
        
        $fontsGoogleFonts = array( 'group', __('Google Fonts:', 'factory_forms_328'), array() );
        
        foreach( $googleFonts->items as $item ) {
            
            $altFont = $item->category;
            if ( in_array( $altFont, array( 'handwriting', 'display' ) ) ) $altFont = 'serif';
            
            $listItem = array(
                'title' => $item->family,
                'value' => $item->family . ', ' . $item->category,
                'hint' => '<em>Google Font</em>',
                'data' => array(
                    'google-font' => true,
                    'family' => $item->family,
                    'variants' => $item->variants,
                    'subsets' => $item->subsets
                )
            );
            
            $fontsGoogleFonts[2][] = $listItem;
        }
        
        $fonts[] = $fontsCommon;
        $fonts[] = $fontsGoogleFonts;

        set_transient('factory_google_fonts', $fonts, 60 * 60 * 6);
        return $fonts;
    }
    
    protected function getGoogleFonts() {
        
        $body = get_transient('factory_google_fonts_raw');
        if ( !empty( $body ) ) return $body;
        
        $response = wp_remote_get( sprintf( 'https://www.googleapis.com/webfonts/v1/webfonts?key=%s', self::APIKEY ) );
        
        $this->error = false;
        $this->defailedError = false;
                    
        if ( is_wp_error( $response ) ) {
            
            $this->error = __('Unable to retrieve the list of Google Fonts.', 'factory_forms_328');
            $this->defailedError = $response->get_error_message();
            return $body;
        }
            
        if ( !isset( $response['body'] ) ) {

            $this->error = __('Invalide response from the Google Fonts API.', 'factory_forms_328');
            $this->defailedError = $response['body'];
            return $body;
        }
            
        $body = json_decode( $response['body']);

        if ( empty( $body->items ) ) {
            
            $this->error = __('Unexpected error. The list of Google Fonts are empty.', 'factory_forms_328');
            return $body;
        }
        
        set_transient('factory_google_fonts_raw', $body, 60 * 60 * 6);
        return $body;
    }
    
    public function afterControlsHtml() {
        ?>
        <?php $this->googleFontData->html() ?>
        <?php
    }
}
