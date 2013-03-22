<?php
/**
 * Factory Metabox Manager
 * 
 * The main tasks of the manager is:
 * - register metaboxes for custom posts
 * - process data on post saving
 */
class FactoryFR102MetaboxManager {
    
    public $plugin;
    
    /**
     * Variable to store metaboxes per type they defined for.
     * @var array
     */
    public $metaboxes = array();
    public $types = array();
    
    public function __construct(FactoryFR102Plugin $plugin) {
        $this->plugin = $plugin;
        
        add_action('add_meta_boxes', array($this, 'actionAddMetabox'));
        add_action('save_post', array($this, 'saveMetaData'));
        add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
    }

    /**
     * Registers metabox objects.
     */
    public function register( $metaboxes ) {
        if ( !is_array($metaboxes) ) $metaboxes = array($metaboxes);
        
        foreach($metaboxes as $metabox) {
            $this->metaboxes[$metabox->id] = $metabox;
        }

        foreach($metaboxes as $metabox) {
            if ( empty( $metabox->types ) ) continue;
                
            foreach($metabox->types as $type) {
                $this->types[$type][$metabox->id] = $metabox;
            }
        }
    }
    
    public function registerFor( $metabox, $type ) {
        if ( !isset( $this->metaboxes[$metabox->id] ) ) {
            $this->metaboxes[$metabox->id] = $metabox;
        }
        $this->metaboxes[$metabox->id]->addType($type); 
        $this->types[$type][$metabox->id] = $metabox;
    }
 
    /**
     * Registers metaboxes added during type configuration.
     */
    public function actionAddMetabox() {

        foreach($this->types as $type => $metaboxes) {
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
     * Adds scripts and styles for the metabox.
     * @param type $hook
     */
    public function actionAdminScripts( $hook ) {    
        if ( !in_array( $hook, array('post.php', 'post-new.php'))) return;
        
        foreach($this->metaboxes as $metabox) {
            $metabox->register();
            $metabox->actionAdminScripts( $hook );
        }         
    }
    
    /**
     * Saves metabox data.
     * @param int $post_id
     * @param object $post
     * 
     * @todo remove multiple permossion for post edition.
     */
    public function saveMetaData( $post_id ) {
        
        // Verify the post type
        if ( !isset( $_POST['post_type'] ) ) return $post_id;
        
        foreach($this->metaboxes as $metabox) {
            $metabox->saveMetaData( $post_id );
        }   
    }
} 