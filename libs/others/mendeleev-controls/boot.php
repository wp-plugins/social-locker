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
if (defined('MENDEELEV_000_LOADED')) return;
define('MENDEELEV_000_LOADED', true);

// absolute path and URL to the files and resources of the module.
define('MENDEELEV_000_LOADED_URL', plugins_url(null,  __FILE__ ));

// registration of control themes
FactoryForms300_Form::registerControlTheme( array(
    'name'      => 'mendeleev-300',
    'style'     => MENDEELEV_000_LOADED_URL. '/css/{temper}/mendeleev-controls.css',
    'script'    => MENDEELEV_000_LOADED_URL. '/js/mendeleev-controls.js',
));