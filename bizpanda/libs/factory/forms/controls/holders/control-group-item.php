<?php
/**
 * The file contains the class of Tab Control Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Tab Control Holder
 * 
 * @since 1.0.0
 */
class FactoryForms328_ControlGroupItem extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'control-group-item';
    
    
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() { 
            $this->addCssClass('control-group-item' );             
            $this->addCssClass('factory-control-group-item-' . $this->options['name'] );
            if ( $this->parent->getValue() == $this->options['name'] ) {                
                $this->addCssClass('current');
                foreach( $this->elements as $val ) {
                    $val->setOption('isActive', 1);
                }               
            } else {
                foreach( $this->elements as $val ) {
                    $val->setOption('isActive', 0);
                }
            } 
                
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