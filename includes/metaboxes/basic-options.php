<?php
/**
 * The file contains a class to configure the metabox Basic Options.
 * 
 * Created via the Factory Metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The class to configure the metabox Basic Options.
 * 
 * @since 1.0.0
 */
class OnpSL_BasicOptionsMetaBox extends FactoryMetaboxes305_FormMetabox
{
    /**
     * A visible title of the metabox.
     * 
     * Inherited from the class FactoryMetabox.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @var string
     */
    public $title = 'Basic Options';
    
    /**
     * A prefix that will be used for names of input fields in the form.

     * Inherited from the class FactoryFormMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $scope = 'sociallocker';
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'core';
    
    /**
     * Configures a form that will be inside the metabox.
     * 
     * @see FactoryMetaboxes305_FormMetabox
     * @since 1.0.0
     * 
     * @param FactoryForms307_Form $form A form object to configure.
     * @return void
     */
    public function form( $form ) {        

            global $sociallocker;
            
            $form->add(array(  

                array(

                    'type'  => 'textbox',
                    'name'  => 'common_url',
                    'title' => 'URL to share',
                    'hint'  => 'Enter an URL to like, tweet and +1 or leave this 
                                field empty in order to use an URL of a page where the locker will be placed.' .
                               '<br />Need a separate URL for each button? Try a ' .
                               '<a href="' . onp_licensing_308_get_purchase_url( $sociallocker ) . '">' .
                               'premium version</a> of the plugin.',
                    'placeholder'   => 'http://url-to-share.com'
              ),
          ));
        

        
        $form->add(array(  
            
            array(
                'type'      => 'textbox',
                'name'      => 'header',
                'title'     => 'Locker header',
                'hint'      => 'Enter a header of the locker. You can leave this field empty.',
                'default'   => 'This content is locked!'
            ),
            
            array(
                'type'      => 'wp-editor',
                'name'      => 'message',
                'title'     => 'Locker message',
                'hint'      => 'Enter a message that appears under the header.<br /><br />' . 
                               'Shortcodes: [post_title], [post_url].',
                'default'   => 'Please support us, use one of the buttons below to unlock the content.',
                'tinymce'   => array(
                    'handle_event_callback' => 'sociallocker_editor_callback',
                    'height' => 100
                ),
                'layout'    => array(
                    'hint-position' => 'left'
                )
            ),
        ));    
      
    }
}

FactoryMetaboxes305::register('OnpSL_BasicOptionsMetaBox');