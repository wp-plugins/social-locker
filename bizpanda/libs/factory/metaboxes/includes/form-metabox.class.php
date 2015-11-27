<?php
/**
 * The file contains a class for creating metaboxes with forms based on the Factory Forms module.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-metaboxes 
 * @since 1.0.0
 */

/**
 * A class extending FactoryMetaboxes_Metabox and adding ability to create and save forms.
 * 
 * @since 1.0.0
 */
abstract class FactoryMetaboxes321_FormMetabox extends FactoryMetaboxes321_Metabox {

    /**
     * A scope of metadata. By default the current class name used.
     * 
     * @since 1.0.0
     * @var string
     */
    public $scope;
    
    /**
     * CSS class that addes to the form.
     * 
     * @since 3.0.6
     * @var string 
     */
    public $cssClass;
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        $this->scope = ( !$this->scope ) ? $this->formatCamelCase( get_class($this) ) : $this->scope;
    }
    
    private function getForm( $post_id = null ) {
        
        // creating a value provider
        $this->provider = new FactoryForms328_MetaValueProvider( array(
            'scope' => $this->scope                            
        ));
        $this->provider->init( $post_id );

        // creating a form
        $form = new FactoryForms328_Form( array(
            'scope' => $this->scope,
            'name' => $this->id
        ), $this->plugin );

        $form->setProvider( $this->provider );

        $this->form( $form ); 
        return $form;
    }
    
    /**
     * Renders a form.
     */
    public function html() { 
        
        $form = $this->getForm();
        
        echo '<div class="factory-form-metabox">';
        $this->beforeForm( $form );
        $form->html( array(
            'cssClass' => $this->cssClass
        ));
        $this->afterForm( $form );
        echo '</div>';
    }
    
    public function save( $post_id ) {
        
        $form = $this->getForm( $post_id ); 
        $this->onSavingForm( $post_id );
        
        $form->save();
    }
    
    /**
     * Extra custom actions after the form is saved.
     */
    public function onSavingForm( $post_id ) {
        return;
    }
    
    /**
     * Form method that must be overridden in the derived classes.
     */
    public abstract function form($form);
    
    /**
     * Method executed before rendering the form.
     */
    public function beforeForm(FactoryForms328_Form $form) {
        return;
    }
    
    /**
     * Method executed after rendering the form.
     */
    public function afterForm(FactoryForms328_Form $form) {
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