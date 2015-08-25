<?php
/**
 * The file contains the class of Group Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Group Holder
 * 
 * @since 1.0.0
 */
class FactoryForms328_FormGroupHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'form-group';
        
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {

        $this->addCssClass('factory-form-group-'. $this->getName() );
        $this->addHtmlAttr('id', 'factory-form-group-' . $this->getName() );
        
        ?>
        <fieldset <?php $this->attrs()?>>
            <?php if ( $this->hasTitle() ) { ?>
            <legend class='factory-legend'>
                <p class='factory-title'><?php $this->title() ?></p>
                <?php if ( $this->hasHint() ) { ?>
                <p class='factory-hint'><?php echo $this->hint() ?></p>
                <?php } ?>
            </legend>
            <?php } ?>
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
        </fieldset>
        <?php 
    }
}