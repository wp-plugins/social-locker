<?php
/**
 * A group of classes and methods to create and manage shortcodes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * A helper class to register new shortcodes.
 * 
 * One shortcode manager for all shortcodes from different plugins.
 * 
 * @since 1.0.0
 */
class FactoryShortcodes320 {
    
    private static $manager = false;
    
    /**
     * Registering a new shortcode.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function register( $className, $plugin ) {
        if ( !self::$manager ) self::$manager = new FactoryShortcodes320_ShortcodeManager();
        self::$manager->register( $className, $plugin );
    }
}

/**
 * Factory Shortcode Manager
 * 
 * The main tasks of the manager is:
 * - creating aninstance of Factory Shortcode per every call of the shortcode.
 * - tracking shortcodes in post content.
 */
class FactoryShortcodes320_ShortcodeManager {
    
    /**
     * A set of registered shortcodes.
     * 
     * @since 1.0.0
     * @var FactoryShortcodes320_Shortcode[] 
     */
    private $shortcodes = array();
    
    /**
     * Keeps links between "class name" => "plugin"
     * 
     * @since 3.2.0
     * @var FactoryShortcodes320_Shortcode[] 
     */
    private $classToPlugin = array();
    
    /**
     * A set of shortcodes that has assets connects (js and css).
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    public $connected = array();
    
    /**
     * This method allows to create a new shortcode object for each call.
     * 
     * @param type $name A shortcode name.
     * @param type $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
  
        list($prefix, $type) = explode('_', $name, 2);
        if ($prefix !== 'shortcode') return;
        
        $blank = new $type( $this->classToPlugin[$type] );
        return $blank->render($arguments[0], $arguments[1]);
    }    
    
    /**
     * Registers a new shortcode.
     * 
     * @since 1.0.0
     * @param type $className A short code class name.
     * @return void
     */
    public function register( $className, $plugin ) {
        $shortcode = new $className( $plugin );
        $shortcode->manager = $this;
        
        $this->shortcodes[] = $shortcode;
        
        foreach($shortcode->shortcodeName as $shortcodeName) {
            $className = get_class($shortcode);
            $this->classToPlugin[$className] = $plugin;
            add_shortcode($shortcodeName, array($this, 'shortcode_' . $className));
        }
    }
} 