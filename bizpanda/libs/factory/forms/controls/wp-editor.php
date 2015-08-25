<?php
/**
 * WP Editor Control
 * 
 * Main options:
 *  name            => a name of the control
 *  value           => a value to show in the control
 *  default         => a default value of the control if the "value" option is not specified
 *  tinymce         => an array of options for tinymce
 *                     @link http://codex.wordpress.org/Function_Reference/wp_editor
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms328_WpEditorControl extends FactoryForms328_Control 
{
    public $type = 'wp-editor';
       
    /**
     * Preparing html attributes and options for tinymce.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        if ( empty( $this->options['tinymce'] ) ) $this->options['tinymce'] = array();
        if ( !isset( $this->options['tinymce']['content_css'] ) ) 
            $this->options['tinymce']['content_css'] = FACTORY_FORMS_328_URL . '/assets/css/editor.css';
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        $nameOnForm = $this->getNameOnForm(); 
        $value = $this->getValue();
        
        ?>
        <div class='factory-form-wp-editor'>
        <?php wp_editor( $value, $nameOnForm, array(
            'textarea_name' => $nameOnForm,
            'wpautop' => false,
            'teeny' => true,
            'tinymce' => $this->getOption('tinymce', array())
        )); ?> 
        </div>
        <?php
    }
}
