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
abstract class FactoryMetaboxes300_FormMetabox extends FactoryMetaboxes300_Metabox {

    /**
     * A scope of metadata. By default the current class name used.
     * 
     * @since 1.0.0
     * @var string
     */
    public $scope;
    
    public function __construct() {
        parent::__construct();
        $this->scope = ( !$this->scope ) ? $this->formatCamelCase( get_class($this) ) : $this->scope;
    }
    
    private function getForm( $post_id = null ) {
        
        // creating a value provider
        $this->provider = new FactoryForms300_MetaValueProvider( array(
            'scope' => $this->scope
        ));
        $this->provider->init( $post_id );
        
        // creating a form
        $form = new FactoryForms300_Form( array(
            'scope' => $this->scope
        ));
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
        $form->html();
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
    public function beforeForm(FactoryForms300_Form $form) {
        return;
    }
    
    /**
     * Method executed after rendering the form.
     */
    public function afterForm(FactoryForms300_Form $form) {
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