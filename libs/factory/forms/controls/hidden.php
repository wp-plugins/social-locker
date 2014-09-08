<?php
/**
 * Hidden Input Control
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

class FactoryForms324_HiddenControl extends FactoryForms324_Control 
{
    public $type = 'hidden';
    
    /**
     * Preparing html attributes before rendering html of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        $value = htmlspecialchars ( $this->getValue() );
        $nameOnForm = $this->getNameOnForm();

        $this->addHtmlAttr('id', $nameOnForm);
        $this->addHtmlAttr('name', $nameOnForm);
        $this->addHtmlAttr('value', $value); 
        $this->addHtmlAttr('type', 'hidden'); 
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
