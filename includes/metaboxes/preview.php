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
class OnpSL_PreviewMetaBox extends FactoryMetaboxes320_Metabox
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
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
        $this->title = __('Locker Preview', 'sociallocker');
    }
    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes320_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {        
        global $sociallocker;
        $query_string = '?action=onp_sl_preview'; 
        $query_string = apply_filters('onp_sl_preview_url', $query_string);
        
        $extra_data = array(
            'data-lang' => get_option('sociallocker_lang'),
            'data-short-lang' => get_option('sociallocker_short_lang'),
            'data-facebook-appid' => get_option('sociallocker_facebook_appid'),
            'data-facebook-version' => get_option('sociallocker_facebook_version', 'v1.0')
        );
        $extra_data['data-vk-appid'] = get_option('sociallocker_vk_appid');
        $extra_data['data-url'] = admin_url('admin-ajax.php') . $query_string;
        
        $extra_data = apply_filters('onp_sl_preview_data_wrap', $extra_data);
        
        $dataPrint = sizeof($extra_data) ? ' ' : '';        
        foreach( $extra_data as $key => $val) {
            $dataPrint .= $key.'="'.$val.'" ';
        }
        $dataPrint = rtrim($dataPrint, ' ');
        ?>
        <script>
            function onp_sl_update_preview_height(height) {
                jQuery("#lock-preview-wrap iframe").height(height);
            }
            var pluginName = '<?php echo $sociallocker->pluginName; ?>';
            
            <?php if ( defined('ONP_SL_STYLER_PLUGIN_ACTIVE') ) { ?>
            window.onp_sl_styleroller = true;
            <?php } else { ?>
            window.onp_sl_styleroller = false;
            window.onp_sl_styleroller_offer_text = '<?php _e('Want more themes?', 'sociallocker') ?>';
            window.onp_sl_styleroller_offer_url = '<?php echo $sociallocker->options['styleroller'] ?>';
            <?php } ?>
        </script>
        <p class="note"><strong><?php _e('Note', 'sociallocker'); ?>:</strong> <?php _e('In the preview mode, the some features of the locker may not work properly.', 'sociallocker'); ?></p>
        <div id="lock-preview-wrap"<?php echo $dataPrint; ?>>
            <iframe 
                allowtransparency="1" 
                frameborder="0" 
                hspace="0" 
                marginheight="0"
                marginwidth="0"
                name="preview"
                vspace="0"
                width="100%">
                <?php _e('Your browser doen\'t support the iframe tag.', 'sociallocker'); ?>
            </iframe>           
        </div>
        <?php
    }
}

FactoryMetaboxes320::register('OnpSL_PreviewMetaBox', $sociallocker);
