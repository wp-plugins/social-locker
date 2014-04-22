<?php
/**
 * The file contains the class of More Link Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Collapsed Group Holder
 * 
 * @since 1.0.0
 */
class FactoryForms307_MoreLinkHolder extends FactoryForms307_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'more-link';
        
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {

        $count = isset( $this->options['count'] ) ? $this->options['count'] : 0;
        $id = 'factory-more-link-' . $this->getName();
        
        ?>
        <div <?php $this->attrs() ?>>
            <a href="#<?php echo $id ?>" class="factory-more-link-show"><?php $this->title() ?> (<?php echo $count ?>)</a>
            <div class='factory-more-link-content' id="<?php echo $id ?>" style="display: none;">
                <a href="#<?php echo $id ?>" class='factory-more-link-hide'>hide extra options</a>
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
        </div></div>
        <?php 
    }
}