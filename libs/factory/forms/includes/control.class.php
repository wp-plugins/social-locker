<?php
/**
 * The file contains the base class for all controls.
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
abstract class FactoryForms300_Control extends FactoryForms300_FormElement {
    
    /**
     * Is this element a control?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isControl = true;
    
    /**
     * A provider that is used to get values.
     * 
     * @since 1.0.0
     * @var IFactoryValueProvider 
     */
    protected $provider = null;
    
    /**
     * Create a new instance of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct( $options, $form, $provider = null ) {
        parent::__construct( $options, $form );
        $this->provider = $provider;
    }
    
    /**
     * Sets a provider for the control.
     * 
     * @since 1.0.0
     * @param IFactoryForms300_ValueProvider $provider
     * @return void
     */
    public function setProvider( $provider ) {
        $this->provider = $provider;
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
        return isset( $this->options['name'] ) ? $this->options['name'] : null;
    }
    
    /**
     * Prints a control name used to save data with a provider.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function printName() {
        $name = $this->getName();
        if ( is_array( $name ) ) echo $name[0];
        else echo $name;
    }
    
    /**
     * Returns a control scope.
     * 
     * @since 1.0.0
     * @return string|null A control scope.
     */
    public function getScope() 
    {
        return isset( $this->options['scope'] ) ? $this->options['scope'] : null;
    }
    
    /**
     * Prints a control scope.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function printScope() {
        echo $this->getScope();
    }
    
    /**
     * Returns a name of control on a form (scope + _ + name)
     * 
     * @since 1.0.0
     * @return string|null A control name on a form.
     */
    public function getNameOnForm() 
    {
        $scope = $this->getScope();
        $name = $this->getName();
        
        if ( is_array( $name ) ) {
            $names = array();
            foreach( $name as $item ) {
                $names[] = empty($scope) ? $item : $scope . '_' . $item;
            } 
            return $names;
            
        }

        if ( empty($scope) ) return $name;
        if ( empty($name) ) return null;
        return $scope . '_' . $name;
    }
    
    /**
     * Prints a control name on a form.
     * 
     * @since 1.0.0
     * @return void
     */
    public function printNameOnForm() {
        $name = $this->getNameOnForm();
        if ( is_array( $name ) ) echo $name[0];
        else echo $name;
    }
    
    /**
     * Returns a value of the control got after submitting a form.
     * 
     * @since 1.0.0
     * @return mixed;
     */
    public function getSubmitValue() {
        $nameOnForm = $this->getNameOnForm();
        
        if (is_array($nameOnForm)) {
            $values = array();
            foreach($nameOnForm as $item) {
                $values[] = isset( $_POST[$item] ) ? $_POST[$item] : null;
            }
            return $values; 
        }
        
        return isset( $_POST[$nameOnForm] ) ? $_POST[$nameOnForm] : null;
    }
    
    /**
     * Returns an initial value of control that is used to render the control first time.
     * 
     * @since 1.0.0
     * @return mixed;
     */
    public function getValue( $index = null ) {
        
        if ( isset( $this->options['value'] ) ) {
            if ( is_array( $this->options['value'] ) ) {
                if ( $index !== null ) return $this->options['value'][$index];
                else return $this->options['value'];
            } else {
               return $this->options['value'];
            }
        } 

        $default = null;
        if ( isset( $this->options['default'] ) ) {
            if ( is_array( $this->options['default'] ) ) {
                if ( $index !== null ) $default = $this->options['default'][$index];
                else $default = $this->options['default'];
            } else {
               $default = $this->options['default'];
            }
        } 
        
        if ( $this->provider ) {
            $value = $this->provider->getValue( $this->getName(), $default );
            if ( is_array($value) && $index !== null ) return $value[$index];
            return $value;
        }
        
        return null;
    }
    
    /**
     * Shows the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function render() {
        $this->addCssClass('factory-from-control-' . $this->type);
        
        $this->beforeHtml();
        $this->html();
        $this->afterHtml(); 
    }
    
    /**
     * A virtual method that is executed before rendering html markup of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml(){}
    
    /**
     * A virtual method that is executed after rendering html markup of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterHtml(){}
    
    /**
     * Renders the html markup for the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public abstract function html();
    
    /**
     * Returns a layout option.
     * 
     * @since 1.0.0
     * @param type $optionName A layout option to return.
     * @param type $default A default value to return if the option doesn't exist.
     * @return mixed
     */
    public function getLayoutOption( $optionName, $default ) {
        if ( !isset( $this->options['layout'] ) ) return $default;
        if ( !isset( $this->options['layout'][$optionName] ) ) return $default;
        return $this->options['layout'][$optionName];
    }
}