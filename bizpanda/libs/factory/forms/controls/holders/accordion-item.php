<?php
/**
 * The file contains the class of Tab Control Holder.
 * 
 * @author Alex Kovalev <alex@byonepress.com>
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
class FactoryForms328_AccordionItemHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'accordion-item';
    
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {
    ?>
        <h3><?php echo $this->options['title']; ?></h3>
        <div class="factory-accordion-item">
            <div class="inner-factory-accordion-item">
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
        </div>      
     <?php
    }
}