<?php
/**
 * The file contains the base class for all form element (controls, holders).
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * The base class for all form element (controls, holders).
 * 
 * Provides several methods to build html markup of an element.
 * 
 * @since 1.0.0
 */
abstract class FactoryForms328_FormElement {
    
    /**
     * A type of an elemnt.
     * 
     * @since 1.0.0
     * @var boolean 
     */
    protected $type = null;
    
    /**
     * An html attribute builder.
     * 
     * @since 1.0.0
     * @var FactoryForms328_HtmlAttributeBuilder 
     */
    private $htmlBuilder;
    
    /**
     * Element options.
     * 
     * @since 1.0.0
     * @var array 
     */
    public $options = array();
    
    /**
     * A parent form.
     * 
     * @since 1.0.0
     * @var FactoryForms328_Form 
     */
    protected $form;
    
    /**
     * A form layout.
     * 
     * @since 1.0.0
     * @var FactoryForms328_FormLayout 
     */
    protected $layout;
    
    /**
     * Is this element a control?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isControl = false;
    
    /**
     * Is this element a control holder?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isHolder = false;
    
    /**
     * Is this element a custom form element?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isCustom = false;
    
    /**
     * Creates a new instance of a form element.
     * 
     * @since 1.0.0
     * @param mixed[] $options A holder options.
     * @param FactoryForms328_Form $form A parent form.
     */
    public function __construct( $options, $form ) {
        $this->options = $options;
        $this->form = $form;
        $this->layout = $form->layout;
        
        $this->htmlBuilder = new FactoryForms328_HtmlAttributeBuilder();
        
        if ( isset( $this->options['cssClass']) ) {
            $this->htmlBuilder->addCssClass( $this->options['cssClass'] );
        }
        
        if ( isset( $this->options['htmlData'] ) ) {
            foreach($this->options['htmlData'] as $dataKey => $dataValue) {
                $this->htmlBuilder->addHtmlData($dataKey, $dataValue);
            }
        }
        
        if ( isset( $this->options['htmlAttrs'] ) ) {
            foreach($this->options['htmlAttrs'] as $attrKey => $attrValue) {
                $this->htmlBuilder->addHtmlAttr($attrKey, $attrValue);
            }
        }
        
        $this->addCssClass('factory-' . $this->type);
    }
    
    
    /**
     * Sets options for the control.
     * 
     * @since 1.0.0
     * @param mixed[] $options
     * @return void
     */
    public function setOptions( $options ) {
        $this->options = $options;
    }
    
    /**
     * Gets options of the control.
     * 
     * @since 1.0.0
     * @return mixed[] $options
     */
    public function getOptions() {
        return $this->options;
    }
    
    /**
     * Sets a new value for a given option.
     * 
     * @since 1.0.0
     * @param type $name An option name to set.
     * @param type $value A value to set.
     * @return void
     */
    public function setOption( $name, $value ) {
        $this->options[$name] = $value;
    }
    
    /**
     * Gets an option value or default.
     * 
     * @since 1.0.0
     * @param mixed $name An option name to get.
     * @param mixed $default A default value/
     * @return mixed
     */
    public function getOption( $name, $default = null ) {
        return isset( $this->options[$name] ) ? $this->options[$name] : $default;
    }
    
    /**
     * Prints an option value or default.
     * 
     * @since 1.0.0
     * @param mixed $name An option name to get.
     * @param mixed $default A default value/
     * @return void
     */
    public function option( $name, $default = null ) {
        $value = $this->getOption($name, $default);
        echo $value;
    }

    /**
     * Adds a new CSS class for the element.
     * 
     * @since 1.0.0
     * @return void
     */
    public function addCssClass( $class ) {
        $this->htmlBuilder->addCssClass( $class );
    }
    
    /**
     * Prints CSS classes of the element.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function cssClass() {
        $this->htmlBuilder->printCssClass();
    }
    
    /**
     * Adds a new html attribute.
     * 
     * @since 1.0.0
     * @param type $attrName
     * @param type $attrValue
     * @return void
     */
    protected function addHtmlData( $dataKey, $dataValue ) {
        return $this->htmlBuilder->addHtmlData( $dataKey, $dataValue );
    }

    /**
     * Adds a new html attribute.
     * 
     * @since 1.0.0
     * @param type $attrName
     * @param type $attrValue
     * @return void
     */
    protected function addHtmlAttr( $attrName, $attrValue ) {
        return $this->htmlBuilder->addHtmlAttr( $attrName, $attrValue );
    }
    
    /**
     * Prints all html attributes, including css classes and data.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function attrs() {
        return $this->htmlBuilder->printAttrs();
    }
    
    /**
     * Returns an element title.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getTitle() {
        if ( isset( $this->options['title'] ) ) return $this->options['title'];
        return false;
    }
    
    /**
     * Returns true if an element has title.
     * 
     * @since 1.0.0
     * @return bool
     */
    public function hasTitle() {
        $title = $this->getTitle();
        return !empty( $title );
    }
    
    /**
     * Prints an element title.
     * 
     * @since 1.0.0
     * @return void
     */
    public function title() {
        echo $this->getTitle();
    }
    
    /**
     * Returns an element hint.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getHint() {
        if ( isset( $this->options['hint'] ) ) return $this->options['hint'];
        return false;
    }
    
    /**
     * Returns true if an element has hint.
     * 
     * @since 1.0.0
     * @return bool
     */
    public function hasHint() {
        $hint = $this->getHint() ;
        return !empty( $hint );
    }
    
    /**
     * Prints an element hint.
     * 
     * @since 1.0.0
     * @return void
     */
    public function hint() {
        echo $this->getHint();
    }
    
    /**
     * Returns an element name.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getName() {
        
        if ( empty( $this->options['name'] ) && !empty( $this->options['title'] ) ) {
            $this->options['name'] = str_replace(' ', '-', $this->options['title'] );
            $this->options['name'] = strtolower( $this->options['name'] );
        }
        
        if ( !isset( $this->options['name'] ) ) {
            $this->options['name'] = $this->type . '-' . rand();
        }
        
        return $this->options['name'];
    }
    
    /**
     * Prints an element name.
     * 
     * @since 1.0.0
     * @return void
     */
    public function name() {
        echo $this->getName();
    }  
    
    /**
     * Returns an element type.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Returns an element icon.
     * 
     * @since 1.0.0
     * @return string
     */
    public function getIcon() {
        if ( isset( $this->options['icon'] ) ) return $this->options['icon'];
        return false;
    }
    
    /**
     * Returns true if an element has a icon.
     * 
     * @since 1.0.0
     * @return bool
     */
    public function hasIcon() {
        $icon = $this->getIcon() ;
        return !empty( $icon );
    }
    
    /**
     * Prints an element icon.
     * 
     * @since 1.0.0
     * @return void
     */
    public function icon() {
        echo $this->getIcon();
    }
}
