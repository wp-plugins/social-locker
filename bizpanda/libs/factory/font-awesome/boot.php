<?php
/**
 * Factory Font Awersome
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-bootstrap 
 * @since 1.0.0
 */

// module provides function only for the admin area
if ( !is_admin() ) return;

if (defined('FACTORY_FONTAWESOME_320_LOADED')) return;
define('FACTORY_FONTAWESOME_320_LOADED', true);

define('FACTORY_FONTAWESOME_320_DIR', dirname(__FILE__));
define('FACTORY_FONTAWESOME_320_URL', plugins_url(null,  __FILE__ ));

add_action('admin_enqueue_scripts', 'factory_fontawesome_320_load_assets');   
if (!function_exists('factory_fontawesome_320_load_assets')) {
    function factory_fontawesome_320_load_assets() {
        wp_enqueue_style('factory-fontawesome-320', FACTORY_FONTAWESOME_320_URL . '/assets/css/font-awesome.css');
    }
}