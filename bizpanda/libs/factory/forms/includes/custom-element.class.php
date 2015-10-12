<?php
/**
 * The file contains the base class for all custom elements.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The base class for all controls.
 * 
 * @since 1.0.0
 */
abstract class FactoryForms328_CustomElement extends FactoryForms328_FormElement {
    
    /**
     * Is this element a custom form element?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isCustom = true;
    
    public function render() {
        
        // if the control is off, then ignore it
        $off = $this->getOption('off', false);
        if ( $off ) return;
        
        $this->html();
    }
}