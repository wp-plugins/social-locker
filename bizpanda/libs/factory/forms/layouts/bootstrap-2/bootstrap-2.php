<?php
/**
 * The file contains a form layout based on Twitter Bootstrap 2
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * A form layout based on Twitter Bootstrap 2
 */
class FactoryForms328_Bootstrap2FormLayout extends FactoryForms328_FormLayout {
    
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
        $this->addCssClass('factory-bootstrap-329');
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
        
        $controlName = $control->getName();
        $controlNameClass = $controlName ? 'factory-control-' . $controlName : '';
    ?>
    <div class="control-group <?php echo $themeClass ?> <?php echo $controlNameClass ?>">
        <label for="<?php $control->printNameOnForm() ?>" class="control-label">
            <?php if ( $control->hasIcon() ) { ?>
            <img class="control-icon" src="<?php $control->icon() ?>" />
            <?php } ?>        
            <?php $control->title() ?>
            <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'left' ) { ?>
            <div class="help-block"><?php $control->hint() ?></div>
            <?php } ?>
        </label>
        <div class="controls">
    <?php
    }

    public function afterControl( $control ) {
        if ( $control->getType() == 'hidden' ) return;
    ?>
        <?php if ( $control->hasHint() && $control->getLayoutOption('hint-position', 'bottom') == 'bottom' ) { ?>
        <div class="help-block">
            <?php $control->hint() ?>
        </div>
        <?php } ?>
        </div>
    </div>
    <?php
    }
}
