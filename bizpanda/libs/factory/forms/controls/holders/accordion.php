<?php
/**
 * The file contains the class of Tab Control Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Tab Control Holder
 * 
 * @since 1.0.0
 */
class FactoryForms328_AccordionHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'accordion';
    
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {        
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