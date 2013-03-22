<?php
/**
 * Factory Shortcode Manager
 * 
 * The main tasks of the manager is:
 * - creating aninstance of Factory Shortcode per every call of the shortcode.
 * - tracking shortcodes in post content.
 */
class FactoryFR100ShortcodeManager {
    
    public $plugin;
    public $blanks;
    public $connected = array();
    public $editionPostId = null;
    
    /**
     * Save variable that allows check whether the filter Content Save Pre fires once.
     * @var boolean 
     */
    private $firstContentSave = true;

    public function __construct(FactoryFR100Plugin $plugin) {
        $this->plugin = $plugin;
    }
    
    public function __call($name, $arguments)
    {
        list($prefix, $type) = explode('_', $name);
        if ($prefix !== 'shortcode') return;
        
        $shortcode = new $type( $this->plugin );
        
        if ( empty( $this->connected[$shortcode->shortcode] ) ) {
            $this->connected[$shortcode->shortcode] = true;
            
            $shortcode->assets($shortcode->scripts, $shortcode->styles);
            $shortcode->scripts->connect(true);
            $shortcode->styles->connect(true);
        }
        
        return $shortcode->execute($arguments[0], $arguments[1]);
    }    
    
    /**
     * Registers shortcodes blanks.
     */
    public function register( $shortcodeBlanks ) {
 
        $this->blanks = $shortcodeBlanks;
        $isAdmin = is_admin();

        if ($isAdmin) { 
            
            foreach( $this->blanks as $blank ) {
                
                // register shortcodes for tracking
                if ($blank->tracking) {
                    
                    factory_fr100_tr_register_shortcode(
                        $blank->shortcode,
                        array($blank, 'trackingCallback')
                    );
                }
                
                $blank->registerForAdmin();
            }
            
            add_action('save_post', array($this, 'actionSavePost'));            
        }
        else
        {
            // registers shortcodes when we are in the public area
            foreach($this->blanks as $blank) {
                add_shortcode($blank->shortcode, array($this, 'shortcode_' . get_class($blank)));
            }
            
            // includes scripts and styles for shortcodes
            add_action('wp_enqueue_scripts', array($this, 'actionEnqueueScripts'));
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
        factory_fr100_tr_check_content($post->post_content, $postid);
    }
    
    /**
     * Checks needs to include assets in the <head> tag of the post page.
     * @param FactoryFR100Shortcode $blank
     */
    private function checkAssets( $blank, $post, $postid ) {
        
        $blank->assets($blank->scripts, $blank->styles);

        $content = $post->post_content;
        $metaName = 'factory_fr100_' . $blank->shortcode . '_include_assets';

        delete_post_meta($postid, $metaName);

        if ( 
            ( !$blank->styles->isEmpty() || !$blank->scripts->IsHeaderEmpty() ) &&
            !(strpos($content, '[' . $blank->shortcode) === false) 
        ) {
            update_post_meta($postid, $metaName, $blank->shortcode);
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
       
       if ( empty($post) || is_home() || is_front_page() ) return;
       
       foreach( $this->blanks as $blank ) {
           $metaName = 'factory_fr100_' . $blank->shortcode . '_include_assets';
           $metaValue = get_post_meta($post->ID, $metaName);
          
           if ( empty($metaValue) ) continue;
           $blank->assets($blank->scripts, $blank->styles);
           
           $blank->scripts->connect();
           $blank->styles->connect();
           
           $this->connected[$blank->shortcode] = true;
       }    
    }
} 