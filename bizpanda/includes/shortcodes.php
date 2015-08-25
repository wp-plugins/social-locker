<?php

/**
 * A base shortcode for all lockers
 * 
 * @since 1.0.0
 */
class OPanda_LockerShortcode extends FactoryShortcodes320_Shortcode {

    // -------------------------------------------------------------------------------------
    // Includes assets
    // -------------------------------------------------------------------------------------

    public $assetsInHeader = true;
    
    /**
     * Defines what assets need to include.
     * The method is called separate from the Render method during shortcode registration.
     */
    public function assets( $attrs = array(), $fromBody = false, $fromHook = false ) {
        if ( is_admin() ) return false;

        if ( is_array( $attrs) ) {
            
            foreach( $attrs as $attr ) {
                $id = isset( $attr['id'] ) ? (int)$attr['id'] : $this->getDefaultId(); 
                OPanda_AssetsManager::requestAssets( $id, $fromBody, $fromHook ); 
            }

            return true;
        } else {
            return false;
        }
    }
    
    // -------------------------------------------------------------------------------------
    // Content render
    // -------------------------------------------------------------------------------------
        
    public function html($attr, $content) { 
        global $post;
        global $wp_embed;

        $id = isset( $attr['id'] ) ? (int)$attr['id'] : $this->getDefaultId();
        if ( !empty( $id ) ) $lockerMeta = get_post_meta($id, '');
        
        if ( empty( $id ) || empty($lockerMeta) ) {
            printf( __('<div><strong>[Opt-In Panda] The locker [id=%d] doesn\'t exist or the default lockers were deleted.</strong></div>', 'bizpanda'), $id );
            return;
        }
        
        // passcode
        
        if ( OPanda_AssetsManager::autoUnlock( $id ) ) {
            echo $content;
            return;
        }
        
        // runs nested shortcodes
        
        $content = $wp_embed->autoembed($content);
        $content = do_shortcode( $content );
        
        // if returns:
        // 'content' - shows the locker content
        // 'nothing' - shows nothing (cut content)
        // 'locker' or other values - shows the locker
        
        $whatToShow = apply_filters('onp_sl_what_to_show', 'locker', $id );
        if ( 'content' === $whatToShow ) { echo $content; return; }
        if ( 'nothing' === $whatToShow ) return;
        
        $content = preg_replace( '/^<br \/>/', '', $content );
        $content = preg_replace( '/<br \/>$/', '', $content );

        $lockData = OPanda_AssetsManager::getLockerDataToPrint( $id );
        
        // -
        // use the shortcode attrs if specified instead of configured option
        
        if ( isset( $attr['url'] ) ) {
            $lockData['options']['facebook']['like']['url'] = $attr['url'];
            $lockData['options']['facebook']['share']['url'] = $attr['url']; 
            $lockData['options']['twitter']['tweet']['url'] = $attr['url'];
            $lockData['options']['google']['plus']['url'] = $attr['url'];
            $lockData['options']['google']['share']['url'] = $attr['url'];
            $lockData['options']['linkedin']['share']['url'] = $attr['url'];     
        }
        
        if ( isset( $attr['title'] ) ) {
            $lockData['options']['text']['title'] = $attr['title'];    
        }  
        
        if ( isset( $attr['message'] ) ) {
            $lockData['options']['text']['message'] = $attr['message'];    
        } 
        
        if ( isset( $attr['theme'] ) ) {
            $lockData['options']['theme'] = $attr['theme'];    
        } 
        
        $isAjax = false;
        $lockData['ajax'] = false;

        $dynamicTheme = get_option('opanda_dynamic_theme', 0);

        $this->lockId = "onpLock" . rand(100000, 999999);
        $this->lockData = $lockData;
        
        $overlap = $lockData['options']['overlap']['mode'];
        $hideContent = $overlap === 'full';

        if ($isAjax) { ?>
            <div class="onp-locker-call" style="display: none;" data-lock-id="<?php echo $this->lockId ?>"></div>
        <?php } else { ?>           
            <div class="onp-locker-call" <?php if ( $hideContent ) { ?>style="display: none;"<?php } ?> data-lock-id="<?php echo $this->lockId ?>">
                <p><?php echo  $content ?></p>
            </div>
        <?php } ?> 

        <?php 
        

        if ( $dynamicTheme ) { ?>
            <div class="onp-optinpanda-params" style="display: none;">
                <?php echo json_encode( $lockData ) ?>
            </div>
        <?php } else {
           add_action('wp_footer', array($this, 'wp_footer'), 1);
        }
    }
    
    public function wp_footer() {
        $dynamicTheme = get_option('opanda_dynamic_theme', false);
        if ( !$dynamicTheme ) $this->printOptions();
    }
    
    public function printOptions() { 

        
    ?>
        <script>
            if ( !window.bizpanda ) window.bizpanda = {};
            if ( !window.bizpanda.lockerOptions ) window.bizpanda.lockerOptions = {};
            window.bizpanda.lockerOptions['<?php echo $this->lockId; ?>'] = <?php echo json_encode( $this->lockData ) ?>;
        </script>
        <?php  do_action('opanda_print_locker_assets', $this->lockData['lockerId'], $this->lockData ); ?>
    <?php
    }
        
    // -------------------------------------------------------------------------------------
    // Shortcode Tracking
    // -------------------------------------------------------------------------------------
    
    /**
     * Defines whether the changes of post what includes shortcodes are tracked.
     * @var boolean 
     */
    public $track = true;

    /**
     * The function that will be called when a post containing a current shortcode is changed. 
     * @param string $shortcode
     * @param mixed[] $attr
     * @param string $content
     * @param integer $postId
     */
    public function onTrack($shortcode, $attr, $content, $postId) { 

        $id = isset( $attr['id'] ) ? (int)$attr['id'] : $this->getDefaultId(); 
        
        $lockerMeta = get_post_meta($id, '');
        if (empty($lockerMeta)) return;
        
        foreach($lockerMeta as $metaKey => $metaValue) {
            if (strpos($metaKey, 'opanda_locker_content_hash_') === 0) {
                delete_post_meta($id, $metaKey);
            }
        }
    }
}