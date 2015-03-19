<?php
/**
 * The file contains a base class for all shortcodes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The base class for all shortcodes.
 * 
 * @since 1.0.0
 */
abstract class FactoryShortcodes320_Shortcode {
    
    private static $metaKeyShorcodeAssetsForPosts = 'factory_shortcodes_assets';
    
    /**
     * Shortcode name.
     * 
     * @since 1.0.0
     * @var string
     */
    public $shortcodeName = null;
    
    /**
     * If true, the assets methods will be called in header.
     * 
     * @since 3.0.0
     * @var boolean
     */
    public $assetsInHeader = false;
    
    /**
     * A manager that created and track this shortcode.
     * @since 1.0.0
     * @var FactoryShortcodes320_ShortcodeManager 
     */
    private $manager;

    /**
     * Scripts to include on the same page.
     * 
     * @since 1.0.0
     * @var Factory324_ScriptList 
     */
    public $scripts;
    
    /**
     * Styles to include on the same page.
     * 
     * @since 1.0.0
     * @var Factory324_StyleList 
     */
    public $styles;
    
    /**
     * If set true, this shortcode will be tracked on altering post content.
     * 
     * When this shortcode will be found in a post content, the method onTrack will be fired.
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $track = false;
    
    /**
     * If true, it means that shortcodes assets have been conected already.
     * 
     * @since 1.0.0
     * @var bool 
     */
    protected $connected = false;
        
    /** 
     * Creates a new instance of a shortcode objects.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        
        $this->scripts = $this->plugin->newScriptList();
        $this->styles = $this->plugin->newStyleList();   
        
        if ( !is_array( $this->shortcodeName )) {
            $this->shortcodeName = array( $this->shortcodeName );
        }
        
        if ( $this->assetsInHeader ) {
            add_action('wp_enqueue_scripts', array($this, 'actionEnqueueScripts'));
        }
        
        if ( is_admin() ) { 
           add_action('save_post', array($this, 'actionSavePost'));
        }
    }
    
    /**
     * Adds shortcode scripts and styles of it's nedded.
     * 
     * Calls on the hook "wp_enqueue_scripts".
     * 
     * @since 1.0.0
     * @global type $post
     * @return void
     */
    public function actionEnqueueScripts() {
       global $post;
       if ( empty($post) ) return;

       foreach($this->shortcodeName as $shortcodeName) {
            if ( $this->connected ) return;
           
            $metaValue = get_post_meta($post->ID, self::$metaKeyShorcodeAssetsForPosts, true);
            if ( !isset( $metaValue[$shortcodeName] ) ) continue;

            $this->assets( false, true );

            $this->scripts->connect();
            $this->styles->connect();

            $this->connected = true;
        }
    }
    
    /**
     * Adds shortcode scripts and styles of it's nedded.
     * 
     * Calls on the hook "save_post".
     * 
     * @global type $post
     * @return void
     */
    public function actionSavePost( $postid ) {
        if ( wp_is_post_revision( $postid ) ) return $postid;
        
        $post = get_post($postid);
        if (empty($post)) return;
        
        $this->onPostSave( $post );

        // checks needs to include assets in the <head> tag of the post page.
        $this->markPostAssets( $post );
        // 
        if ($this->track) $this->trackShortcode( $post );
    }
    
    /**
     * Marks post as a post containing a current shortcode and to load the shortcodes assets
     * on showing the post.
     * 
     * @since 1.0.0
     * @param object $post A current post.
     * @return void
     */
    private function markPostAssets( $post ) {
        if ( !$this->assetsInHeader) return;
        
        $content = $post->post_content;
        $metaValue = get_post_meta($post->ID, self::$metaKeyShorcodeAssetsForPosts, true);
        if ( !is_array($metaValue) ) $metaValue = array();
        
        foreach($this->shortcodeName as $shortcodeName) {
            unset( $metaValue[$shortcodeName] );
            if ( !(strpos($content, '[' . $shortcodeName) === false) ) {
                $metaValue[$shortcodeName] = true;
            }   
        }
        
        update_post_meta($post->ID, self::$metaKeyShorcodeAssetsForPosts, $metaValue);
    }
    
    /**
     * Checks if a post contains a given shortcode and extract its atrributes and content on post saving.
     * 
     * @since 1.0.0
     * @param object $post A current post.
     * @return void
     */
    private function trackShortcode( $post ) {
        $matches = array();

        $tagregexp = join( '|', $this->shortcodeName );

        $start = '(\[(' . $tagregexp . ')([^\[\]]*)\])';
        $end = '\[\/\2\]';
        $pattern = '/' . $start . '(.*?)' . $end . '/is';

        $count = preg_match_all($pattern, $post->post_content, $matches, PREG_SET_ORDER);
        if (!$count) return $post->post_content;

        foreach($matches as $order => $match) {

            $shortcode = $match[2];
            $attrContent = str_replace('\\', '', $match[3] );
            $innerContent = str_replace('\\', '', $match[4]);

            $attrs = shortcode_parse_atts($attrContent);
            
            $this->onTrack( $shortcode, $attrs, $innerContent, $post->ID );
        }
    }
    
    /**
     * Returns a shortcode html markup.
     * 
     * @since 1.0.0
     * @return string
     */
    public function render($attr, $content) {

        if ( !$this->connected ) {
            $this->assets( true, false );
            $this->scripts->connect(true);
            $this->styles->connect(true);
        }
        
        ob_start();
        $this->html($attr, $content);
        $html = ob_get_clean();
        
        return $html;
    }

    /**
     * Configures assets (js and css) for the shortcodes.
     * 
     * The method should be overwritten in a deferred class.
     * 
     * @since 1.0.0
     * @return void
     */
    public function assets(){}
   
    
    /**
     * Renders shortcode html.
     *      * @since 1.0.0
     * @return void
     */
    public abstract function html($attr, $content);
    
    public function onPostSave ( $post ){}
    public function onTrack( $shortcode, $attrs, $innerContent, $postId ) {}
}