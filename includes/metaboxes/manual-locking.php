<?php
/**
 * The file contains a class to configure the metabox Bulk Locking.
 * 
 * Created via the Factory Metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The class to show info on how the plugin support is provided.
 * 
 * @since 1.0.0
 */
class OnpSL_ManualLockingMetaBox extends FactoryMetaboxes305_Metabox
{
    /**
     * A visible title of the metabox.
     * 
     * Inherited from the class FactoryMetabox.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @var string
     */
    public $title = 'Manual Locking <i>(recommended)</i>';
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'core';
    
    /**
     * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $context = 'side';

    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes305_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        global $post;
        $isSystem = get_post_meta( $post->ID, 'sociallocker_is_system', true);
        
        $shortcode = '[sociallocker] [/sociallocker]';
        if (!$isSystem) $shortcode = '[sociallocker id="' . $post->ID . '"] [/sociallocker]';
 
        ?>
        <div class="factory-bootstrap-308 factory-fontawesome-305">
           <p class="onp-sl-description-section">
               <?php _e('Wrap content you want to lock via the following shortcode in your post editor:') ?>
               <input class="onp-sl-shortcode" type="text" value='<?php echo $shortcode ?>' />
           </p>
        </div>
        <?php
    }
}

FactoryMetaboxes305::register('OnpSL_ManualLockingMetaBox');