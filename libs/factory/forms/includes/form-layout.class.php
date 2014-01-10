<?php
/**
 * The file contains the base class for all form layouts.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The base class for all form layouts. 
 */
abstract class FactoryForms300_FormLayout extends FactoryForms300_ControlHolder {
    
    /**
     * A form layout name.
     * 
     * @since 1.0.0
     * @var string 
     */
    protected $name = 'default';
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string 
     */
    protected $type = 'form-layout';
    
    /**
     * Creates a new instance of a form layout.
     * 
     * @since 1.0.0
     * @param mixed[] $options A holder options.
     * @param FactoryForms300_Form $form A parent form.
     */
    public function __construct($options, $form) {

        $options['name'] = $this->name;
        $options['items'] = $form->getItems();
        
        parent::__construct($options, $form);

        $this->addCssClass('factory-forms-300-' . $this->type);  
        $this->addCssClass('factory-forms-300-' . $this->name);
    }
    
    /**
     * Renders a beginning of a form.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeRendering() {
        echo '<div '; $this->attrs(); echo '>';
    }
    
    /**
     * Renders the end of a form.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterRendering() {
        echo '</div>';
    }
    
    /**
     * Rendering some html before a holder.
     * 
     * @since 1.0.0
     * @return void
     */ 
    protected function beforeHolder( $element ) {}
    
    /**
     * Rendering some html after a holder.
     * 
     * @since 1.0.0
     * @return void
     */ 
    protected function afterHolder( $element ) {}
    
    /**
     * Rendering some html before a contol.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeControl( $element ) {}
    
    /**
     * Rendering some html after a contol.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function afterControl( $element ) {}
}