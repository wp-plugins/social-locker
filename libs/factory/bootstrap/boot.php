<?php
/**
 * Factory Bootstrap
 * 
 * Factory is an internal professional framework developed by OnePress Ltd
 * for own needs. Please don't use it to create your own independent plugins.
 * In future the one will be documentated and released for public.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-bootstrap 
 * @since 1.0.0
 */

// module provides function only for the admin area
if ( !is_admin() ) return;

if (defined('FACTORY_BOOTSTRAP_305_LOADED')) return;
define('FACTORY_BOOTSTRAP_305_LOADED', true);

define('FACTORY_BOOTSTRAP_305_DIR', dirname(__FILE__));
define('FACTORY_BOOTSTRAP_305_URL', plugins_url(null,  __FILE__ ));

// sets version of admin interface
if ( is_admin() ) {
    global $wp_version;
    if ( !defined('FACTORY_FLAT_ADMIN_030800')) {
        define('FACTORY_FLAT_ADMIN_030800', version_compare( $wp_version, '3.8', '>='  ));
    }
}

add_action('admin_enqueue_scripts', 'factory_bootstrap_305_load_assets');   
function factory_bootstrap_305_load_assets() {

    if ( FACTORY_FLAT_ADMIN_030800 ) {
        wp_enqueue_style('factory-bootstrap-305', FACTORY_BOOTSTRAP_305_URL . '/assets/flat/css/bootstrap.css');	
        wp_enqueue_script('factory-bootstrap-305', FACTORY_BOOTSTRAP_305_URL . '/assets/flat/js/bootstrap.js', array('jquery')); 
        
        $userId = get_current_user_id();
        $colorName = get_user_meta($userId, 'admin_color', true);
        
        if ( $colorName !== 'fresh' ) {       
            wp_enqueue_style('factory-bootstrap-305-colors', FACTORY_BOOTSTRAP_305_URL . '/assets/flat/css/bootstrap.' . $colorName . '.css');
        }
        
        if ( $colorName == 'light' ) {
            $primaryDark = '#037c9a';
            $primaryLight = '#04a4cc';
        } elseif( $colorName == 'blue' ) {
            $primaryDark = '#d39323';
            $primaryLight = '#e1a948';
        } elseif( $colorName == 'coffee' ) {
            $primaryDark = '#b78a66';
            $primaryLight = '#c7a589';
        } elseif( $colorName == 'ectoplasm' ) {
            $primaryDark = '#839237';
            $primaryLight = '#a3b745';
        } elseif( $colorName == 'ocean' ) {
            $primaryDark = '#80a583';
            $primaryLight = '#9ebaa0';
        } elseif( $colorName == 'midnight' ) {
            $primaryDark = '#d02a21';
            $primaryLight = '#e14d43';
        } elseif( $colorName == 'sunrise' ) {
            $primaryDark = '#c36822';
            $primaryLight = '#dd823b';
        } else {
            $primaryDark = '#0074a2';
            $primaryLight = '#2ea2cc';
        }
        
    } else {
        
        $primaryDark = '#0074a2';
        $primaryLight = '#2ea2cc';
         
        wp_enqueue_style('factory-bootstrap-305', FACTORY_BOOTSTRAP_305_URL . '/assets/volumetric/css/bootstrap.css');
        wp_enqueue_script('factory-bootstrap-305', FACTORY_BOOTSTRAP_305_URL . '/assets/volumetric/js/bootstrap.js', array('jquery')); 
    }

    ?>
    <script>
        if ( !window.onpsl ) window.onpsl = {};
        if ( !window.onpsl.factoryBootstrap305 ) window.onpsl.factoryBootstrap305 = {}; 
        window.onpsl.factoryBootstrap305.colors = {
            primaryDark: '<?php echo $primaryDark ?>',
            primaryLight: '<?php echo $primaryLight ?>'
        };
    </script>
    <?php
}

add_filter('admin_body_class', 'factory_bootstrap_305_admin_body_class');
function factory_bootstrap_305_admin_body_class( $classes  ) {
    global $wp_version;
    $classes .=  version_compare( $wp_version, '3.8', '>='  ) ? 'factory-flat' : 'factory-volumetric';
    return $classes;
}