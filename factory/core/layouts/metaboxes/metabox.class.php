<?php

/**
 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
 */
abstract class FactoryPR108Metabox {
    
    /**
     * Id of the metabox. 
     * Be default, the current class name is used.
     * @var string 
     */
    public $id = null;
    
    /**
     * Visible title of the metabox.
     * @var string
     */
    public $title = '[Metabox]';
    
    /**
     * Placeholder of the metabox ('normal', 'side', 'advanced')
     * @var string 
     */
    public $context = 'normal';
    
    /**
     * Priority of the metabox.
     * @var string
     */
    public $priority = 'default';
    
    /**
     * Post types to display the metabox.
     * @var array
     */
    public $types = array();
    
    /**
     * Current factory.
     * @var FactoryPlugin
     */
    public $plugin;
    
    /**
     * Currant license manager.
     * @var FactoryLicenseManager 
     */
    public $license;
    
    /**
     * Scripts that required to include.
     * @var FactoryScriptList
     */
    public $scripts;
    
    /**
     * Styles that required to include.
     * @var FactoryStyleList
     */  
    public $styles;
    
    private $isRegisted;
    
    public function __construct( FactoryPR108Plugin $plugin = null ) {
        $this->plugin = $plugin;
        $this->id = empty($this->id) ? get_class($this) : $this->id;
    }
    
    /**
     * Adds a new post type where the metabox appear.
     * @param string $typeName
     */
    public function addType( $typeName ) {
        
       if ( !in_array($typeName, $this->types) ) {
           $this->types[] = $typeName;
       }
    }
    
    public function configure(FactoryPR108ScriptList $scripts, FactoryPR108StyleList $styles) {
        // method must be overriden in the derived classed.
    }

    public function register() {
        if ( $this->isRegisted ) return;
         $this->isRegisted = true;
           
        $this->scripts = new FactoryPR108ScriptList( $this->plugin );
        $this->styles = new FactoryPR108StyleList( $this->plugin );
        
        $this->configure( $this->scripts, $this->styles );
    }
    
    /**
     * Adds scripts and styles for the metabox.
     * @param type $hook
     */
    public function actionAdminScripts() {
        global $post;
            
        if ( $this->scripts->isEmpty() && $this->styles->isEmpty() ) return;
        if ( !in_array( $post->post_type, $this->types)) return;

        $aseetUrl = $this->plugin->pluginUrl . '/assets/';
        
        foreach ($this->scripts->getAllRequired() as $script) {
            wp_enqueue_script( $script );
        }        
        
        foreach ($this->scripts->getAll() as $script) {
            wp_enqueue_script( $script, str_replace('~/', $aseetUrl, $script), array('jquery'), false, true);
        }

        foreach ($this->styles->getAllRequired() as $style) {
            wp_enqueue_style( $style );
        }       
        
        foreach ($this->styles->getAll() as $style) {
            wp_enqueue_style( $style, str_replace('~/', $aseetUrl, $style));
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
        
        $post_type = $_POST['post_type'];
        if ( !in_array( $post_type, $this->types ) ) return $post_id;
        
        // Verify the nonce before proceeding
        $className = strtolower( get_class($this) );  
        $nonceName = $className . '_factory_nonce';
        $nonceValue = $className  . '_factory';
        
        if ( !isset( $_POST[$nonceName] ) || !wp_verify_nonce( $_POST[$nonceName], $nonceValue ) )
            return $post_id;
        
        if ( wp_is_post_revision( $post_id ) ) return $post_id;
        
        // Get the post type object.
	$post_type = get_post_type_object( $post_type );

	// Check if the current user has permission to edit the post.
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;
        
        // All right, save data.
        $this->save( $post_id );
    }
    
    /**
     * Renders content of the current metabox.
     */
    public function show() {
        $this->license = $this->plugin->license;  
         
        // security nonce
        $className = strtolower( get_class($this) );
        wp_nonce_field( $className  . '_factory', $className . '_factory_nonce' );
        
        ob_start();
        $this->render();
        $content = ob_get_clean();
        
        echo $content;
    }
    
    public function render() {             
        echo 'Define the method "html" in your metabox class.';
    }
    
    public function save( $postId ) {
        // The method must be overridden in the derived classes.
    }
}