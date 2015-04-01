<?php
/**
 * The file contains a form layout based on Twitter Bootstrap 3
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * A form layout based on Twitter Bootstrap 3
 */
class FactoryForms328_Bootstrap3FormLayout extends FactoryForms328_FormLayout {
    
    public $name = 'default';
    
    /**
     * Creates a new instance of a bootstrap3 form layout.
     * 
     * @since 1.0.0
     * @param mixed[] $options A holder options.
     * @param FactoryForms328_Form $form A parent form.
     */
    public function __construct($options, $form) {
        parent::__construct($options, $form);
        $this->addCssClass('factory-bootstrap');
        if ( isset( $options['cssClass'] ) ) $this->addCssClass( $options['cssClass'] );
    }
    
    /**
     * Renders a beginning of a form.
     * 
     * @since 1.0.0
     * @return void
     */
    public function beforeRendering() {
    ?>
        <div <?php $this->attrs() ?>>
            <div class="form-horizontal">
    <?php
    }
    
    /**
     * Renders the end of a form.
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
    
    public function beforeControl( $control ) {
        if ( $control->getType() == 'hidden' ) return;

        $themeClass = '';
        if ( isset($control->options['theme']) ) $themeClass = $control->options['theme'];
        
        $controlName = $control->getOption('name');
        $controlNameClass = $controlName ? 'factory-control-' . $controlName : '';
    ?>
    <div class="form-group form-group-<?php echo $control->type ?> <?php echo $themeClass ?> <?php echo $controlNameClass ?>">
        <label for="<?php $control->printNameOnForm() ?>" class="col-sm-2 control-label">
            <?php if ( $control->hasIcon() ) { ?>
            <img class="control-icon" src="<?php $control->icon() ?>" />
            <?php } ?>        
            <?php $control->title() ?>
            <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'left' ) { ?>
            <div class="help-block"><?php $control->hint() ?></div>
            <?php } ?>
        </label>
        <div class="control-group col-sm-10">
    <?php
    }

    public function afterControl( $control ) {
        if ( $control->getType() == 'hidden' ) return;
    ?>
        <?php if ( $control->getOption('after', false) ) { ?>
        <span class="factory-after">
            <?php $control->option('after') ?>
        </span>  
        <?php } ?>
        <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'bottom' ) { ?>
        <div class="help-block">
            <?php $control->hint() ?>
        </div>
        <?php } ?>
        </div>
    </div>
    <?php
    }
    
    public function startRow( $index, $total ) {
        ?>
        <div class='factory-row factory-row-<?php echo $index ?> factory-row-<?php echo $index ?>-of-<?php echo $total ?>'>
            <div class="form-group form-group">
        <?php 
    }
    
    public function endRow( $index, $total ) {
        ?>
            </div>
        </div>
        <?php
    }
    
    public function startColumn( $control, $index, $total ) {
        $index = $total == 2 ? 4 : 3;
        $name = $control->getNameOnForm();
        ?>
        <label for="<?php echo $name ?>" class="col-sm-2 control-label control-label-<?php echo $name ?>">
            <?php if ( $control->hasIcon() ) { ?>
            <img class="control-icon" src="<?php $control->icon() ?>" />
            <?php } ?>        
            <?php $control->title() ?>
            <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'left' ) { ?>
            <div class="help-block"><?php $control->hint() ?></div>
            <?php } ?>
        </label>
        <div class="control-group control-group-<?php echo $name ?> col-sm-<?php echo $index ?>">
        <?php
    }
    
    public function endColumn( $control, $index, $total ) {
    ?>
        <?php if ( $control->getOption('after', false) ) { ?>
        <span class="factory-after">
            <?php $control->option('after') ?>
        </span>  
        <?php } ?>
        <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'bottom' ) { ?>
        <div class="help-block">
            <?php $control->hint() ?>
        </div>
        <?php } ?>
        </div>
    <?php
    }
}
