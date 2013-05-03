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
if (defined('FACTORY_FORM_FR106_LOADED')) return;
define('FACTORY_FORM_FR106_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_FORM_FR106_DIR', dirname(__FILE__));
define('FACTORY_FORM_FR106_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(FACTORY_FORM_FR106_DIR. '/form.class.php');
include(FACTORY_FORM_FR106_DIR. '/metabox-form.class.php');

// control base
include(FACTORY_FORM_FR106_DIR. '/controls/form-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/form-standart-control.class.php');

// default controls
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/textbox-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/url-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/integer-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/editor-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/hidden-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/list-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/default-controls/textarea-control.class.php');

// mendeleev controls
include(FACTORY_FORM_FR106_DIR. '/controls/mendeleev-controls/radio-control.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/mendeleev-controls/checkbox-control.class.php');

// service controls
include(FACTORY_FORM_FR106_DIR. '/controls/service-controls/form-item.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/service-controls/form-group.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/service-controls/form-tab-item.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/service-controls/form-tab.class.php');
include(FACTORY_FORM_FR106_DIR. '/controls/service-controls/form-collapsed.class.php');

// register form controls
FactoryFormFR106::register('textbox', 'FactoryFormFR106TextboxFormControl');
FactoryFormFR106::register('url', 'FactoryFormFR106UrlFormControl');
FactoryFormFR106::register('textarea', 'FactoryFormFR106TextareaFormControl');
FactoryFormFR106::register('list', 'factoryFormFR106ListFormControl');
FactoryFormFR106::register('integer', 'FactoryFormFR106IntegerFormControl');
FactoryFormFR106::register('hidden', 'FactoryFormFR106HiddenFormControl');
FactoryFormFR106::register('editor', 'FactoryFormFR106EditorFormControl');

FactoryFormFR106::register('mv-radio', 'FactoryFormFR106PiRadioFormControl');
FactoryFormFR106::register('mv-checkbox', 'FactoryFormFR106CheckboxFormControl');

add_action('admin_enqueue_scripts', 'factory_form_fr106_admin_scripts');
function factory_form_fr106_admin_scripts() {
    wp_enqueue_style('forms-style', FACTORY_FORM_FR106_URL . '/assets/css/forms.css'); 
    wp_enqueue_script('forms-script', FACTORY_FORM_FR106_URL . '/assets/js/forms.js'); 
}