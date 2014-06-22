<?php
/**
 * Theme Manager Class
 * 
 * Manages themes available to use.
 * 
 * @since 3.3.3
 */
class OnpSL_ThemeManager {
    
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
    public static function getThemes( $format = null ) {
        $themes = array();
        
        if ( !self::$themesRegistered ) {
            do_action('onp_sl_register_themes');
            self::$themesRegistered = true;
        }
        
        if ( 'dropdown' === $format ) {
            $output = array();
            foreach( self::$themes as $theme ) {
                $output[] = array( $theme['name'], $theme['title'] ); 
            }
            return $output;
        }
        
        return self::$themes;
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
            require_once $themes[$themeName]['path'] . '/editable-options.php';
        }
        
        $options = array();
        
        $functionToCall = 'onp_sl_get_' . $themeName . '_theme_editable_options';
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
            require_once $themes[$themeName]['path'] . '/css-rules.php';
        }
        
        $rules = array();
        
        $functionToCall = 'onp_sl_get_' . $themeName . '_theme_css_rules';
        if (function_exists($functionToCall)) $rules = $functionToCall();
        
        $rules = apply_filters( 'onp_sl_' . $themeName . '_theme_css_rules', $rules, $themeName) ;
        $rules = apply_filters( 'onp_sl_theme_css_rules', $rules, $themeName) ;
        
        return $rules;
    }
}