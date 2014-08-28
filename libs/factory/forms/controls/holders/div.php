<?php
/**
 * The file contains the class of Div Control Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Div Control Holder
 * 
 * @since 1.0.0
 */
class FactoryForms324_DivHolder extends FactoryForms324_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'div';
    
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {

        if ( isset( $this->options['class'] )) $this->addCssClass ( $this->options['class'] );
        if ( isset( $this->options['id'] )) $this->addHtmlAttr ( 'id', $this->options['id'] );

        ?>
        <div <?php $this->attrs() ?>>
        <?php
    }
    
    /**
     * Here we should render an end html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function afterRendering() {
        ?>
        </div>
        <?php 
    }
}