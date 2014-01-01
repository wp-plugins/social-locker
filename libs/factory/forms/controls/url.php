<?php
/**
 * Url Control
 * 
 * Main options:
 *  @see FactoryForms300_TextboxControl
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms300_UrlControl extends FactoryForms300_TextboxControl 
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
