<?php
/**
 * Color
 * 
 * Main options:
 *  name            => a name of the control
 *  value           => a value to show in the control
 *  default         => a default value of the control if the "value" option is not specified
 * 
 * @author Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

class FactoryForms328_ColorControl extends FactoryForms328_Control 
{
    public $type = 'color';
        
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        $name = $this->getNameOnForm();
        $value = $this->getValue();  
       
        if ( !$value ) $value = '#1e8cbe';
        
        
        // the "pickerTarget" options allows to select element where the palette will be shown
        $pickerTarget = $this->getOption('pickerTarget');
        if ( !empty( $pickerTarget ) ) $this->addHtmlData('picker-target', $pickerTarget);
        
        ?>           
        <div <?php $this->attrs() ?>>
            <div class="factory-background" <?php echo (!empty($value) ? 'style="background:'.$value.';"' : '' ); ?>></div>
            <div class="factory-pattern"></div>
            <input type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="factory-input-text factory-color-hex" value="<?php echo $value; ?>">
        </div>
 <?php
    }
}
