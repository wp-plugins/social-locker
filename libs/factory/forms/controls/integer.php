<?php
/**
 * Integer Input Control
 * 
 * Main options:
 *  @see FactoryForms305_TextboxControl
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms305_IntegerControl extends FactoryForms305_TextboxControl 
{
    public $type = 'integer';
    
    /**
     * Converting string to integer.
     * 
     * @since 1.0.0
     * @return integer
     */
    public function getSubmitValue() {
        $value = parent::getSubmitValue();
        if ( $value == '' || $value == 'null' ) return null;
        return intval($value);
    }
}
