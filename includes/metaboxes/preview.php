<?php
/**
 * The file contains a metabox to show the Locker Preview.
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
 * The class to render the metabox Locker Preview'.
 * 
 * @since 1.0.0
 */
class OnpSL_PreviewMetaBox extends FactoryMetaboxes300_Metabox
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
    public $title = 'Locker Preview';
    
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
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes300_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        ?>
        <script>
            function onp_sl_update_preview_height(height) {
                jQuery("#lock-preview-wrap iframe").height(height);
            }
        </script>
        <p class="note"><strong>Note</strong>: It's just a preview. The locker and the social buttons don't work correctly in the admin area.</p>
        <div id="lock-preview-wrap" 
             data-lang="<?php echo get_option('sociallocker_lang') ?>" 
             data-short-lang="<?php echo get_option('sociallocker_short_lang') ?>" 
             data-facebook-appid="<?php echo get_option('sociallocker_facebook_appid') ?>" 
             data-url="<?php echo admin_url('admin-ajax.php') . '/?onp_sl_preview=1' ?>">
            <iframe 
                allowtransparency="1" 
                frameborder="0" 
                hspace="0" 
                marginheight="0"
                marginwidth="0"
                name="preview"
                vspace="0"
                width="100%">
                Your browser doen't support the iframe tag.
            </iframe>
        </div>
        <?php
    }
}

FactoryMetaboxes300::register('OnpSL_PreviewMetaBox');