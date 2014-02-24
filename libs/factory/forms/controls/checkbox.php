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

class FactoryForms300_CheckboxControl extends FactoryForms300_Control 
{
    public $type = 'checkbox';
    
    public function getSubmitValue() {
        return isset($_POST[$this->getNameOnForm()]) ? 1 : 0;
    }
    
    /**
     * Preparing html attributes before rendering html of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        $value = $this->getValue();
        $nameOnForm = $this->getNameOnForm();
        
        if ( $value ) $this->addHtmlAttr('checked', 'checked');
        
        $this->addHtmlAttr('type', 'checkbox');  
        $this->addHtmlAttr('id', $nameOnForm);
        $this->addHtmlAttr('name', $nameOnForm);
        $this->addHtmlAttr('value', 1); 
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        ?>
        <input <?php $this->attrs() ?>/>
        <?php
    }
}
