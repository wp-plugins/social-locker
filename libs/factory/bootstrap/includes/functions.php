<?php
/**
 * This file manages assets of the Factory Bootstap.
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

global $factory_bootstrap_313_scripts;
global $factory_bootstrap_313_styles;

$factory_bootstrap_313_scripts = array();
$factory_bootstrap_313_styles = array();

function factory_bootstrap_313_load_assets( $hook ) {
    global $factory_bootstrap_313_scripts;
    global $factory_bootstrap_313_styles;
    
    do_action('factory_bootstrap_313_enqueue_scripts', $hook );

    $dependencies = array();
    if ( !empty( $factory_bootstrap_313_scripts ) ) {
        $dependencies[] = 'jquery';
        $dependencies[] = 'jquery-ui-core'; 
        $dependencies[] = 'jquery-ui-widget'; 
    }

    foreach( $factory_bootstrap_313_scripts as $script ) {
        switch ($script) {
            case 'plugin.iris':
                $dependencies[] = 'jquery-ui-widget';
                $dependencies[] = 'jquery-ui-slider';
                $dependencies[] = 'jquery-ui-draggable';
                break;
        }
    }
    
    // Issue #FB-3:
    // Tests if we can access load-styles.php and load-scripts.php remotely.
    // If yes, we use load-styles.php and load-scripts.php to load, merge and compress css and js.
    // Otherwise, every resource will be loaded separatly.
 
    $isWpContentAccessTested = get_option('factory_wp_content_access_tested', false );
    if ( !$isWpContentAccessTested ) {
        update_option('factory_css_js_compression', false );
        update_option('factory_wp_content_access_tested', true );
        
        if ( function_exists('wp_remote_get') ) {
            $result = wp_remote_get(FACTORY_BOOTSTRAP_313_URL . '/includes/load-scripts.php?test=1');
            if ( !is_wp_error($result ) && $result && isset( $result['body'] ) && $result['body'] == 'success' ) {
                update_option('factory_css_js_compression', true );
            }  
        }
    }
    
    $compression = get_option('factory_css_js_compression', false );
    
    if ( !$compression ) {

        $id = md5(FACTORY_BOOTSTRAP_313_VERSION);
        
        $isFirst = true;
        foreach($factory_bootstrap_313_scripts as $scriptToLoad) {
            wp_enqueue_script($scriptToLoad . '-' . $id, FACTORY_BOOTSTRAP_313_URL . "/assets/js/$scriptToLoad.js", $isFirst ? $dependencies : false);
            $isFirst = false;            
        }
        
        foreach($factory_bootstrap_313_styles as $styleToLoad) {
            wp_enqueue_style($styleToLoad . '-' . $id, FACTORY_BOOTSTRAP_313_URL . "/assets/flat/css/$styleToLoad.css" );       
        }
     
    // - //
        
    } else {
        
        // removes the optimization hack (#SLWP-83)
        // http://diywpblog.com/wordpress-optimization-remove-query-strings-from-static-resources/
        if ( is_admin() ) {
            remove_filter( 'script_loader_src', '_remove_script_version', 15 );
            remove_filter( 'style_loader_src', '_remove_script_version', 15 );
            remove_filter( 'script_loader_src', '_remove_script_version', 1 );
            remove_filter( 'style_loader_src', '_remove_script_version', 1 );
        }

        $loadScriptsOut = join( ',', $factory_bootstrap_313_scripts );      
        $loadStylesOut = join( ',', $factory_bootstrap_313_styles );

        if( defined('WP_DEBUG') && WP_DEBUG ) {
            $loadScriptsOut .= "&debug=true";
            $loadStylesOut .= "&debug=true";
        }

        if ( !empty( $factory_bootstrap_313_styles ) ) {
            $id = md5($loadStylesOut . FACTORY_BOOTSTRAP_313_VERSION);
            wp_enqueue_style('factory-bootstrap-313-' . $id, FACTORY_BOOTSTRAP_313_URL . '/includes/load-styles.php?c=1&folder=flat&load='.$loadStylesOut, array(), FACTORY_BOOTSTRAP_313_VERSION); 
        }

        if ( !empty( $factory_bootstrap_313_scripts ) ) {
            $id = md5($loadScriptsOut . FACTORY_BOOTSTRAP_313_VERSION);
            wp_enqueue_script('factory-bootstrap-313-' . $id, FACTORY_BOOTSTRAP_313_URL . '/includes/load-scripts.php?c=1&load='.$loadScriptsOut, $dependencies, FACTORY_BOOTSTRAP_313_VERSION); 
        } 
        
    }

    $userId = get_current_user_id();
    $colorName = get_user_meta($userId, 'admin_color', true);

    if ( $colorName !== 'fresh' ) {       
        wp_enqueue_style('factory-bootstrap-313-colors', FACTORY_BOOTSTRAP_313_URL . '/assets/flat/css/bootstrap.' . $colorName . '.css');
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
  
    ?>

    <script>
        if ( !window.onpsl ) window.onpsl = {};
        if ( !window.onpsl.factoryBootstrap313 ) window.onpsl.factoryBootstrap313 = {}; 
        window.onpsl.factoryBootstrap313.colors = {
            primaryDark: '<?php echo $primaryDark ?>',
            primaryLight: '<?php echo $primaryLight ?>'
        };
    </script>
    <?php
}
add_action('admin_enqueue_scripts', 'factory_bootstrap_313_load_assets', 1); 

/**
 * Adds the body classes: 'factory-flat or 'factory-volumetric'.
 */
function factory_bootstrap_313_admin_body_class( $classes  ) {
    $classes .=  FACTORY_FLAT_ADMIN ? ' factory-flat ' : ' factory-volumetric ';
    return $classes;
}
add_filter('admin_body_class', 'factory_bootstrap_313_admin_body_class');

/**
 * Includes the Bootstrap scripts.
 */
function factory_bootstrap_313_enqueue_script( $scripts ) {
    global $factory_bootstrap_313_scripts;
    
    if ( is_array( $scripts )) {
        foreach( $scripts as $script) {
            if ( !in_array ( $script, $factory_bootstrap_313_scripts ) ) $factory_bootstrap_313_scripts[] = $script; 
        }
    } else {
        if ( !in_array ( $scripts, $factory_bootstrap_313_scripts ) ) $factory_bootstrap_313_scripts[] = $scripts; 
    }
}

/**
 * Includes the Bootstrap styles.
 */
function factory_bootstrap_313_enqueue_style( $styles ) {
    global $factory_bootstrap_313_styles;
    
    if ( is_array( $styles )) {
        foreach( $styles as $style ) {
            if ( !in_array ( $style, $factory_bootstrap_313_styles ) ) $factory_bootstrap_313_styles[] = $style;
        }
    } else {
        if ( !in_array ( $styles, $factory_bootstrap_313_styles ) ) $factory_bootstrap_313_styles[] = $styles;
    }
}