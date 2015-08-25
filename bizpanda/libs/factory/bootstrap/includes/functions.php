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

add_action('factory_bootstrap_329_plugin_created', 'factory_bootstrap_329_plugin_created');
function factory_bootstrap_329_plugin_created( $plugin ) {
    $manager = new FactoryBootstrap329_Manager( $plugin );
    $plugin->bootstrap = $manager;
}

/**
 * The Bootstrap Manager class.
 * 
 * @since 3.2.0
 */
class FactoryBootstrap329_Manager {
    
    /**
     * A plugin for which the manager was created.
     * 
     * @since 3.2.0
     * @var Factory325_Plugin
     */
    public $plugin;
    
    /**
     * Contains scripts to include.
     * 
     * @since 3.2.0
     * @var string[]
     */
    public $scripts = array();
    
    /**
     * Contains styles to include.
     * 
     * @since 3.2.0
     * @var string[]
     */
    public $styles = array();
            
    /**
     * Createas a new instance of the license api for a given plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;  
        
        add_action('admin_enqueue_scripts', array($this, 'loadAssets'));
        add_filter('admin_body_class', array($this, 'adminBodyClass'));
    }
    
    /**
     * Includes the Bootstrap scripts.
     * 
     * @since 3.2.0
     * @param mixedp[] $scripts
     * @return void
     */
    public function enqueueScript( $scripts ) {

        if ( is_array( $scripts )) {
            foreach( $scripts as $script) {
                if ( !in_array ( $script, $this->scripts ) ) $this->scripts[] = $script; 
            }
        } else {
            if ( !in_array ( $scripts, $this->scripts ) ) $this->scripts[] = $scripts; 
        }
    }

    /**
     * Includes the Bootstrap styles.
     * 
     * @since 3.2.0
     * @param mixedp[] $scripts
     * @return void
     */
    public function enqueueStyle( $styles ) {

        if ( is_array( $styles )) {
            foreach( $styles as $style ) {
                if ( !in_array ( $style, $this->styles ) ) $this->styles[] = $style;
            }
        } else {
            if ( !in_array ( $styles, $this->styles ) ) $this->styles[] = $styles;
        }
    }
    
    /**
     * Loads Bootstrap assets.
     * 
     * @see admin_enqueue_scripts
     * 
     * @since 3.2.0
     * @return void
     */
    public function loadAssets( $hook ) {

        do_action('factory_bootstrap_enqueue_scripts', $hook );
        do_action('factory_bootstrap_enqueue_scripts_' . $this->plugin->pluginName, $hook );

        $dependencies = array();
        if ( !empty( $this->scripts ) ) {
            $dependencies[] = 'jquery';
            $dependencies[] = 'jquery-ui-core'; 
            $dependencies[] = 'jquery-ui-widget'; 
        }

        foreach( $this->scripts as $script ) {
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
                $result = wp_remote_get(FACTORY_BOOTSTRAP_329_URL . '/includes/load-scripts.php?test=1');
                if ( !is_wp_error($result ) && $result && isset( $result['body'] ) && $result['body'] == 'success' ) {
                    update_option('factory_css_js_compression', true );
                }  
            }
        }

        $compression = get_option('factory_css_js_compression', false );

        if ( !$compression ) {

            $id = md5(FACTORY_BOOTSTRAP_329_VERSION);

            $isFirst = true;
            foreach($this->scripts as $scriptToLoad) {
                wp_enqueue_script($scriptToLoad . '-' . $id, FACTORY_BOOTSTRAP_329_URL . "/assets/js/$scriptToLoad.js", $isFirst ? $dependencies : false);
                $isFirst = false;            
            }

            foreach($this->styles as $styleToLoad) {
                wp_enqueue_style($styleToLoad . '-' . $id, FACTORY_BOOTSTRAP_329_URL . "/assets/flat/css/$styleToLoad.css" );       
            }

        // - //

        } else {

            $loadScriptsOut = join( ',', $this->scripts );      
            $loadStylesOut = join( ',', $this->styles );

            if( defined('WP_DEBUG') && WP_DEBUG ) {
                $loadScriptsOut .= "&debug=true";
                $loadStylesOut .= "&debug=true";
            }

            if ( !empty( $this->styles ) ) {
                $id = md5($loadStylesOut . FACTORY_BOOTSTRAP_329_VERSION);
                wp_enqueue_style('factory-bootstrap-329-' . $id, FACTORY_BOOTSTRAP_329_URL . '/includes/load-styles.php?c=1&folder=flat&load='.$loadStylesOut, array(), FACTORY_BOOTSTRAP_329_VERSION); 
            }

            if ( !empty( $this->scripts ) ) {
                $id = md5($loadScriptsOut . FACTORY_BOOTSTRAP_329_VERSION);
                wp_enqueue_script('factory-bootstrap-329-' . $id, FACTORY_BOOTSTRAP_329_URL . '/includes/load-scripts.php?c=1&load='.$loadScriptsOut, $dependencies, FACTORY_BOOTSTRAP_329_VERSION); 
            } 

        
            // Issue #FB-4:
            // Some themes and plugins contain the functions which remove arguments from the scripts and styles paths.
            // If we use the compression, we need to check whether the paths are the same.

            add_filter( 'script_loader_src', array($this, 'testKeepingArgsInPaths'), 99999, 2 );
            add_filter( 'style_loader_src', array($this, 'testKeepingArgsInPaths'), 99999, 2 );
        }

        $userId = get_current_user_id();
        $colorName = get_user_meta($userId, 'admin_color', true);

        if ( $colorName !== 'fresh' ) {       
            wp_enqueue_style('factory-bootstrap-329-colors', FACTORY_BOOTSTRAP_329_URL . '/assets/flat/css/bootstrap.' . $colorName . '.css');
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
            if ( !window.factory ) window.factory = {};
            if ( !window.factory.factoryBootstrap329 ) window.factory.factoryBootstrap329 = {}; 
            window.factory.factoryBootstrap329.colors = {
                primaryDark: '<?php echo $primaryDark ?>',
                primaryLight: '<?php echo $primaryLight ?>'
            };
        </script>
        <?php 
    }
    
    /**
     * Tests whether the scripts and styles path contain query arguments or them were removed.
     * 
     * See 'script_loader_src'
     * See 'style_loader_src'
     * 
     * @since 3.2.5
     * @return void
     */
    public function testKeepingArgsInPaths( $src, $handle ) {
        if ( substr($handle, 0, 22) !== 'factory-bootstrap-329-') return $src;
        
        $parts = explode( '?', $src );
        if ( count( $parts ) > 1 ) return $src;
        
        update_option('factory_css_js_compression', false );
        return $src;
    }
    
    /**
     * Adds the body classes: 'factory-flat or 'factory-volumetric'.
     * 
     * @since 3.2.0
     * @param string $classes
     * @return string
     */
    public function adminBodyClass( $classes) {
        $classes .=  FACTORY_FLAT_ADMIN ? ' factory-flat ' : ' factory-volumetric ';
        return $classes; 
    }
}