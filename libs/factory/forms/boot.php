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
if (defined('FACTORY_FORMS_300_LOADED')) return;
define('FACTORY_FORMS_300_LOADED', true);

// absolute path and URL to the files and resources of the module.
define('FACTORY_FORMS_300_DIR', dirname(__FILE__));
define('FACTORY_FORMS_300_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_FORMS_300_DIR . '/includes/providers/value-provider.interface.php');
require(FACTORY_FORMS_300_DIR . '/includes/providers/meta-value-provider.class.php');
require(FACTORY_FORMS_300_DIR . '/includes/providers/options-value-provider.class.php');

require(FACTORY_FORMS_300_DIR. '/includes/html-builder.class.php');
require(FACTORY_FORMS_300_DIR. '/includes/form-element.class.php');    
require(FACTORY_FORMS_300_DIR. '/includes/control.class.php');
require(FACTORY_FORMS_300_DIR. '/includes/control-holder.class.php');
require(FACTORY_FORMS_300_DIR. '/includes/custom-element.class.php');
require(FACTORY_FORMS_300_DIR. '/includes/form-layout.class.php');
require(FACTORY_FORMS_300_DIR. '/includes/form.class.php');
require(FACTORY_FORMS_300_DIR. '/helpers.php');
#endcomp

// registration of controls
FactoryForms300_Form::registerControls(array(
    array(
        'type'      => 'checkbox',
        'class'     => 'FactoryForms300_CheckboxControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/checkbox.php'
    ),
    array(
        'type'      => 'dropdown',
        'class'     => 'FactoryForms300_DropdownControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/dropdown.php'
    ),
    array(
        'type'      => 'hidden',
        'class'     => 'FactoryForms300_HiddenControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/hidden.php'
    ), 
    array(
        'type'      => 'hidden',
        'class'     => 'FactoryForms300_HiddenControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/hidden.php'
    ), 
    array(
        'type'      => 'radio',
        'class'     => 'FactoryForms300_RadioControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/radio.php'
    ), 
    array(
        'type'      => 'textarea',
        'class'     => 'FactoryForms300_TextareaControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/textarea.php'
    ),  
    array(
        'type'      => 'textbox',
        'class'     => 'FactoryForms300_TextboxControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/textbox.php'
    ),
    array(
        'type'      => 'url',
        'class'     => 'FactoryForms300_UrlControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/url.php'
    ),
    array(
        'type'      => 'wp-editor',
        'class'     => 'FactoryForms300_WpEditorControl',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/wp-editor.php'
    )
));

// registration of control holders
FactoryForms300_Form::registerHolders(array(
    array(
        'type'      => 'tab',
        'class'     => 'FactoryForms300_TabHolder',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/holders/tab.php'
    ),
    array(
        'type'      => 'tab-item',
        'class'     => 'FactoryForms300_TabItemHolder',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/holders/tab-item.php'
    ),
    array(
        'type'      => 'form-group',
        'class'     => 'FactoryForms300_FormGroupHolder',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/holders/form-group.php'
    ), 
    array(
        'type'      => 'more-link',
        'class'     => 'FactoryForms300_MoreLinkHolder',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/holders/more-link.php'
    ),
    array(
        'type'      => 'div',
        'class'     => 'FactoryForms300_DivHolder',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/holders/div.php'
    ), 
));

// registration custom form elements
FactoryForms300_Form::registerCustomElements(array(
    array(
        'type'      => 'html',
        'class'     => 'FactoryForms300_Html',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/customs/html.php',
    ),
    array(
        'type'      => 'separator',
        'class'     => 'FactoryForms300_Separator',
        'include'   => FACTORY_FORMS_300_DIR. '/controls/customs/separator.php',
    ), 
));


// registration of form layouts
FactoryForms300_Form::registerFormLayout( array(
    'name'      => 'bootstrap-2',
    'class'     => 'FactoryForms300_Bootstrap2FormLayout',
    'include'   => FACTORY_FORMS_300_DIR. '/layouts/bootstrap-2/bootstrap-2.php'
));  
FactoryForms300_Form::registerFormLayout( array(
    'name'      => 'bootstrap-3',
    'class'     => 'FactoryForms300_Bootstrap3FormLayout',
    'include'   => FACTORY_FORMS_300_DIR. '/layouts/bootstrap-3/bootstrap-3.php'
));  