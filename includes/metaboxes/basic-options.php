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
class OnpSL_BasicOptionsMetaBox extends FactoryMetaboxes320_FormMetabox
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
    public $title;    
    
    
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
	
    public $cssClass = 'factory-bootstrap-323 factory-fontawesome-320';

    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
        $this->title = __('Basic Options', 'sociallocker');
    }
    
    /**
     * Configures a form that will be inside the metabox.
     * 
     * @see FactoryMetaboxes320_FormMetabox
     * @since 1.0.0
     * 
     * @param FactoryForms323_Form $form A form object to configure.
     * @return void
     */
    public function form( $form ) {        

            global $sociallocker;
            
            $form->add(array(  

                array(

                    'type'  => 'textbox',
                    'name'  => 'common_url',
                    'title' => __('URL to share', 'sociallocker'),
                    'hint'  => sprintf(__('Enter the URL that you want your visitors to like, tweet or +1. Or, you can leave this field empty to use the URL of the page where the locker is located.
                               Need a separate URL for each button? Try a 
                               <a href="%s">
                               premium version</a> of the plugin.', 'sociallocker'), onp_licensing_323_get_purchase_url( $sociallocker )),
                    'placeholder'   => 'http://url-to-share.com'
              ),
          ));
        

        
        
        $form->add(array(  
            
            array(
                'type'      => 'textbox',
                'name'      => 'header',
                'title'     => __('Locker header', 'sociallocker'),
                'hint'      => __('Enter the header you want for the locker. You can also leave this field empty.', 'sociallocker'),
                'default'   => __('This content is locked!', 'sociallocker')
            ),
            
            array(
                'type'      => 'wp-editor',
                'name'      => 'message',
                'title'     => __('Locker message', 'sociallocker'),
                'hint'      => __('Enter the text that appears under the header.', 'sociallocker').'<br /><br />'. 
                               __('Shortcodes: [post_title], [post_url].', 'sociallocker'),
                'default'   => __('Please support us, use one of the buttons below to unlock the content.', 'sociallocker'),
                'tinymce'   => array(
                    'setup' => 'function(ed){ window.onpsl.lockerEditor.bindWpEditorChange( ed ); }',
                    'height' => 100
                ),
                'layout'    => array(
                    'hint-position' => 'left'
                )
            ),
        ));
            
            $form->add(array(  
                array(
                    'type'      => 'dropdown',
                    'way'       => 'buttons',
                    'name'      => 'overlap',
                    'data'      => array(
                        array('full', '<i class="fa fa-lock"></i>Full (classic)'),
                        array('transparence', '<i class="fa fa-adjust"></i>Transparency' ),
                        array('blurring', '<i class="fa fa-bullseye"></i>Blurring', sprintf( __( 'This option is available only in the <a href="%s" target="_blank">premium version</a> of the plugin (the transparency mode will be used in the free version)', 'sociallocker' ), onp_licensing_323_get_purchase_url( $sociallocker ) ) )
                    ),
                    'title'     => __('Overlap Mode', 'sociallocker'),
                    'hint'      => __('Choose one of the overlap modes for your locked content.', 'sociallocker'),
                    'default'   => 'full'
                )
            )); 
            
        

        
        $form->add(array(  
            array(
                'type'      => 'dropdown',
                'name'      => 'overlap_position',
                'data'      => array(
                    array('top', __( 'Top Position', 'sociallocker' ) ),
                    array('middle', __( 'Middle Position', 'sociallocker' ) ),
                    array('scroll', __( 'Scrolling (N/A in Preview)', 'sociallocker' ) )
                ),
                'title'     => '',
                'hint'      => '',
                'default'   => 'middle'
            )
        )); 
    }
    
    /**
     * Replaces the 'blurring' overlap with 'transparence' in the free version.
     * 
     * @since 1.0.0
     * @param type $postId
     */
    public function onSavingForm( $postId ) {
            $overlap = isset ( $_POST['sociallocker_overlap'] ) ? $_POST['sociallocker_overlap'] : null;
            if ( $overlap == 'blurring' ) $_POST['sociallocker_overlap'] = 'transparence';
        

    }
}

FactoryMetaboxes320::register('OnpSL_BasicOptionsMetaBox', $sociallocker);
