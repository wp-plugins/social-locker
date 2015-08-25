<?php
/**
 * The file contains the base class for all complex controls.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The base class for all controls.
 * 
 * @since 1.0.0
 */
abstract class FactoryForms328_ComplexControl extends FactoryForms328_Control {
    
    /**
     * Is this element a complex control?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isComplexControl = true;
    
    /**
     * Contains a set of internal controls.
     * 
     * @since 1.0.0
     * @var FactoryForms328_Control[]
     */
    public $innerControls = array();
    
    /**
     * Sets a provider for the control.
     * 
     * @since 1.0.0
     * @param IFactoryForms328_ValueProvider $provider
     * @return void
     */
    public function setProvider( $provider ) {
        $this->provider = $provider;
        
        foreach( $this->innerControls as $control ) {
            $control->setProvider( $provider );
        }
    }
    
    /**
     * Returns a control name used to save data with a provider.
     * 
     * The method can return if the control have several elements.
     * 
     * @since 1.0.0
     * @return string[]|string|null A control name.
     */
    public function getName() 
    {
        $names = array();
        
        foreach( $this->innerControls as $control ) {
            $innerNames = $control->getName();
            if ( is_array($innerNames) ) $names = array_merge($names, $innerNames );
            else $names[] = $innerNames;
        }
        
        return $names;
    }
    
    /**
     * Returns an array of value to save received after submission of a form.
     * 
     * @see getSubmitValue
     * 
     * The array has the following format:
     * array(
     *    'control-name1' => 'value1',
     *    'control-name2__sub-name1' => 'value2'
     *    'control-name2__sub-name2' => 'value3'
     * )
     * 
     * @since 3.1.0
     * @return mixed[]
     */
    public function getValuesToSave() {
        $values = array();
        
        foreach( $this->innerControls as $control ) {
            $innerValues = $control->getValuesToSave();
            if ( is_array($innerValues) ) $values = array_merge( $values, $innerValues );
            else $values[] = $innerValues;
        }
        
        return $values;
    }
    
    /**
     * Returns an initial value of control that is used to render the control first time.
     * 
     * @since 1.0.0
     * @return mixed;
     */
    public function getValue( $index = null, $multiple = false ) {
        
        $values = array();
        foreach( $this->innerControls as $control ) {
            $innerValues = array_merge($values, $control->getValue() );
            if ( is_array($innerValues) ) $values = array_merge($values, $innerValues );
            else $values[] = $innerValues;
        }
        
        if ( $index !== null ) { return $values[$index]; } 
        else {  return $values; }
    }
   
}