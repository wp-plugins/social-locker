<?php
/**
 * Factory Forms
 * 
 * Factory Forms is an important part of the Factory that provides a declarative
 * way to build forms without any extra html or css markup.
 */

// Module provides function for the admin area only
if ( !is_admin() ) return;

// Checks if the one is already loaded.
// We prevent to load the same version of the module twice.
if (defined('FACTORY_FORM_FR108_LOADED')) return;
define('FACTORY_FORM_FR108_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_FORM_FR108_DIR', dirname(__FILE__));
define('FACTORY_FORM_FR108_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(FACTORY_FORM_FR108_DIR. '/html-helpers.class.php');
include(FACTORY_FORM_FR108_DIR. '/form.class.php');
include(FACTORY_FORM_FR108_DIR. '/metabox-form.class.php');

// control base
include(FACTORY_FORM_FR108_DIR. '/controls/form-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/form-standart-control.class.php');

// default controls
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/textbox-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/url-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/integer-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/editor-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/hidden-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/list-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/default-controls/textarea-control.class.php');

// mendeleev controls
include(FACTORY_FORM_FR108_DIR. '/controls/mendeleev-controls/radio-control.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/mendeleev-controls/checkbox-control.class.php');

// service controls
include(FACTORY_FORM_FR108_DIR. '/controls/service-controls/form-item.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/service-controls/form-group.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/service-controls/form-tab-item.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/service-controls/form-tab.class.php');
include(FACTORY_FORM_FR108_DIR. '/controls/service-controls/form-collapsed.class.php');

// register form controls
FactoryFormFR108::register('textbox', 'FactoryFormFR108TextboxFormControl');
FactoryFormFR108::register('url', 'FactoryFormFR108UrlFormControl');
FactoryFormFR108::register('textarea', 'FactoryFormFR108TextareaFormControl');
FactoryFormFR108::register('list', 'factoryFormFR108ListFormControl');
FactoryFormFR108::register('integer', 'FactoryFormFR108IntegerFormControl');
FactoryFormFR108::register('hidden', 'FactoryFormFR108HiddenFormControl');
FactoryFormFR108::register('editor', 'FactoryFormFR108EditorFormControl');

FactoryFormFR108::register('mv-radio', 'FactoryFormFR108PiRadioFormControl');
FactoryFormFR108::register('mv-checkbox', 'FactoryFormFR108CheckboxFormControl');

add_action('admin_enqueue_scripts', 'factory_form_fr108_admin_scripts');
function factory_form_fr108_admin_scripts() {
    wp_enqueue_style('forms-style', FACTORY_FORM_FR108_URL . '/assets/css/forms.css'); 
    wp_enqueue_style('forms-controls', FACTORY_FORM_FR108_URL . '/assets/css/controls.css'); 
    wp_enqueue_script('forms-controls', FACTORY_FORM_FR108_URL . '/assets/js/controls.js'); 
}