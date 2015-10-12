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
class FactoryForms328_TabHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'tab';
    
    /**
     * An align of a tab (horizontal or vertical).
     * 
     * @since 1.0.0
     * @var string 
     */
    public $align = 'horizontal';
    
    /**
     * Creates a new instance of control holder.
     * 
     * @since 1.0.0
     * @param mixed[] $options A holder options.
     * @param FactoryForms328_Form $form A parent form.
     */
    public function __construct($options, $form) {
        parent::__construct($options, $form);
        $this->align = isset( $options['align'] ) ? $options['align'] : 'horizontal';
    }
    
    /**
     * Here we should render a beginning html of the tab.
     * 
     * @since 1.0.0
     * @return void 
     */
    public function beforeRendering() {

        $isFirstTab = true;
        $tabClass = $this->getOption('class');
        
        if(!empty($tabClass)) 
        $this->addCssClass($tabClass);    
        $this->addCssClass('factory-align-' . $this->align);
        
        ?>
        <div <?php $this->attrs() ?>>
            <div class="factory-headers">
            <ul class="nav nav-tabs">
            <?php foreach( $this->elements as $element ) { 
                if ( $element->options['type'] !== 'tab-item' ) continue;

                $hasIcon = isset( $element->options['icon'] );
                if ( $hasIcon ) $tabIcon = $element->options['icon'];
                
                $builder = new FactoryForms328_HtmlAttributeBuilder();
                $builder->addCssClass('factory-tab-item-header');
                $builder->addCssClass('factory-tab-item-header-'. $element->getName() );     
                if ( $hasIcon ) $builder->addCssClass('factory-tab-item-header-with-icon');
                if ( $isFirstTab ) $builder->addCssClass('active');

                $builder->addHtmlData('tab-id', $element->getName() );
                $isFirstTab = false;
                
                if ($hasIcon) { ?>
                <style>
                    .factory-form-tab-item-header-<?php $element->name() ?> a {
                        background-image: url("<?php echo $tabIcon ?>");
                    }
                </style>
                <?php } ?>
                <li <?php $builder->printAttrs() ?>>
                    <a href="#<?php $element->name() ?>" data-toggle="tab">
                        <?php $element->title() ?>
                    </a>
                </li>
            <?php } ?>
            </ul>
            </div>
            <div class='tab-content factory-bodies'>
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