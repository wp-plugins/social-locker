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
abstract class FactoryForms305_CustomElement extends FactoryForms305_FormElement {
    
    /**
     * Is this element a custom form element?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isCustom = true;
    
    public function render() {
        $this->html();
    }
}