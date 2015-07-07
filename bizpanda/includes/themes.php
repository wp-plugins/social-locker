<?php
/**
 * Theme Manager Class
 * 
 * Manages themes available to use.
 * 
 * @since 3.3.3
 */
class OPanda_ThemeManager {
    
    /**
     * The flat to used to call the hook 'onp_sl_register_themes' once.
     * 
     * @since 3.3.3
     * @var bool
     */
    private static $themesRegistered = false;
    
    /**
     * Contains an array of registred themes.
     * 
     * @since 3.3.3
     * @var mixed[] 
     */
    private static $themes;
    

    /**
     * Returns all registered themes.
     * 
     * @since 3.3.3
     * @param string $format the format of the output array, available values: 'dropdown'.
     * @return mixed[]
     */
    public static function getThemes( $item = null, $format = null ) {
        $themes = array();

        if ( !self::$themesRegistered ) {
            do_action('onp_sl_register_themes', $item);
            self::$themesRegistered = true;
        }

        $themes = self::$themes;
        
        if ( $item ) {
            
            $allThemes = $themes;
            $themes = array();
            
            foreach( $allThemes as $themeName => $themeData ) {
                if ( isset( $themeData['items'] ) && !in_array( $item, $themeData['items'] ) ) continue;
                $themes[$themeName] = $themeData;          
            }
        }
        
        if ( 'dropdown' === $format ) {
            $output = array();
            foreach( $themes as $theme ) {
                $output[] = array( 
                    'title' => $theme['title'],
                    'value' => $theme['name'],
                    'hint' => isset( $theme['hint'] ) ? $theme['hint'] : null,
                    'data' => array(
                        'preview' => isset( $theme['preview'] ) ? $theme['preview'] : null,
                        'previewHeight' => isset( $theme['previewHeight'] ) ? $theme['previewHeight'] : null      
                    )
                ); 
            }
            return $output;
        }
        
        
        return $themes;
    }
    
    /**
     * Registers a new theme.
     * 
     * @since 3.3.3
     * @param mixed $themeOptions
     * @return void
     */
    public static function registerTheme( $themeOptions ) {
        self::$themes[$themeOptions['name']] = $themeOptions;
    }
    
    /**
     * Returns editable options for a given theme.
     * 
     * @since 3.3.3
     * @param string $themeName A theme name for which we need to return the options.
     * @return mixed[]
     */
    public static function getEditableOptions( $themeName ) {
        $themes = self::getThemes();
        
        if ( isset( $themes[$themeName] )) {
            
            $path = $themes[$themeName]['path'] . '/editable-options.php';
            if ( !file_exists($path)) return false;
            
            require_once $path;
        }

        $options = array();

        $functionToCall = 'onp_sl_get_' . str_replace('-', '_', $themeName ) . '_theme_editable_options';
        if (function_exists($functionToCall)) $options = $functionToCall();

        $options = apply_filters( 'onp_sl_editable_' . $themeName . '_theme_options', $options, $themeName) ;
        $options = apply_filters( 'onp_sl_editable_theme_options', $options, $themeName) ;
        
        return $options;
    }
    
    /**
     * Returns CSS converting rules.
     * 
     * @since 3.3.3
     * @param string $themeName A theme name for which we need to return the rules.
     * @return mixed[]
     */
    public static function getRulesToGenerateCSS( $themeName ) {
        $themes = self::getThemes();
        
        if ( isset( $themes[$themeName] )) {
            
            $path = $themes[$themeName]['path'] . '/css-rules.php';
            if ( !file_exists($path)) return false;
            
            require_once $path;
        }

        $rules = array();
        
        $functionToCall = 'onp_sl_get_' . str_replace('-', '_', $themeName ) . '_theme_css_rules';
        if (function_exists($functionToCall)) $rules = $functionToCall();

        $rules = apply_filters( 'onp_sl_' . $themeName . '_theme_css_rules', $rules, $themeName) ;
        $rules = apply_filters( 'onp_sl_theme_css_rules', $rules, $themeName);
        
        
        return $rules;
    }
}
 

/**
 * Helper which returns a set of editable options for changing background.
 * 
 * @since 1.0.2
 * @param type $name A name base for the options.
 */
function opanda_background_editor_options( $name, $sets = array() ) {
    
    $defaultType = isset( $sets['default'] ) ? $sets['default']['type'] : 'color';
    
    $options = array(
        'type'      => 'control-group',
        'name'      => $name . '_type',
        'default'   => $name . '_' . $defaultType . '_item',
        'title'     => isset( $sets['title'] ) ? $sets['title'] : null,
        'items'     => array(
            array(
                'type'      => 'control-group-item',
                'title'     => __('Color', 'bizpanda'),
                'name'      => $name . '_color_item',
                'items'     => array(
                    array(
                        'type'      => 'color-and-opacity',
                        'name'      => $name . '_color',
                        'title'     => __('Set up color and opacity:', 'bizpanda'),
                        'default'   => ( isset( $sets['default'] ) && $defaultType == 'color' ) ? $sets['default']['value'] : null
                    )
                )
            ),
            array(
                'type'      => 'control-group-item',
                'title'     => __('Gradient', 'bizpanda'),
                'name'      => $name . '_gradient_item',
                'items'     => array(
                    array(
                        'type'      => 'gradient',
                        'name'      => $name . '_gradient',
                        'title'     => __('Set up gradient', 'bizpanda'),
                        'default'   => ( isset( $sets['default'] ) && $defaultType == 'gradient' ) ? $sets['default']['value'] : null
                    )
                )
            ),
            array(
                'type'      => 'control-group-item',
                'title'     => __('Pattern', 'bizpanda'),
                'name'      => $name . '_image_item',
                'items'     => array(
                    array(
                        'type'      => 'pattern',
                        'name'      => $name . '_image',
                        'title'     => __('Set up pattern', 'bizpanda'),
                        'default'   => ( isset( $sets['default'] ) && $defaultType == 'image' ) ? $sets['default']['value'] : null,
                        'patterns'  => ( isset( $sets['patterns']) ) ? $sets['patterns'] : array()
                    )
                )
            )
        )
    );
    
    return $options;
}