<?php
/**
 * A group of classes and methods to create and manage metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-metaboxes 
 * @since 1.0.0
 */

add_action('add_meta_boxes', 'FactoryMetaboxes321::actionAddMetaboxes');
add_action('admin_enqueue_scripts', 'FactoryMetaboxes321::actionAdminEnqueueScripts');
add_action('save_post', 'FactoryMetaboxes321::actionSavePost');

/**
 * A base class to manage metaboxes. 
 * 
 * The main tasks of the manager is:
 *  - to register metaboxes for custom posts
 *  - to process data on post saving
 * 
 * @since 1.0.0
 */
class FactoryMetaboxes321 {
    
    /**
     * A variable to store metaboxes per type they defined for.
     * 
     * @since 1.0.0
     * @var FactoryMetaboxes321_Metabox[]
     */
    public static $metaboxes = array();
    
    /**
     * A variable storing post types for which there're metaboxes registered.
     * 
     * @since 1.0.0
     * @var string[] 
     */
    public static $postTypes = array();

	protected static $_existingMetaboxes = array();

    /**
     * Registers a metabox by its class name.
     * 
     * @since 1.0.0
     * @param type $className A metabox class name.
     * @return FactoryMetaboxes321_Metabox
     */
    public static function register( $classNameOrObject, $plugin ) {
  
        if ( is_string( $classNameOrObject )) {
            
            $className = $classNameOrObject;
            if ( !isset( self::$_existingMetaboxes[$className] ) ) {
                self::$_existingMetaboxes[$className] = new $className( $plugin );
            }
        
        } else {
            
            $className = get_class( $classNameOrObject );
            if ( !isset( self::$_existingMetaboxes[$className] ) ) {
                self::$_existingMetaboxes[$className] = $classNameOrObject;
            }
        }
        
        $metabox =self::$_existingMetaboxes[$className];
        self::$metaboxes[$metabox->id] = $metabox;
        
        if ( empty( $metabox->postTypes ) ) return $metabox;
        foreach($metabox->postTypes as $type) {
            self::$postTypes[$type][$metabox->id] = $metabox;
        }
        
        return $metabox;
    }
    
    /**
     * Registers a metabox for a given post type.
     * 
     * @since 1.0.0
     * @param type $classNameOrObject A metabox class name.
     * @param type $postType A post type for which a given metabox should be registered. 
     * @return FactoryMetaboxes321_Metabox
     */
    public static function registerFor( $classNameOrObject, $postType, $plugin ) {

        $metabox = self::register( $classNameOrObject, $plugin );
        self::$metaboxes[$metabox->id]->addPostType($postType); 
        self::$postTypes[$postType][$metabox->id] = $metabox;

        return $metabox;
    }
 
    /**
     * On calling the action "add_meta_boxes".
     * 
     * Registering metaboxes via Wordpress API.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @return void
     */
    public static function actionAddMetaboxes() {

        foreach(self::$postTypes as $type => $metaboxes) {
            foreach($metaboxes as $metabox) {

                add_meta_box( 
                    $metabox->id, 
                    $metabox->title,
                    array($metabox, 'show'), 
                    $type, 
                    $metabox->context, 
                    $metabox->priority 
                );
            }
        }
    }
    
    /**
     * On calling the action "admin_enqueue_scripts".
     * 
     * Adding scripts and styles for registered metaboxes for respective pages.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function actionAdminEnqueueScripts( $hook ) {    
        if ( !in_array( $hook, array('post.php', 'post-new.php'))) return;
        foreach(self::$metaboxes as $metabox) $metabox->connect();
    }
    
    /**
     * On calling the action "save_post".
     * 
     * @since 1.0.0
     * @return void
     */
    public static function actionSavePost( $post_id ) {
        
        // verify the post type
        if ( !isset( $_POST['post_type'] ) ) return $post_id;
        
        foreach(self::$metaboxes as $metabox) {
            $metabox->actionSavePost( $post_id );
        }   
    }
} 