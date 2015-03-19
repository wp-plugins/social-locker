<?php
/**
 * The file contains Html Attribute Builder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Html Attribute Builder
 * 
 * @since 1.0.0
 */
class FactoryForms328_HtmlAttributeBuilder {
    
    /**
     * An array to store css classes.
     * 
     * @since 1.0.0
     * @var string[]
     */
    protected $cssClasses = array();
    
    /**
     * An array to store html attributes.
     * 
     * @since 1.0.0
     * @var string[]
     */
    protected $htmlAttrs = array();
    
    /**
     * An array to store html data.
     * 
     * @since 1.0.0
     * @var string[]
     */
    protected $htmlData = array();
    
    /**
     * Adds a new CSS class.
     * 
     * @since 1.0.0
     * @return void
     */
    public function addCssClass( $class ) {
        if (!is_array($class)) {
            $this->cssClasses[] = $class;
        } else {
            $this->cssClasses = array_merge( $this->cssClasses, $class );
        }
    }
    
    /**
     * Prints CSS classes.
     * 
     * @since 1.0.0
     * @return void
     */
    public function printCssClass() {
        echo implode(' ', $this->cssClasses);
    }
    
    /**
     * Adds a new html data item.
     * 
     * @since 1.0.0
     * @param string $dataKey
     * @param string $dataValue
     * @return void
     */
    public function addHtmlData( $dataKey, $dataValue ) {
        $this->htmlData[$dataKey] = $dataValue;
    }
    
    /**
     * Prints html data items.
     * 
     * @since 1.0.0
     * @return void
     */
    public function printHtmlData() {
        foreach($this->htmlData as $key => $value) {
            echo 'data-' . $key . '="' . $value . '" ';
        } 
    }

    /**
     * Adds a new html attribute.
     * 
     * @since 1.0.0
     * @param type $attrName
     * @param type $attrValue
     * @return void
     */
    public function addHtmlAttr( $attrName, $attrValue ) {
        $this->htmlAttrs[$attrName] = $attrValue;
    }
    
    /**
     * Prints all html attributes, including css classes and data.
     * 
     * @since 1.0.0
     * @return void
     */
    public function printAttrs() {
        $attrs = $this->htmlAttrs;
        
        if ( !empty($this->cssClasses) ) $attrs['class'] = implode(' ', $this->cssClasses);
        
        foreach( $this->htmlData as $dataKey => $dataValue) {
            $attrs['data-'.$dataKey] = $dataValue;
        }
        
        foreach($attrs as $key => $value) {
            echo $key . '="' . $value . '" ';
        } 
    }
}