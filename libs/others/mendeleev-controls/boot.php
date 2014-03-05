<?php
/**
 * Mendeelev Controls
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package onepress-mendeleev 
 * @since 1.0.0
 */

// module provides function for the admin area only
if ( !is_admin() ) return;

// checks if the module is already loaded in order to
// prevent loading the same version of the module twice.
if (defined('MENDELEEV_305_LOADED')) return;
define('MENDELEEV_305_LOADED', true);

// absolute path and URL to the files and resources of the module.
define('MENDELEEV_305_LOADED_URL', plugins_url(null,  __FILE__ ));

// registration of control themes
FactoryForms305_Form::registerControlTheme( array(
    'name'      => 'mendeleev-305',
    'style'     => MENDELEEV_305_LOADED_URL. '/css/{temper}/mendeleev-controls.css',
    'script'    => MENDELEEV_305_LOADED_URL. '/js/mendeleev-controls.js',
));