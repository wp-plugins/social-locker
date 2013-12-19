<?php

abstract class FactoryFormFR110Metabox extends FactoryFR110Metabox {

    /**
     * Value provider for the metabox form.
     * @var IFactoryValueProvider
     */
    private $valueProvider;
    
    /**
     * Scope of metadata. By default the current class name used.
     * @var string
     */
    public $scope;
    
    public function __construct( $plugin = null, $valueProvider = null ) {
        parent::__construct($plugin);
        
        $this->valueProvider = $valueProvider ? $valueProvider : new FactoryFR110MetaValueProvider();
        $this->scope = ( !$this->scope ) ? get_class($this) : $this->scope;
    }
    
    private function getForm( $post_id = null ) {
        
        $this->valueProvider->init( $this->scope, $post_id );
        $form = new FactoryFormFR110( $this->plugin, $this->valueProvider );
        $this->form( $form );
        if ( !$form->scope ) $form->scope = $this->formatCamelCase( $this->scope );
              
        return $form;
    }
    
    /**
     * Renders a form.
     */
    public function render() { 
        
        $form = $this->getForm();  
        
        $this->beforeForm( $form );
        $form->render();
        $this->afterForm( $form );
    }
    
    public function save( $post_id ) {
        
        $form = $this->getForm( $post_id ); 
        $controls = $form->getControls();    

        foreach($controls as $control) {
            
            $names = $control->getName();

            // the control has plural names
            if (gettype($names) == 'array'){
                foreach($names as $name) {
                    $value = $control->getValue($name);
                    $this->valueProvider->setValue($name, $value);
                }
               
            // the control has a singular name
            } else {
                $name = $names;
                $value = $control->getValue($name);
                $this->valueProvider->setValue($name, $value);
            }
        }
        
        $this->valueProvider->saveChanges();
        $this->saveForm( $post_id );
    }
    
    /**
     * Extra custom actions after the form is saved.
     */
    public function saveForm( $post_id ) {
        return;
    }
    
    /**
     * Form method that must be overridden in the derived classes.
     */
    public abstract function form(FactoryFormFR110 $form);
    
    /**
     * Method executed before rendering the form.
     */
    public function beforeForm(FactoryFormFR110 $form) {
        return;
    }
    
    /**
     * Method executed after rendering the form.
     */
    public function afterForm(FactoryFormFR110 $form) {
        return;
    }
        
    private function formatCamelCase( $string ) {
        $output = "";
        foreach( str_split( $string ) as $char ) {
                strtoupper( $char ) == $char and $output and $output .= "_";
                $output .= $char;
        }
        $output = strtolower($output);
        return $output;
    }  
}