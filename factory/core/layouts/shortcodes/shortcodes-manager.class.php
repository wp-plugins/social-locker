<?php
/**
 * Factory Shortcode Manager
 * 
 * The main tasks of the manager is:
 * - creating aninstance of Factory Shortcode per every call of the shortcode.
 * - tracking shortcodes in post content.
 */
class FactoryFR110ShortcodeManager {
    
    public $plugin;
    public $blanks;
    public $connected = array();
    public $editionPostId = null;
    
    /**
     * Save variable that allows check whether the filter Content Save Pre fires once.
     * @var boolean 
     */
    private $firstContentSave = true;

    public function __construct(FactoryFR110Plugin $plugin) {
        $this->plugin = $plugin;
    }
    
    public function __call($name, $arguments)
    {
        list($prefix, $type) = explode('_', $name);
        if ($prefix !== 'shortcode') return;
        
        $blank = new $type( $this->plugin );
        
        foreach($blank->shortcode as $shortcode) {
            if ( empty( $this->connected[$shortcode] ) ) {
                foreach($blank->shortcode as $shortcode) $this->connected[$shortcode] = true;
                
                $blank->assets($blank->scripts, $blank->styles);
                $blank->scripts->connect(true);
                $blank->styles->connect(true);
            }   
        }

        return $blank->execute($arguments[0], $arguments[1]);
    }    
    
    /**
     * Registers shortcodes blanks.
     */
    public function register( $shortcodeBlanks ) {
        if ( empty($shortcodeBlanks) ) return;
        
        $this->blanks = $shortcodeBlanks;
        $isAdmin = is_admin();
        
        // registers shortcodes when we are in the public area
        foreach($this->blanks as $blank) {
            foreach($blank->shortcode as $shortcode) {
                add_shortcode($shortcode, array($this, 'shortcode_' . get_class($blank)));
            }
        }

        // includes scripts and styles for shortcodes
        add_action('wp_enqueue_scripts', array($this, 'actionEnqueueScripts'));

        if ($isAdmin) { 
            
            foreach( $this->blanks as $blank ) {
                
                // register shortcodes for tracking
                if ($blank->tracking) {
                    
                    foreach($blank->shortcode as $shortcode) {
                        factory_fr110_tr_register_shortcode(
                            $shortcode, array($blank, 'trackingCallback')
                        ); 
                    }
                }
                
                $blank->registerForAdmin();
            }
            
            add_action('save_post', array($this, 'actionSavePost'));            
        }
    }
       
    /**
     * Action 'savepost'
     */
    public function actionSavePost( $postid ) {
        if ( wp_is_post_revision( $postid ) ) return $postid;
        
        $post = get_post($postid);
        if (empty($post)) return;

        foreach( $this->blanks as $blank ) {  
            
            // checks needs to include assets in the <head> tag of the post page.
            $this->checkAssets($blank, $post, $postid);
        }  
        
        // runs the shortcode tracking 
        factory_fr110_tr_check_content($post->post_content, $postid);
    }
    
    /**
     * Checks needs to include assets in the <head> tag of the post page.
     * @param FactoryShortcode $blank
     */
    private function checkAssets( $blank, $post, $postid ) {
        
        $blank->assets($blank->scripts, $blank->styles);

        $content = $post->post_content;
        foreach($blank->shortcode as $shortcode) {
            $metaName = 'factory_fr110_' . $shortcode . '_include_assets';

            delete_post_meta($postid, $metaName);

            if ( 
                ( !$blank->styles->isEmpty() || !$blank->scripts->IsHeaderEmpty() ) &&
                !(strpos($content, '[' . $shortcode) === false) 
            ) {
                update_post_meta($postid, $metaName, $shortcode);
            }   
        }
    }
    
    /**
     * Filter 'preContentSave'
     */
    public function filterPreContentSave($content) {
        global $post;
        
        if (!$this->firstContentSave) return $content;
        if (empty($post)) return $content;
        if (property_exists($post, 'mp_skip')) return $content;
        
        $this->firstContentSave = false;
        return mp_filter_content($content, $post->ID, 'on');  
    }
    
    /**
     * Filter 'contentEditPre'
     */
    public function filterContentEditPre($content) {
        global $post;
        if (empty($post)) return $content;
        
        return mp_filter_content($content, $post->ID, 'off');  
    }    
        
    public function actionEnqueueScripts() {
       global $post;
       
       if ( empty($post) ) return;
       
       foreach( $this->blanks as $blank ) {
            foreach($blank->shortcode as $shortcode) {
                $metaName = 'factory_fr110_' . $shortcode . '_include_assets';
                $metaValue = get_post_meta($post->ID, $metaName);

                if ( empty($metaValue) ) continue;
                $blank->assets($blank->scripts, $blank->styles);

                $blank->scripts->connect();
                $blank->styles->connect();

                $this->connected[$shortcode] = true;
            }
        }    
    }
} 