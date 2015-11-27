<?php
/**
 * The file contains the base class for all control holder
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The base class for control holders.
 * 
 * @since 1.0.0
 */
abstract class FactoryForms328_Holder extends FactoryForms328_FormElement {
    
    /**
     * Holder Elements.
     * 
     * @since 1.0.0
     * @var FactoryForms328_FormElement[] 
     */
    protected $elements = array();
    
    /**
     * Is this element a control holder?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isHolder = true;
    
    /**
     * Creates a new instance of control holder.
     * 
     * @since 1.0.0
     * @param mixed[] $options A holder options.
     * @param FactoryForms328_Form $form A parent form.
     */
    public function __construct($options, $form) {
        parent::__construct($options, $form);        
        $this->elements = $form->createElements( $options['items'] );
    }
    
    /**
     * Returns holder elements.
     * 
     * @since 1.0.0
     * @return FactoryForms328_FormElement[].
     */
    public function getElements() {
        return $this->elements;
    }
    
    /**
     * Renders the form or a given control holder.
     * 
     * @since 1.0.0
     * @param $holder A control holder to render.
     * @return void
     */
    function render() {
        
        $this->beforeRendering();
        
        $isFirstItem = true;

        foreach( $this->elements as $element ) {
            $element->setOption('isFirst', $isFirstItem);
            if ( $isFirstItem ) $isFirstItem = false;

            do_action('factory_form_before_element_' . $element->getOption('name') );
            
            // if a current item is a control holder
            if ( $element->isHolder ) {
                
                $this->form->layout->beforeHolder( $element );
                $element->render();
                $this->form->layout->afterHolder( $element );
                
            // if a current item is an input control
            } elseif ( $element->isControl ) {
                
                $this->form->layout->beforeControl( $element );
                $element->render();
                $this->form->layout->afterControl( $element );
                
            // if a current item is a custom form element
            } elseif ( $element->isCustom ) {
                
                $element->render();    
                
            // otherwise, show the error
            } else {
                print_r($element);
                echo( '[ERROR] Invalid item.' ); 
            }
            
            do_action('factory_form_after_element_' . $element->getOption('name') );
        } 
        
        $this->afterRendering();
    }

    /**
     * Rendering a beginning of a holder.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeRendering(){}
    
    /**
     * Rendering an end of a holder.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterRendering(){}
    
    /**
     * Rendering some html before an inner holder.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeInnerHolder(){}
    
    /**
     * Rendering some html after an inner holder.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterInnerHolder(){}
    

    protected function beforeInnerElement(){}
    
    /**
     * Rendering some html after an inner element.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterInnerElement(){} 
}