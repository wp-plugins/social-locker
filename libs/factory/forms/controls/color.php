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

class FactoryForms307_ColorControl extends FactoryForms307_Control 
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
        $values = $this->getValue();  
       
        // the "pickerTarget" options allows to select element where the palette will be shown
        $pickerTarget = $this->getOption('pickerTarget');
        if ( !empty( $pickerTarget ) ) $this->addHtmlData('picker-target', $pickerTarget);
        
        ?>           
        <div <?php $this->attrs() ?>>
            <div class="factory-preview">
                <div class="factory-background" <?php echo (!empty($values) ? 'style="background:'.$values.';"' : '' ); ?>></div>
            </div>
            <input type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="factory-input-text factory-color-hex" value="<?php echo $values; ?>">
        </div>
 <?php
    }
}
