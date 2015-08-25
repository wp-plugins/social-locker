<?php
/**
 * A group of classes and methods to create and manage pages.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

add_action('admin_menu', 'FactoryPages321::actionAdminMenu');

/**
 * A base class to manage pages. 
 * 
 * @since 1.0.0
 */
class FactoryPages321 {

    private static $pages = array();

    public static function register( $plugin, $className ) {
        if ( !isset( self::$pages[$plugin->pluginName] ) ) self::$pages[$plugin->pluginName] = array();
        self::$pages[$plugin->pluginName][] = new $className( $plugin );
    }
        
    public static function actionAdminMenu() {
        if ( empty(self::$pages) ) return;

        foreach(self::$pages as $pluginPages) {
            foreach($pluginPages as $page) {
                $page->connect();
            }
        }
    }
    
    public static function getIds( $plugin ){
        if ( !isset( self::$pages[$plugin->pluginName] ) ) return array();
        
        $result = array();
        foreach(self::$pages[$plugin->pluginName] as $page) $result[] = $page->getResultId();
        return $result;
    }
}

function factory_pages_321_get_page_id( $plugin, $pureId ) {
    return $pureId . '-' . $plugin->pluginName;
}