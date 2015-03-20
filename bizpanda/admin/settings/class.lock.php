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
            <?php _e('Options linked with the locking feature. Don\'t change the options here if you are not sure that you do.', 'optinpanda' )?>
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
            'title'     => __( 'Debug', 'optinpanda' ),
            'hint'      => __( 'When this option turned on, the locker appears always, ignoring any settings, even if the user already unlocked the content.', 'optinpanda' )
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'interrelation',
            'title'     => __( 'Interrelation', 'sociallocker' ),
            'hint'      => __( 'Set On to make lockers interrelated. When one of the interrelated lockers are unlocked on your site, the others will be unlocked too.<br /> Recommended to turn on, if you use the Batch Locking feature.', 'sociallocker' ),
            'default'   => false
        );

        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'dynamic_theme',
            'title'     => __( 'I use a dynamic theme', 'optinpanda' ),
            'hint'      => __( 'If your theme loads pages dynamically via ajax, set "On" to get the lockers working (if everything works properly, don\'t turn on this option).', 'optinpanda' )
        );
        
        $forms[] = array(
            'type'      => 'div',
            'id'        => 'onp-dynamic-theme-options',
            'items'     => array(

                array(
                    'type'      => 'textbox',
                    'name'      => 'dynamic_theme_event',
                    'title'     => __( 'jQuery Events', 'optinpanda' ),
                    'hint'      => __( 'If pages of your site are loaded dynamically via ajax, it\'s necessary to catch ' . 
                                   'the moment when the page is loaded in order to appear the locker.<br />By default the plugin covers ' .
                                   '99% possible events. So <strong>you don\'t need to set any value here</strong>.<br />' .
                                   'But if you know how it works and sure that it will help, you can put here the javascript event ' .
                                   'that triggers after loading of pages on your site.', 'optinpanda' )
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
                array( 'full', __('Classic (full)', 'optinpanda') ),
                array( 'transparence', __('Transparency', 'optinpanda') )   
            ),
            'default'   => 'transparence',
            'title'     => __( 'Alt Overlap Mode', 'optinpanda' ),
            'hint'      => __( 'This overlap mode will be applied for browsers which don\'t support the blurring effect.', 'optinpanda' )
        );

        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'rss',
            'title'     => __( 'Locked content<br /> is visible in RSS feeds', 'optinpanda' ),
            'hint'      => __( 'Set On to make locked content visible in RSS feed.', 'optinpanda' ),
            'default'   => false
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'actual_urls',
            'title'     => __( 'Actual URLs by default', 'optinpanda' ),
            'hint'      => __( 'Optional. If you do not set explicitly URLs to like/share in the settings of social buttons, then by default the plugin will use an URL of the page where the locker is located. Turn on this option to extract URLs to like/share from an address bar of the user browser, saving all query arguments. By default (when this option disabled) permalinks are used.', 'optinpanda' ),
            'default'   => false
        );

        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'tumbler',
            'title'     => __( 'Anti-Cheating', 'optinpanda' ),
            'default'   => false,
            'hint'      => __( 'Turn it on to protect your locked content against cheating from visitors. Some special browser extensions allow to view the locked content without actual sharing. This option checks whether the user has really liked/shared your page. In future versions of the plugin, we will make this option active by default.', 'optinpanda' )
        );
        
        /**
         * Not supported yet
        $forms[] = array(
            'type'      => 'dropdown',
            'name'      => 'na_mode',
            'data'      => array(
                array( 'show-error', __('Show the error', 'optinpanda') ),
                array( 'show-content', __('Remove the locker, show the content', 'optinpanda') )
            ),
            'title'     => __( 'If N/A, what to do?', 'optinpanda' ),
            'default'   => false,
            'hint'      => __( 'Optional. Select what the locker should to do if the social buttons have not been loaded. At this case, the locker is not available to use. It occurs if the visitor uses the extensions like Avast or Adblock which may block social networks. By default the locker shows the error and the offer to disable the extensions.<br /><i>How to test? Set the option "Timeout of waiting loading the locker (in ms)" to 1</i>.', 'optinpanda' )
        );
        */
        
        $forms[] = array(
            'type'      => 'textbox',
            'name'      => 'timeout',
            'title'     => __( 'Timeout of waiting<br />loading the locker (in ms)', 'optinpanda' ),
            'default'   => '20000',
            'hint'      => __( 'The use can have browser extensions which block loading scripts from social networks. If the social buttons have not been loaded within the specified timeout, the locker shows the error (in the red container) alerting about that a browser blocks loading of the social buttons.<br />', 'optinpanda' )
        );
 
        $forms[] = array(
            'type' => 'separator'
        );
        
        return $forms;
    }
}

