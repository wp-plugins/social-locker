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

class FactoryForms307_FontControl extends FactoryForms307_ComplexControl 
{
    public $type = 'font';
    
    public function __construct($options, $form, $provider = null) {
        parent::__construct($options, $form, $provider);
        
        $optionFontSize = array(
            'name' => $this->options['name'] . '__size',
            'units' => $this->options['units'],
            'default' => isset( $this->options['default'] ) ? $this->options['default'][0] : null            
        );
            
        $fonts = array(
                
            // sans-serif
            array( 'inherit', __( '(use website font)', 'sociallocker' ) ),
            array( 'Arial, "Helvetica Neue", Helvetica, sans-serif', 'Arial, Helvetica Neue, Helvetica' ),
            array( '"Helvetica Neue", Helvetica, Arial, sans-serif', 'Helvetica Neue, Helvetica, Arial' ),   
            array( 'Tahoma, Geneva, Verdana, sans-serif', 'Tahoma, Geneva, Verdana' ),
            array( 'Geneva, Tahoma, Verdana, sans-serif', 'Geneva, Tahoma, Verdana' ), 
            array( '"Segoe UI", Segoe, Tahoma, Geneva, sans-serif', 'Segoe UI, Segoe, Tahoma, Geneva' ),
            array( 'Optima, Segoe, "Segoe UI", Candara, Calibri, Arial, sans-serif;', 'Optima, Segoe, Candara, Calibri' ),                
            array( 'Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;', 'Calibri, Candara, Segoe' ),
            array( '"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif', 'Lucida Grande, Lucida Sans, Geneva' ),
            array( '"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;', 'Trebuchet MS, Lucida Grande, Tahoma' ),  
            array( '"Gill Sans", "Gill Sans MT, Calibri", sans-serif;', 'Gill Sans, Gill Sans MT, Calibri' ),   

            // serif
            array( 'Cambria, Georgia, serif', 'Cambria, Georgia' ), 
            array( 'Georgia, Cambria, "Times New Roman", Times, serif', 'Georgia, Cambria, Times New Roman' ),
            array( '"Lucida Bright", Georgia, serif', 'Lucida Bright, Georgia' ),
            array( 'Didot, "Didot LT STD", "Hoefler Text", Garamond, "Times New Roman", serif', 'Didot, Hoefler Text, Garamond' ), 
            array( '"Goudy Old Style", Garamond, "Big Caslon", "Times New Roman", serif', 'Goudy Old Style, Garamond, Big Caslon' ),   
            array( 'Baskerville, "Baskerville old face", "Hoefler Text", Garamond, "Times New Roman", serif;', 'Baskerville, Hoefler, Garamond' ),
            array( '"Big Caslon", "Book Antiqua", "Palatino Linotype", Georgia, serif', 'Big Caslon, Book Antiqua, Palatino Linotype' ), 
            array( '"Bodoni MT", Didot, "Didot LT STD", "Hoefler Text", Garamond, "Times New Roman", serif', 'Bodoni MT, Didot, Hoefler Text' ),
            array( 'Palatino, "Palatino Linotype", "Palatino LT STD", "Book Antiqua", Georgia, serif', 'Palatino, Book Antiqua, Georgia' ),
        );
        
        $fonts = apply_filters('factory_forms_307_fonts', $fonts);
        $fonts = apply_filters('factory_forms_307_fonts-' . $this->options['name'], $fonts);
        
        $optionFontFamily = array(
            'name' => $this->options['name'] . '__family',
            'data' => $fonts,
            'default' => isset( $this->options['default'] ) ? $this->options['default'][1] : null            
        );        
        
        $optionFontColor = array(
            'name' => $this->options['name'] . '__color',           
            'default' => isset( $this->options['default'] ) ? $this->options['default'][2] : null,
            'pickerTarget' => '.factory-control-' . $this->options['name'] . ' .factory-picker-target'
        );
        
        $this->size = new FactoryForms307_IntegerControl( $optionFontSize, $form, $provider );
        $this->family = new FactoryForms307_DropdownControl( $optionFontFamily, $form, $provider );
        $this->color = new FactoryForms307_ColorControl( $optionFontColor, $form, $provider );
        
        $this->innerControls = array( $this->family, $this->size, $this->color );   
    }
    
    /**
     * Fixes the font value as it can contains \".
     * 
     * @since 1.0.0
     * @return mixed
     */
    public function getSubmitValue() {
        $values = parent::getSubmitValue();
        $values[0] = stripcslashes( $values[0] );
        return $values;
    }
     
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {           
    ?>         
     <div <?php $this->attrs() ?>>
        <div class="factory-control-row">     
             <div class="factory-family-wrap">
                    <?php $this->family->html() ?>
             </div>  
             <div class="factory-size-wrap">
                    <?php $this->size->html() ?>
             </div>   
             <div class="factory-color-wrap">
                    <?php $this->color->html() ?>
             </div>
        </div>
        <div class="factory-picker-target"></div>
     </div>
   <?php
    }
}
