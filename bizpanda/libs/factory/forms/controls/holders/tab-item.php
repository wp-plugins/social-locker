<?php
/**
 * The file contains the class of Tab Item Control Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Tab Item Control Holder
 * 
 * @since 1.0.0
 */
class FactoryForms328_TabItemHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'tab-item';
        
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {

        $this->addCssClass('tab-'. $this->getName() ); 
        $this->addHtmlAttr('id', $this->getName() );
        
        $this->addCssClass('tab-pane');
        if ( isset( $this->options['isFirst'] ) && $this->options['isFirst'] ) $this->addCssClass('active');

        ?>
        <div <?php $this->attrs()?>>
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