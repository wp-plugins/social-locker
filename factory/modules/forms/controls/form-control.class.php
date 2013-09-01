<?php

abstract class FactoryFormPR108Control {
    
    /**
     * A type of a control.
     * @var boolean 
     */
    public $type = null;
    
    /**
     * A type of an item (used to render form correctly).
     * @var boolean 
     */
    public $itemType = 'control';
    
    /**
     * Current factory.
     * @var FactoryPlugin 
     */
    public $plugin;
    
    /**
     * Control properties
     * @var array 
     */
    public $props;
    
    /**
     * Provider used to get values.
     * @var type 
     */
    public $provider;
    
    public $classes = array();
    
    public function __construct( FactoryPR108Plugin $plugin ) {
        
        if ($this->type == null) 
            throw new Exception('The control must have the defined type propery.');
        
        $this->plugin = $plugin;
    }
    
    /** 
     * Setups control properties and provider.
     * @param type $properties
     * @param IPFactoryValueProvider $provider
     */
    public function setup( &$properties, IFactoryPR108ValueProvider $provider ) {
        
        $this->props = $properties;
        $this->provider = $provider; 
        
        if ( !empty( $properties['class'] )) {
            foreach( explode( $properties['class'], ' ') as $class ) {
                $this->classes[] = $class;
            }
        }
    }
    
    /**
     * Returns control name used to save data by provider.
     */
    public function getName() 
    {
        return $this->props['name'];
    }
        
    /**
     * Renders the control using control's info and value got from a value provider.
     */
    public abstract function render();
    
    /**
     * Returns result's value.
     */
    public function getValue( $name ) {   
        $fullname = ( !empty( $this->props['scope'] )) ? $this->props['scope'] . '_' . $name : $name;
        return isset( $_POST[$fullname] ) ? $_POST[$fullname] : null;
    }
    
    /**
     * Returns set of classses that must be applied to the control.
     * @return string 'class1 class2 class3' or empty string ''
     */
    protected function getClasses() {
        return implode(' ', $this->classes);
    }
    
    /**
     * Adds class to the classes' set.
     * @param type $className
     */
    protected function addClass( $className ) {
        if ( empty($className) ) return;
        if ( !in_array($className, $this->classes )) $this->classes[] = $className;
    }
}