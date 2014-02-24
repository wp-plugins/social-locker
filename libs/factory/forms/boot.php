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
if (defined('FACTORY_FORMS_305_LOADED')) return;
define('FACTORY_FORMS_305_LOADED', true);

// absolute path and URL to the files and resources of the module.
define('FACTORY_FORMS_305_DIR', dirname(__FILE__));
define('FACTORY_FORMS_305_URL', plugins_url(null,  __FILE__ ));

#comp merge
require(FACTORY_FORMS_305_DIR . '/includes/providers/value-provider.interface.php');
require(FACTORY_FORMS_305_DIR . '/includes/providers/meta-value-provider.class.php');
require(FACTORY_FORMS_305_DIR . '/includes/providers/options-value-provider.class.php');

require(FACTORY_FORMS_305_DIR. '/includes/html-builder.class.php');
require(FACTORY_FORMS_305_DIR. '/includes/form-element.class.php');    
require(FACTORY_FORMS_305_DIR. '/includes/control.class.php');
require(FACTORY_FORMS_305_DIR. '/includes/control-holder.class.php');
require(FACTORY_FORMS_305_DIR. '/includes/custom-element.class.php');
require(FACTORY_FORMS_305_DIR. '/includes/form-layout.class.php');
require(FACTORY_FORMS_305_DIR. '/includes/form.class.php');
require(FACTORY_FORMS_305_DIR. '/helpers.php');
#endcomp

// registration of controls
FactoryForms305_Form::registerControls(array(
    array(
        'type'      => 'checkbox',
        'class'     => 'FactoryForms305_CheckboxControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/checkbox.php'
    ),
    array(
        'type'      => 'dropdown',
        'class'     => 'FactoryForms305_DropdownControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/dropdown.php'
    ),
    array(
        'type'      => 'hidden',
        'class'     => 'FactoryForms305_HiddenControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/hidden.php'
    ), 
    array(
        'type'      => 'hidden',
        'class'     => 'FactoryForms305_HiddenControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/hidden.php'
    ), 
    array(
        'type'      => 'radio',
        'class'     => 'FactoryForms305_RadioControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/radio.php'
    ), 
    array(
        'type'      => 'textarea',
        'class'     => 'FactoryForms305_TextareaControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/textarea.php'
    ),  
    array(
        'type'      => 'textbox',
        'class'     => 'FactoryForms305_TextboxControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/textbox.php'
    ),
    array(
        'type'      => 'url',
        'class'     => 'FactoryForms305_UrlControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/url.php'
    ),
    array(
        'type'      => 'wp-editor',
        'class'     => 'FactoryForms305_WpEditorControl',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/wp-editor.php'
    )
));

// registration of control holders
FactoryForms305_Form::registerHolders(array(
    array(
        'type'      => 'tab',
        'class'     => 'FactoryForms305_TabHolder',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/holders/tab.php'
    ),
    array(
        'type'      => 'tab-item',
        'class'     => 'FactoryForms305_TabItemHolder',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/holders/tab-item.php'
    ),
    array(
        'type'      => 'form-group',
        'class'     => 'FactoryForms305_FormGroupHolder',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/holders/form-group.php'
    ), 
    array(
        'type'      => 'more-link',
        'class'     => 'FactoryForms305_MoreLinkHolder',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/holders/more-link.php'
    ),
    array(
        'type'      => 'div',
        'class'     => 'FactoryForms305_DivHolder',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/holders/div.php'
    ), 
));

// registration custom form elements
FactoryForms305_Form::registerCustomElements(array(
    array(
        'type'      => 'html',
        'class'     => 'FactoryForms305_Html',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/customs/html.php',
    ),
    array(
        'type'      => 'separator',
        'class'     => 'FactoryForms305_Separator',
        'include'   => FACTORY_FORMS_305_DIR. '/controls/customs/separator.php',
    ), 
));


// registration of form layouts
FactoryForms305_Form::registerFormLayout( array(
    'name'      => 'bootstrap-2',
    'class'     => 'FactoryForms305_Bootstrap2FormLayout',
    'include'   => FACTORY_FORMS_305_DIR. '/layouts/bootstrap-2/bootstrap-2.php'
));  
FactoryForms305_Form::registerFormLayout( array(
    'name'      => 'bootstrap-3',
    'class'     => 'FactoryForms305_Bootstrap3FormLayout',
    'include'   => FACTORY_FORMS_305_DIR. '/layouts/bootstrap-3/bootstrap-3.php'
));  