<?php
/**
 * Textarea Control
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

class FactoryForms328_TextareaControl extends FactoryForms328_Control 
{
    public $type = 'textarea';
    
    
    /**
     * Preparing html attributes before rendering html of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        $nameOnForm = $this->getNameOnForm();

        $this->addCssClass('form-control');
        $this->addHtmlAttr('name', $nameOnForm);
        $this->addHtmlAttr('id', $nameOnForm);
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        $value = htmlspecialchars ( $this->getValue() );
        
        ?>
        <textarea <?php $this->attrs(); ?> /><?php echo $value ?></textarea>
        <?php
    }
}
