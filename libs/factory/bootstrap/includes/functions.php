<?php
/**
 * Этот файл отвечает за печать стилей ядра в админ панели
 * 
 * Создано для Factory Metaboxes.
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

global $factory_bootstrap_312_scripts;
global $factory_bootstrap_312_styles;

$factory_bootstrap_312_scripts = array();
$factory_bootstrap_312_styles = array();

function factory_bootstrap_312_load_assets( $hook ) {
    global $factory_bootstrap_312_scripts;
    global $factory_bootstrap_312_styles;
    
    do_action('factory_bootstrap_312_enqueue_scripts', $hook );

    $dependencies = array();
    if ( !empty( $factory_bootstrap_312_scripts ) ) {
        $dependencies[] = 'jquery';
        $dependencies[] = 'jquery-ui-core'; 
        $dependencies[] = 'jquery-ui-widget'; 
    }
    
    $autoDepencies = array(
        'plugin.iris' => array()
    );

    foreach( $factory_bootstrap_312_scripts as $script ) {
        switch ($script) {
            case 'plugin.iris':
                $dependencies[] = 'jquery-ui-widget';
                $dependencies[] = 'jquery-ui-slider';
                $dependencies[] = 'jquery-ui-draggable';
                break;
        }
    }
    
    // removes the optimization hack (#SLWP-83)
    // http://diywpblog.com/wordpress-optimization-remove-query-strings-from-static-resources/
    if ( is_admin() ) {
        remove_filter( 'script_loader_src', '_remove_script_version', 15 );
        remove_filter( 'style_loader_src', '_remove_script_version', 15 );
    }
    
    $loadScriptsOut = join( ',', $factory_bootstrap_312_scripts );      
    $loadStylesOut = join( ',', $factory_bootstrap_312_styles );

    if( defined('WP_DEBUG') && WP_DEBUG ) {
        $loadScriptsOut .= "&debug=true";
        $loadStylesOut .= "&debug=true";
    }
    
    if ( FACTORY_FLAT_ADMIN ) {  

        if ( !empty( $factory_bootstrap_312_styles ) ) {
            $id = md5($loadStylesOut . FACTORY_BOOTSTRAP_312_VERSION);
            wp_enqueue_style('factory-bootstrap-312-' . $id, FACTORY_BOOTSTRAP_312_URL . '/includes/load-styles.php?c=1&folder=flat&load='.$loadStylesOut, array(), FACTORY_BOOTSTRAP_312_VERSION); 
        }
        
        if ( !empty( $factory_bootstrap_312_scripts ) ) {
            $id = md5($loadScriptsOut . FACTORY_BOOTSTRAP_312_VERSION);
            wp_enqueue_script('factory-bootstrap-312-' . $id, FACTORY_BOOTSTRAP_312_URL . '/includes/load-scripts.php?c=1&load='.$loadScriptsOut, $dependencies, FACTORY_BOOTSTRAP_312_VERSION); 
        } 

        $userId = get_current_user_id();
        $colorName = get_user_meta($userId, 'admin_color', true);

        if ( $colorName !== 'fresh' ) {       
            wp_enqueue_style('factory-bootstrap-312-colors', FACTORY_BOOTSTRAP_312_URL . '/assets/flat/css/bootstrap.' . $colorName . '.css');
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
        
        if ( !empty( $factory_bootstrap_312_styles ) ) {
            $id = md5($loadStylesOut . FACTORY_BOOTSTRAP_312_VERSION);
            wp_enqueue_style('factory-bootstrap-312-' . $id, FACTORY_BOOTSTRAP_312_URL . '/includes/load-styles.php?c=1&folder=volumetric&load='.$loadStylesOut, array(), FACTORY_BOOTSTRAP_312_VERSION);
        }
        
        if ( !empty( $factory_bootstrap_312_scripts ) ) {
            $id = md5($loadScriptsOut . FACTORY_BOOTSTRAP_312_VERSION);
            wp_enqueue_script('factory-bootstrap-312-' . $id, FACTORY_BOOTSTRAP_312_URL . '/includes/load-scripts.php?c=1&load='.$loadScriptsOut, $dependencies, FACTORY_BOOTSTRAP_312_VERSION);
        }
    }
        
    ?>

    <script>
        if ( !window.onpsl ) window.onpsl = {};
        if ( !window.onpsl.factoryBootstrap312 ) window.onpsl.factoryBootstrap312 = {}; 
        window.onpsl.factoryBootstrap312.colors = {
            primaryDark: '<?php echo $primaryDark ?>',
            primaryLight: '<?php echo $primaryLight ?>'
        };
    </script>
    <?php
}
add_action('admin_enqueue_scripts', 'factory_bootstrap_312_load_assets', 1); 

/**
 * Adds the body classes: 'factory-flat or 'factory-volumetric'.
 */
function factory_bootstrap_312_admin_body_class( $classes  ) {
    $classes .=  FACTORY_FLAT_ADMIN ? ' factory-flat ' : ' factory-volumetric ';
    return $classes;
}
add_filter('admin_body_class', 'factory_bootstrap_312_admin_body_class');

/**
 * Includes the Bootstrap scripts.
 */
function factory_bootstrap_312_enqueue_script( $scripts ) {
    global $factory_bootstrap_312_scripts;
    
    if ( is_array( $scripts )) {
        foreach( $scripts as $script) {
            if ( !in_array ( $script, $factory_bootstrap_312_scripts ) ) $factory_bootstrap_312_scripts[] = $script; 
        }
    } else {
        if ( !in_array ( $scripts, $factory_bootstrap_312_scripts ) ) $factory_bootstrap_312_scripts[] = $scripts; 
    }
}

/**
 * Includes the Bootstrap styles.
 */
function factory_bootstrap_312_enqueue_style( $styles ) {
    global $factory_bootstrap_312_styles;
    
    if ( is_array( $styles )) {
        foreach( $styles as $style ) {
            if ( !in_array ( $style, $factory_bootstrap_312_styles ) ) $factory_bootstrap_312_styles[] = $style;
        }
    } else {
        if ( !in_array ( $styles, $factory_bootstrap_312_styles ) ) $factory_bootstrap_312_styles[] = $styles;
    }
}

function _remove_script_version( $src, $handle ){

	$parts = explode( '?', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 2 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 2 );

