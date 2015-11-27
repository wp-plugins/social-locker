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
class OPanda_PreviewMetaBox extends FactoryMetaboxes321_Metabox
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
        
        $this->title = __('Locker Preview', 'bizpanda');
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
        global $bizpanda;
        $query_string = '?action=onp_sl_preview'; 
        $query_string = apply_filters('opanda_preview_url', $query_string);
        
        $extra_data = array(
            'data-lang' => get_option('opanda_lang'),
            'data-short-lang' => get_option('opanda_short_lang'),
            'data-facebook-appid' => get_option('opanda_facebook_appid'),
            'data-facebook-version' => get_option('opanda_facebook_version', 'v1.0')            
        );
        $extra_data['data-vk-appid'] = get_option('opanda_vk_appid');
        $extra_data['data-url'] = admin_url('admin-ajax.php') . $query_string;
        
        $extra_data = apply_filters('onp_sl_preview_data_wrap', $extra_data);
        
        $dataPrint = sizeof($extra_data) ? ' ' : '';        
        foreach( $extra_data as $key => $val) {
            $dataPrint .= $key.'="'.$val.'" ';
        }
        $dataPrint = rtrim($dataPrint, ' ');
        
        $showStyleRollerOffer = ( BizPanda::isSinglePlugin() && BizPanda::hasPlugin('sociallocker') );
        ?>
        <script>
            function onp_sl_update_preview_height(height) {
                jQuery("#lock-preview-wrap iframe").height(height);
            }
            var pluginName = '<?php echo $bizpanda->pluginName; ?>';

            window.opanda_proxy_url = '<?php echo opanda_proxy_url() ?>'
            window.opanda_facebook_app_id = '<?php echo get_option('opanda_facebook_appid') ?>';
            window.opanda_google_client_id = '<?php echo get_option('opanda_google_client_id') ?>';
            window.opanda_linkedin_client_id = '<?php echo get_option('opanda_linkedin_client_id') ?>';
            window.opanda_terms = '<?php echo opanda_terms_url() ?>';
            window.opanda_privacy_policy = '<?php echo opanda_privacy_policy_url() ?>';
            window.opanda_subscription_service_name = '<?php echo get_option('opanda_subscription_service', 'none') ?>';
            
            <?php if ( defined('ONP_OP_STYLER_PLUGIN_ACTIVE') ) { ?>
            window.onp_sl_styleroller = true;
            <?php } else { ?>
            window.onp_sl_styleroller = false;
            window.onp_sl_styleroller_offer_text = '<?php _e('Want more themes?', 'bizpanda') ?>';
            window.onp_sl_styleroller_offer_url = '<?php echo $bizpanda->options['styleroller'] ?>';
            <?php } ?>
                
            <?php if ( $showStyleRollerOffer ) { ?>
            window.onp_sl_show_styleroller_offer = true;
            <?php } else { ?>
            window.onp_sl_show_styleroller_offer = false;
            <?php } ?>      
        </script>
        <p class="note"><strong><?php _e('Note', 'bizpanda'); ?>:</strong> <?php _e('In the preview mode, some features of the locker may not work properly.', 'bizpanda'); ?></p>
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
                <?php _e('Your browser doen\'t support the iframe tag.', 'bizpanda'); ?>
            </iframe>           
        </div>
        <?php
    }
}

global $bizpanda;
FactoryMetaboxes321::register('OPanda_PreviewMetaBox', $bizpanda);
