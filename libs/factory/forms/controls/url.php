<?php
/**
 * Url Control
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

class FactoryForms305_UrlControl extends FactoryForms305_TextboxControl 
{
    public $type = 'url';
    
    /**
     * Adding 'http://' to the url if it was missed.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getSubmitValue() {
        $value = parent::getSubmitValue();
        if ( !empty( $value ) && substr($value, 0, 4) != 'http' ) $value = 'http://' . $value;
        return $value;
    }
}
