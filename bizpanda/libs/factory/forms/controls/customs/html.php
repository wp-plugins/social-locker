<?php
/**
 * Html Markup
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms328_Html extends FactoryForms328_CustomElement
{
    public $type = 'html';
    
    /**
     * Shows the html markup of the element.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        $html = $this->getOption('html', '');

        // if the data options is a valid callback for an object method
        if (
            ( is_array($html) && 
            count($html) == 2 && 
            gettype($html[0]) == 'object' ) || function_exists( $html ) ) {
            
            call_user_func($html);
            return;
        } 
        
        // if the data options is an array of values
        echo $html;
    }
}
