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
if (defined('FACTORY_FORM_FR100_LOADED')) return;
define('FACTORY_FORM_FR100_LOADED', true);

// Absolute path and URL to the files and resources of the module.
define('FACTORY_FORM_FR100_DIR', dirname(__FILE__));
define('FACTORY_FORM_FR100_URL', plugins_url(null,  __FILE__ ));

// - Includes parts

include(FACTORY_FORM_FR100_DIR. '/form.class.php');
include(FACTORY_FORM_FR100_DIR. '/metabox-form.class.php');

// control base
include(FACTORY_FORM_FR100_DIR. '/controls/form-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/form-standart-control.class.php');

// default controls
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/textbox-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/url-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/integer-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/editor-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/hidden-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/list-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/default-controls/textarea-control.class.php');

// mendeleev controls
include(FACTORY_FORM_FR100_DIR. '/controls/mendeleev-controls/radio-control.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/mendeleev-controls/checkbox-control.class.php');

// service controls
include(FACTORY_FORM_FR100_DIR. '/controls/service-controls/form-item.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/service-controls/form-group.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/service-controls/form-tab-item.class.php');
include(FACTORY_FORM_FR100_DIR. '/controls/service-controls/form-tab.class.php');

// register form controls
FactoryFormFR100::register('textbox', 'FactoryFormFR100TextboxFormControl');
FactoryFormFR100::register('url', 'FactoryFormFR100UrlFormControl');
FactoryFormFR100::register('textarea', 'FactoryFormFR100TextareaFormControl');
FactoryFormFR100::register('list', 'factoryFormFR100ListFormControl');
FactoryFormFR100::register('integer', 'FactoryFormFR100IntegerFormControl');
FactoryFormFR100::register('hidden', 'FactoryFormFR100HiddenFormControl');
FactoryFormFR100::register('editor', 'FactoryFormFR100EditorFormControl');

FactoryFormFR100::register('mv-radio', 'FactoryFormFR100TextareaFormControl');
FactoryFormFR100::register('mv-checkbox', 'FactoryFormFR100CheckboxFormControl');