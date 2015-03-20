<?php
/**
 * Html Helper is to render form elements independently.
 * 
 * Factory Forms is a Factory module that provides a declarative
 * way to build forms without any extra html or css markup.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * A class that provides a set of methods to render form elements independently.
 * 
 * @since 1.0.0
 */
class FactoryForms328_FormHelpers {
    
    /**
     * Renders a form element.
     * 
     * @since 1.0.0
     * @param type $type A type of a form element.
     * @param type $options Element options.
     * @return void
     */
    public static function render( $type, $options = array() ) {
        $options['type'] = $type;
        
        if ( FactoryForms328_Form::isControl($type) ) {
            self::renderControl($type, $options);
        } elseif ( FactoryForms328_Form::isControlHolder($type) ) {
            self::renderHolder($type, $options);
        } else {
            print_r($options);
            die('The control type was not found: ' . $type );
        }
    }
    
    /**
     * Renders a given control.
     * 
     * @since 1.0.0
     * @param type $type A control type.
     * @param type $options Control options.
     * @return void
     */
    public static function renderControl($type, $options) {
        FactoryForms328_Form::connectAssetsForItem( $options );
        
        $data = self::$_registeredControls[$type];
        require_once ($data['include']);
        $object = new $data['class']( $options );   
        $object->html();
    }
    
    /**
     * Renders a given control holder.
     * 
     * @since 1.0.0
     * @param type $type A holder type.
     * @param type $options Holder options.
     * @return void
     */
    public static function renderHolder($type, $options) {
        FactoryForms328_Form::connectAssetsForItem( $options );
        
        $data = self::$_registeredHolders[$type];
        require_once ($data['include']);
        $object = new $data['class']( $options );   
        $object->html();
    }
    
    /**
     * A helper method to extract control options (items) by a given name.
     * 
     * @since 3.0.5
     * @param string $controlName a control name to search
     * @param mixed $options a set of contol options
     * @return null|string 
     */
    public static function extractControlOptions( $controlName, $options = array() ) {
              
        foreach($options as $itemOptions) {

            if ( isset( $itemOptions['name'] ) && $itemOptions['name'] == $controlName ) {
                return $itemOptions;
            }
            
            if ( isset( $itemOptions['items'] ) && is_array( $itemOptions['items'] ) ) {
                $result = self::extractControlOptions( $controlName, $itemOptions['items'] );
                if ( $result ) return $result;
            }
        }
        
        return null;        
    }
}