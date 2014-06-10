<?php
/**
 * Checkbox Control
 * 
 * Main options:
 *  name            => a name of the control
 *  value           => a value to show in the control
 *  default         => a default value of the control if the "value" option is not specified
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms320_CheckboxControl extends FactoryForms320_Control 
{
    public $type = 'checkbox';
    
    public function getSubmitValue( $name, $subName ) {
        $nameOnForm = $this->getNameOnForm( $name );
        return isset($_POST[$nameOnForm]) && $_POST[$nameOnForm] != 0 ? 1 : 0;
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        if ( 'buttons' == $this->getOption('way') ) {
            $this->buttonsHtml();
        } else {
            $this->defaultHtml();
        }
    }
    
    /**
     * Shows the Buttons Checkbox.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function buttonsHtml() {
        $value = $this->getValue();
        $nameOnForm = $this->getNameOnForm();
        
        $this->addCssClass('factory-buttons-way');
        $this->addCssClass('btn-group');        
        
        ?>
        <div <?php $this->attrs() ?>>
            <button type="button" class="btn btn-default btn-small btn-sm factory-on <?php if ( $value ) { echo 'active'; } ?>"><?php _e('On', 'factory_forms_320') ?></button>
            <button type="button" class="btn btn-default btn-small btn-sm factory-off <?php if ( !$value ) { echo 'active'; } ?>" data-value="0"><?php _e('Off', 'factory_forms_320') ?></button>
            <input type="checkbox" style="display: none" id="<?php echo $nameOnForm ?>" class="factory-result" name="<?php echo $nameOnForm ?>" value="1" <?php if ( $value ) { echo 'checked="checked"'; } ?>" />
        </div>
        <?php
    }
    
    /**
     * Shows the standart checkbox.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function defaultHtml() {
        $value = $this->getValue();
        $nameOnForm = $this->getNameOnForm();
        
        $this->addHtmlAttr('type', 'checkbox');  
        $this->addHtmlAttr('id', $nameOnForm);
        $this->addHtmlAttr('name', $nameOnForm);
        $this->addHtmlAttr('value', $value);
        
        if ( $value ) $this->addHtmlAttr('checked', 'checked');
        $this->addCssClass('factory-default-way');
        
        ?> 
        <input <?php $this->attrs() ?>/>
        <?php
    }
}
