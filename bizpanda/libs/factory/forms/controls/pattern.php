<?php
/**
 * Pattern Control
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013-2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 3.1.0
 */

class FactoryForms328_PatternControl extends FactoryForms328_Control 
{
    public $type = 'pattern';
    
    public function getName() {
        return array ( 
            $this->getOption('name') . '__url',
            $this->getOption('name') . '__color'
        );
    }
    
    public function __construct( $options, $form, $provider = null ) {
        parent::__construct( $options, $form, $provider );

        if ( !isset( $options['color']) ) $options['color'] = array();
        $options['color'] = array_merge($options['color'], array(
            'name'  => $this->options['name'] . '_color_picker',
            'default' => isset( $this->options['default'] ) ? $this->options['default']['color'] : null,
            'pickerTarget' => '.factory-control-' . $this->options['name'] . ' .factory-picker-target'
        ));
        if ( !$options['color']['default'] ) $options['color']['default'] = '#1e8cbe';
        
        $name = $this->getOption('name');
        
        // filters to get available patterns for the given background contols
        $this->patterns = apply_filters('factory_forms_328_patterns',  array());
        $this->patterns = apply_filters('factory_forms_328_patterns-' . $name, $this->patterns);
        $this->customPatterns = $this->getOption('patterns', array());
 
        $this->color = new FactoryForms328_ColorControl( $options['color'], $form, $provider );
    }
         
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {  
        $name = $this->getNameOnForm();
        $values = $this->getValue();
        
        // if a pattern is not set by defaut, sets the first available pattern
        if ( empty( $values['url'] ) && !empty( $this->patterns )  ) {
            foreach( $this->patterns as $groupKey => $groupValue ) {
                if ( !empty( $this->patterns[$groupKey]['patterns'] ) ) {
                    $values['url'] = $this->patterns[$groupKey]['patterns'][0]['pattern'];
                    break;
                }
            }
        }
        
        if ( !empty( $values['color'] )) {
            $this->color->setOption( 'value', $values['color'] );
        }
        
        $hasColor = !empty( $values['color'] );        
        if ( $hasColor ) $this->addCssClass('factory-color-panel-active');
        
        ?>
        <div <?php $this->attrs() ?>>            
            <div class="factory-pattern-controls">
                <div class="factory-preview-wrap">
                    <div <?php echo (!empty( $values['url'] )) ? 'style="background:url('.$values['url'].') repeat; border:0; font-size:0;"' : ''; ?> class="factory-preview <?php echo $name; ?>"><span></span></div>                    
                </div>
                <a href="#" class="button button-default factory-button factory-change-color-btn <?php if ( $hasColor ) { echo 'button-active'; }?>" title="<?php _e('Change color', 'factory_forms_328') ?>">
                    <i class="fa fa-flask"></i>
                    <span><?php _e( 're-color', 'factory_forms_328') ?></span>
                </a>  

                <input type="hidden" id="<?php echo $name[0]; ?>" name="<?php echo $name[0]; ?>" value="<?php echo $values['url']; ?>" class="factory-pattern-result">
                <input type="hidden" id="<?php echo $name[1]; ?>" name="<?php echo $name[1]; ?>" value="<?php echo $values['color']; ?>" class="factory-color-result">
            
            </div>
            <div class="factory-color-panel">
                <div class="factory-color-wrap">
                    <span class="factory-color-label"><?php _e('Select color:', 'factory_forms_328') ?></span>
                    <?php $this->color->html() ?>
                    <div class="factory-hint"><i><?php _e('Changing the color may takes a minute or more. Please be patient.', 'factory_forms_328') ?></i></div>
                </div>
                <div class="factory-picker-target"></div>
            </div>
            <div class="factory-patterns-panel">
                <div class="factory-patterns-group factory-patterns-group-custom">
                    <?php $this->printPatterns( $this->customPatterns, 4, '<div class="factory-patterns-item factory-upload-btn factory-no-preview"><span class="fa fa-upload"></span></div>' ) ?>
                </div>
            <?php foreach( $this->patterns as $key => $group ): ?>
                <?php if( !empty( $group['patterns'] ) ): ?>
                <div class="factory-patterns-group factory-patterns-group-<?php echo $key ?>">
                    <div class="factory-patterns-group-title"><?php echo $group['title'] ?></div>
                    <?php $this->printPatterns( $group['patterns'], 4 ) ?>
                </div>     
                <?php endif; ?>   
            <?php endforeach; ?>    
            </div>           
            <div class="clearfix"></div>
        </div>
        <?php
    }
    
    private function printPatterns( $patterns, $perRow, $firstItem = null ) {
        $counter = 0;
        $printFirstItem = $firstItem;
        
        ?>
        <div class="factory-patterns-row">
        <?php

        if ( $printFirstItem  ) {
            echo $printFirstItem;
            $printFirstItem = null;
            $counter++;
        } 
            
        foreach( $patterns as $pattern ) {
            $counter++;
            
            ?>
            <div class="factory-patterns-item" data-pattern="<?php echo $pattern['pattern']; ?>">
                <div class="factory-pattern-holder" style="background:url(<?php echo $pattern['preview']; ?>) repeat;"></div>
            </div>    
            <?php 

            if ( $counter == 4 ) {
                $counter = 0;
                ?>
                </div><div class="factory-patterns-row">
                <?php 
            }
        }
        ?>
        </div>
        <?php
    }
}
