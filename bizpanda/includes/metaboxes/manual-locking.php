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
class OPanda_ManualLockingMetaBox extends FactoryMetaboxes321_Metabox
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
    public $title;
    
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
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
        
        $this->title = __('Manual Locking <i>(recommended)</i>', 'bizpanda');
    }
    
    public function configure( $scripts, $styles ){
        $scripts->add( OPANDA_BIZPANDA_URL . '/assets/admin/js/metaboxes/manual-lock.010000.js');
    }
    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes321_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        global $post;
        $isSystem = get_post_meta( $post->ID, 'opanda_is_system', true);

        $item = OPanda_Items::getCurrentItem();
        $shortcodeName = $item['shortcode'];
        
        $shortcode = '[' . $shortcodeName . '] [/' . $shortcodeName . ']';
        if (!$isSystem) $shortcode = '[' . $shortcodeName . ' id="' . $post->ID . '"] [/' . $shortcodeName . ']';
 
        ?>
        <div class="factory-bootstrap-329 factory-fontawesome-320">
           <p class="onp-sl-description-section">
               <?php _e('Wrap content you want to lock via the following shortcode in your post editor:', 'bizpanda') ?>
               <input class="onp-sl-shortcode" type="text" value='<?php echo $shortcode ?>' />
           </p>
        </div>
        <?php
    }
}

global $bizpanda;
FactoryMetaboxes321::register('OPanda_ManualLockingMetaBox', $bizpanda);
