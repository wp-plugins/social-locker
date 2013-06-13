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
if (defined('FACTORY_FORM_FR107_LOADED')) return;
define('FACTORY_FORM_FR107_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_FORM_FR107_DIR', dirname(__FILE__));
define('FACTORY_FORM_FR107_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(FACTORY_FORM_FR107_DIR. '/html-helpers.class.php');
include(FACTORY_FORM_FR107_DIR. '/form.class.php');
include(FACTORY_FORM_FR107_DIR. '/metabox-form.class.php');

// control base
include(FACTORY_FORM_FR107_DIR. '/controls/form-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/form-standart-control.class.php');

// default controls
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/textbox-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/url-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/integer-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/editor-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/hidden-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/list-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/default-controls/textarea-control.class.php');

// mendeleev controls
include(FACTORY_FORM_FR107_DIR. '/controls/mendeleev-controls/radio-control.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/mendeleev-controls/checkbox-control.class.php');

// service controls
include(FACTORY_FORM_FR107_DIR. '/controls/service-controls/form-item.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/service-controls/form-group.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/service-controls/form-tab-item.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/service-controls/form-tab.class.php');
include(FACTORY_FORM_FR107_DIR. '/controls/service-controls/form-collapsed.class.php');

// register form controls
FactoryFormFR107::register('textbox', 'FactoryFormFR107TextboxFormControl');
FactoryFormFR107::register('url', 'FactoryFormFR107UrlFormControl');
FactoryFormFR107::register('textarea', 'FactoryFormFR107TextareaFormControl');
FactoryFormFR107::register('list', 'factoryFormFR107ListFormControl');
FactoryFormFR107::register('integer', 'FactoryFormFR107IntegerFormControl');
FactoryFormFR107::register('hidden', 'FactoryFormFR107HiddenFormControl');
FactoryFormFR107::register('editor', 'FactoryFormFR107EditorFormControl');

FactoryFormFR107::register('mv-radio', 'FactoryFormFR107PiRadioFormControl');
FactoryFormFR107::register('mv-checkbox', 'FactoryFormFR107CheckboxFormControl');

add_action('admin_enqueue_scripts', 'factory_form_fr107_admin_scripts');
function factory_form_fr107_admin_scripts() {
    wp_enqueue_style('forms-style', FACTORY_FORM_FR107_URL . '/assets/css/forms.css'); 
    wp_enqueue_style('forms-controls', FACTORY_FORM_FR107_URL . '/assets/css/controls.css'); 
    wp_enqueue_script('forms-controls', FACTORY_FORM_FR107_URL . '/assets/js/controls.js'); 
}