<?php
/**
 * The file contains a page that shows the common settings for the plugin.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The page Common Settings.
 * 
 * @since 1.0.0
 */
class OPanda_AdvancedSettings extends OPanda_Settings  {
 
    public $id = 'advanced';
    
    /**
     * Shows the header html of the settings screen.
     * 
     * @since 1.0.0
     * @return void
     */
    public function header() {
        global $optinpanda;
        ?>
        <p>
            <?php _e('Options linked with the locking feature. Don\'t change the options here if you are not sure that you do.', 'bizpanda' )?>
        </p>
        <?php
    }

    /**
     * A page to edit the Advanced Options.
     * 
     * @since v3.7.2
     * @return vod
     */
    public function getOptions() {
        global $optinpanda;

        $forms = array();
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'debug',
            'title'     => __( 'Debug', 'bizpanda' ),
            'hint'      => __( 'When this option turned on, the locker appears always, ignoring any settings, even if the user already unlocked the content.', 'bizpanda' )
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'textbox',
            'name'      => 'passcode',
            'title'     => __( 'Pass Code', 'bizpanda' ),
            'hint'      => sprintf( __( 'Optional. When the pass code is contained in your website URL, the locked content gets automatically unlocked.<br/><div class="opanda-example"><strong>Usage example:</strong> <a href="#" class="opanda-url" target="_blank">%s<span class="opanda-passcode"></span></a></div>', 'bizpanda' ), site_url() ),
            'default'   => false
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'permanent_passcode',
            'title'     => __( 'Permanent Unlock<br /> For Pass Code', 'bizpanda' ),
            'hint'      => __( 'Optional. If On, your lockers will be revealed forever if the user once opened the page URL with the Pass Code.<br />Otherwise your lockers will be unlocked only when the page URL contains the Pass Code.', 'bizpanda' ),
            'default'   => false
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'interrelation',
            'title'     => __( 'Interrelation', 'bizpanda' ),
            'hint'      => __( 'Set On to make lockers interrelated. When one of the interrelated lockers are unlocked on your site, the others will be unlocked too.<br /> Recommended to turn on, if you use the Batch Locking feature.', 'bizpanda' ),
            'default'   => false
        );

        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'dynamic_theme',
            'title'     => __( 'I use a dynamic theme', 'bizpanda' ),
            'hint'      => __( 'If your theme loads pages dynamically via ajax, set "On" to get the lockers working (if everything works properly, don\'t turn on this option).', 'bizpanda' )
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'normalize_markup',
            'title'     => __( 'Normalize Markup for Visual Composer', 'bizpanda' ),
            'hint'      => __( 'If you use the Batch Lock with the mode "Skip & Lock" and Visual Composer (or similar plugins), turn on this option to normalize html markup before output.', 'bizpanda' )
        );
        
        $forms[] = array(
            'type'      => 'div',
            'id'        => 'onp-dynamic-theme-options',
            'items'     => array(

                array(
                    'type'      => 'textbox',
                    'name'      => 'dynamic_theme_event',
                    'title'     => __( 'jQuery Events', 'bizpanda' ),
                    'hint'      => __( 'If pages of your site are loaded dynamically via ajax, it\'s necessary to catch ' . 
                                   'the moment when the page is loaded in order to appear the locker.<br />By default the plugin covers ' .
                                   '99% possible events. So <strong>you don\'t need to set any value here</strong>.<br />' .
                                   'But if you know how it works and sure that it will help, you can put here the javascript event ' .
                                   'that triggers after loading of pages on your site.', 'bizpanda' )
                )   
            )
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'dropdown',
            'name'      => 'alt_overlap_mode',
            'data'      => array(
                array( 'full', __('Classic (full)', 'bizpanda') ),
                array( 'transparence', __('Transparency', 'bizpanda') )   
            ),
            'default'   => 'transparence',
            'title'     => __( 'Alt Overlap Mode', 'bizpanda' ),
            'hint'      => __( 'This overlap mode will be applied for browsers which don\'t support the blurring effect.', 'bizpanda' )
        );

        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'rss',
            'title'     => __( 'Locked content<br /> is visible in RSS feeds', 'bizpanda' ),
            'hint'      => __( 'Set On to make locked content visible in RSS feed.', 'bizpanda' ),
            'default'   => false
        );
        
        if ( BizPanda::hasPlugin('sociallocker') ) {
        
            $forms[] = array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'actual_urls',
                'title'     => __( 'Actual URLs by default', 'bizpanda' ),
                'hint'      => __( 'Optional. If you do not set explicitly URLs to like/share in the settings of social buttons, then by default the plugin will use an URL of the page where the locker is located. Turn on this option to extract URLs to like/share from an address bar of the user browser, saving all query arguments. By default (when this option disabled) permalinks are used.', 'bizpanda' ),
                'default'   => false
            );
        }

        if ( BizPanda::hasPlugin('sociallocker') ) {

            $forms[] = array(
                'type' => 'separator'
            );
        
            $forms[] = array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'tumbler',
                'title'     => __( 'Anti-Cheating', 'bizpanda' ),
                'default'   => false,
                'hint'      => __( 'Turn it on to protect your locked content against cheating from visitors. Some special browser extensions allow to view the locked content without actual sharing. This option checks whether the user has really liked/shared your page. In future versions of the plugin, we will make this option active by default.', 'bizpanda' )
            );

            $forms[] = array(
                'type'      => 'textbox',
                'name'      => 'timeout',
                'title'     => __( 'Timeout of waiting<br />loading the locker (in ms)', 'bizpanda' ),
                'default'   => '20000',
                'hint'      => __( 'The use can have browser extensions which block loading scripts from social networks. If the social buttons have not been loaded within the specified timeout, the locker shows the error (in the red container) alerting about that a browser blocks loading of the social buttons.<br />', 'bizpanda' )
            );
        }
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        return $forms;
    }
}

