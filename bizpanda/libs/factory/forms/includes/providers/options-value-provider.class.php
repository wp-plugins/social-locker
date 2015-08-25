<?php
/**
 * The file contains the class of Factory Option Value Provider.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Factory Options Value Provider
 * 
 * This provide stores form values in the wordpress options.
 * 
 * @since 1.0.0
 */
class FactoryForms328_OptionsValueProvider implements IFactoryForms328_ValueProvider 
{
    /**
     * Values to save $optionName => $optionValue
     * 
     * @since 1.0.0
     * @var mixed[]
     */
    private $values = array();

    /**
     * A prefix that will be added to all option names.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $scope;
    
    /**
     * Creates a new instance of an options value provider.
     */
    public function __construct( $options = array() ) {
        $this->scope = ( isset( $options['scope'] ) ) ? $options['scope'] : null;
    }
    
    /**
     * @since 1.0.0
     */
    public function init() {
        // nothing to do
    }

    /**
     * @since 1.0.0
     */
    public function saveChanges() {
        // nothing to do
    }
    
    public function getValue($name, $default = null, $multiple = false ) {
        $name = ( !empty( $this->scope ) ) ? $this->scope . '_' . $name : $name;
        $value = get_option($name, $default);
        
        if ($value === 'true') $value = 1;
        if ($value === 'false') $value = 0;
        
        return $value;
    }
    
    public function setValue($name, $value) {
        $name = ( !empty( $this->scope ) ) ? $this->scope . '_' . $name : $name;
        $value = empty( $value ) ? $value : stripslashes ( $value );
        update_option($name, $value);
    }
}