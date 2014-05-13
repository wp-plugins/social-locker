<?php
/**
 * Factory Forms
 * 
 * Factory Forms is a Factory module that provides a declarative
 * way to build forms without any extra html or css markup.
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

// module provides function for the admin area only
if ( !is_admin() ) return;

// checks if the module is already loaded in order to
// prevent loading the same version of the module twice.
if (defined('FACTORY_FORMS_308_LOADED')) return;
define('FACTORY_FORMS_308_LOADED', true);

// absolute path and URL to the files and resources of the module.
define('FACTORY_FORMS_308_DIR', dirname(__FILE__));
define('FACTORY_FORMS_308_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_FORMS_308_DIR . '/includes/providers/value-provider.interface.php');
require(FACTORY_FORMS_308_DIR . '/includes/providers/meta-value-provider.class.php');
require(FACTORY_FORMS_308_DIR . '/includes/providers/options-value-provider.class.php');

require(FACTORY_FORMS_308_DIR. '/includes/form.class.php');
require(FACTORY_FORMS_308_DIR. '/helpers.php');
#endcomp

/**
 * We add this code into the hook because all these controls quite heavy. So in order to get better perfomance, 
 * we load the form controls only on pages where the forms are created.
 * 
 * @see the 'factory_forms_308_register_controls' hook
 * 
 * @since 3.0.7
 */
if (!function_exists('factory_forms_308_register_default_controls')) {
    
    function factory_forms_308_register_default_controls() {

        require_once(FACTORY_FORMS_308_DIR. '/includes/html-builder.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/form-element.class.php');    
        require_once(FACTORY_FORMS_308_DIR. '/includes/control.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/complex-control.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/holder.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/control-holder.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/custom-element.class.php');
        require_once(FACTORY_FORMS_308_DIR. '/includes/form-layout.class.php');

        // registration of controls
        FactoryForms308_Form::registerControls(array(
            array(
                'type'      => 'checkbox',
                'class'     => 'FactoryForms308_CheckboxControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/checkbox.php'
            ),
            array(
                'type'      => 'dropdown',
                'class'     => 'FactoryForms308_DropdownControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/dropdown.php'
            ),
            array(
                'type'      => 'hidden',
                'class'     => 'FactoryForms308_HiddenControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/hidden.php'
            ), 
            array(
                'type'      => 'hidden',
                'class'     => 'FactoryForms308_HiddenControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/hidden.php'
            ), 
            array(
                'type'      => 'radio',
                'class'     => 'FactoryForms308_RadioControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/radio.php'
            ), 
            array(
                'type'      => 'textarea',
                'class'     => 'FactoryForms308_TextareaControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/textarea.php'
            ),  
            array(
                'type'      => 'textbox',
                'class'     => 'FactoryForms308_TextboxControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/textbox.php'
            ),
            array(
                'type'      => 'url',
                'class'     => 'FactoryForms308_UrlControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/url.php'
            ),
            array(
                'type'      => 'wp-editor',
                'class'     => 'FactoryForms308_WpEditorControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/wp-editor.php'
            ), 
            array(
                'type'      => 'color',
                'class'     => 'FactoryForms308_ColorControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/color.php'        
            ),
            array(
                'type'      => 'color-and-opacity',
                'class'     => 'FactoryForms308_ColorAndOpacityControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/color-and-opacity.php'        
            ),
            array(
                'type'      => 'gradient',
                'class'     => 'FactoryForms308_GradientControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/gradient.php'          
            ),
            array(
                'type'      => 'font',
                'class'     => 'FactoryForms308_FontControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/font.php'        
            ),
            array(
                'type'      => 'background',
                'class'     => 'FactoryForms308_BackgroundControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/background.php'        
            ),
            array(
                'type'      => 'integer',
                'class'     => 'FactoryForms308_IntegerControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/integer.php'        
            ),
            array(
                'type'      => 'control-group',
                'class'     => 'FactoryForms308_ControlGroupHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/control-group.php'       
            ), 
            array(
                'type'      => 'paddings-editor',
                'class'     => 'FactoryForms308_PaddingsEditorControl',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/paddings-editor.php'       
            ), 
        ));

        // registration of control holders
        FactoryForms308_Form::registerHolders(array(
            array(
                'type'      => 'tab',
                'class'     => 'FactoryForms308_TabHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/tab.php'
            ),
            array(
                'type'      => 'tab-item',
                'class'     => 'FactoryForms308_TabItemHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/tab-item.php'
            ),
            array(
                'type'      => 'accordion',
                'class'     => 'FactoryForms308_AccordionHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/accordion.php'        
            ),    
            array(
                'type'      => 'accordion-item',
                'class'     => 'FactoryForms308_AccordionItemHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/accordion-item.php'        
            ),    
            array(
                'type'      => 'control-group',
                'class'     => 'FactoryForms308_ControlGroupHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/control-group.php'        
            ),    
            array(
                'type'      => 'control-group-item',
                'class'     => 'FactoryForms308_ControlGroupItem',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/control-group-item.php'        
            ),    
            array(
                'type'      => 'form-group',
                'class'     => 'FactoryForms308_FormGroupHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/form-group.php'
            ), 
            array(
                'type'      => 'more-link',
                'class'     => 'FactoryForms308_MoreLinkHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/more-link.php'
            ),
            array(
                'type'      => 'div',
                'class'     => 'FactoryForms308_DivHolder',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/holders/div.php'
            ), 
        ));

        // registration custom form elements
        FactoryForms308_Form::registerCustomElements(array(
            array(
                'type'      => 'html',
                'class'     => 'FactoryForms308_Html',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/customs/html.php',
            ),
            array(
                'type'      => 'separator',
                'class'     => 'FactoryForms308_Separator',
                'include'   => FACTORY_FORMS_308_DIR. '/controls/customs/separator.php',
            ), 
        ));


        // registration of form layouts
        FactoryForms308_Form::registerFormLayout( array(
            'name'      => 'bootstrap-2',
            'class'     => 'FactoryForms308_Bootstrap2FormLayout',
            'include'   => FACTORY_FORMS_308_DIR. '/layouts/bootstrap-2/bootstrap-2.php'
        ));  
        FactoryForms308_Form::registerFormLayout( array(
            'name'      => 'bootstrap-3',
            'class'     => 'FactoryForms308_Bootstrap3FormLayout',
            'include'   => FACTORY_FORMS_308_DIR. '/layouts/bootstrap-3/bootstrap-3.php'
        ));  
    }

    add_action('factory_forms_308_register_controls', 'factory_forms_308_register_default_controls');
}