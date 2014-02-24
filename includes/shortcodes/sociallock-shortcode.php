<?php

class OnpSL_Shortcode extends FactoryShortcodes305_Shortcode {
    
    public $track = true;
    
    /**
     * Shortcode name
     * @var string
     */
    public $shortcodeName = array( 
        'sociallocker', 
        'sociallocker-1', 
        'sociallocker-2', 
        'sociallocker-3',
        'sociallocker-4',
        'sociallocker-5');  
        
    // -------------------------------------------------------------------------------------
    // Includes assets
    // -------------------------------------------------------------------------------------

    public $assetsInHeader = true;
    
    /**
     * Defines what assets need to include.
     * The method is called separate from the Render method during shortcode registration.
     */
    public function assets( $fromBody = false, $fromHook = false ) {
        if ( is_admin() ) return;
        OnpSL_AssetsManager::requestAssets( $fromBody, $fromHook );
    }
    
    // -------------------------------------------------------------------------------------
    // Content render
    // -------------------------------------------------------------------------------------
        
    public function html($attr, $content) { 
        global $post;
                
        $id = isset( $attr['id'] ) 
            ? (int)$attr['id'] 
            : get_option('onp_sl_default_locker_id');
        
        if ( !empty($id) ) {
            $lockerMeta = get_post_meta($id, '');
        }

        if ( empty($id) || empty($lockerMeta) ) {
            printf( '<div><strong>[Social Locker] The locker [id=%d] doesn\'t exist or the default lockers was deleted.</strong></div>', $id );
            return;
        }
        
        global $wp_embed;
        $content = $wp_embed->autoembed($content);
        $content = do_shortcode( $content );
        
        $content = preg_replace( '/^<br \/>/', '', $content );
        $content = preg_replace( '/<br \/>$/', '', $content );

        $lockData = OnpSL_AssetsManager::getLockerDataToPrint( $id );
        
        $isAjax = false;
        $lockData['ajax'] = false;
                       
        $dynamicTheme = get_option('sociallocker_dynamic_theme', 0);

        $this->lockId = "onpLock" . rand(100000, 999999);
        $this->lockData = $lockData;

        if ($isAjax) { ?>
            <div class="onp-sociallocker-call" style="display: none;" data-lock-id="<?php echo $this->lockId ?>"></div>
        <?php } else { ?>
            <div class="onp-sociallocker-call" style="display: none;" data-lock-id="<?php echo $this->lockId ?>">
                <p><?php echo  $content ?></p>
            </div>
        <?php } ?> 

        <?php 
        
        if ( $dynamicTheme ) { ?>
            <div class="onp-sociallocker-params" style="display: none;">
                <?php echo json_encode( $lockData ) ?>
            </div>
        <?php } else {
           add_action('wp_footer', array($this, 'wp_footer'), 1);
        }
    }
    
    public function wp_footer() {
        $dynamicTheme = get_option('sociallocker_dynamic_theme', false);
        if ( !$dynamicTheme ) $this->print_options();
    }
    
    public function print_options() { 
    ?>
        <script>
            if ( !window.onpsl ) window.onpsl = {};
            if ( !window.onpsl.lockerOptions ) window.onpsl.lockerOptions = {};
            window.onpsl.lockerOptions['<?php echo $this->lockId; ?>'] = <?php echo json_encode( $this->lockData ) ?>;
        </script>
    <?php
    }
        
    // -------------------------------------------------------------------------------------
    // Shortcode Tracking
    // -------------------------------------------------------------------------------------
    
    /**
     * Defines whether the changes of post what includes shortcodes are tracked.
     * @var boolean 
     */
    public $tracking = true;
    
    /**
     * The function that will be called when a post containing a current shortcode is changed. 
     * @param string $shortcode
     * @param mixed[] $attr
     * @param string $content
     * @param integer $postId
     */
    public function trackingCallback($shortcode, $attr, $content, $postId) { 
 
        $id = isset( $attr['id'] ) 
            ? (int)$attr['id'] 
            : get_option('onp_sl_default_locker_id');
        
        $lockerMeta = get_post_meta($id, '');
        if (empty($lockerMeta)) return;
        
        foreach($lockerMeta as $metaKey => $metaValue) {
            if (strpos($metaKey, 'sociallocker_locker_content_hash_') === 0) {
                delete_post_meta($id, $metaKey);
            }
        }
    }
}

FactoryShortcodes305::register( 'OnpSL_Shortcode' );