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
            'hint'      => __( 'If this option turned on, the plugin displays information about why the locker is not visible.', 'bizpanda' )
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
            'type'      => 'textbox',
            'name'      => 'session_duration',
            'title'     => __( 'Session Duration<br />(in secs)', 'bizpanda' ),
            'hint'      => __( 'Optional. The session duration used in the advanced Visiblity Options. The default value 900 seconds (15 minutes).', 'bizpanda' ),
            'default'   => 900
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'session_freezing',
            'title'     => __( 'Session Freezing', 'bizpanda' ),
            'hint'      => __( 'Optional. If On, the length of users\' sessions is fixed, by default the sessions are prolonged automatically every time when a user visits your website for a specified value of the session duration.', 'bizpanda' ),
            'default'   => false
        );  
        
        if ( BizPanda::hasPlugin('sociallocker') ) {
            
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
        
        }

        $forms[] = array(
            'type' => 'separator'
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
        
        $forms[] = array(
            'type' => 'separator'
        );
         
        $forms[] = array(
            'type' => 'html',
            'html' =>   '<div class="col-md-offset-2" style="padding: 30px 0 10px 0;">' . 
                            '<strong style="font-size: 15px;">' . __('Advanced Options', 'bizpanda') . '</strong>' .
                            '<p>' . __('Please don\'t change these options if everything works properly.', 'bizpanda') . '</p>' .
                        '</div>'
        ); 

        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'normalize_markup',
            'title'     => __( 'Normalize Markup', 'bizpanda' ),
            'hint'      => __( 'Optional. If you use the Batch Lock and the locker appears incorrectly, probably HTML markup of your page is broken. Try to turn on this option and the plugin will try to normalize html markup before output.', 'bizpanda' )
        );
        
        $forms[] = array(
            'type' => 'separator'
        );
        
        $forms[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'dynamic_theme',
            'title'     => __( 'I use a dynamic theme', 'bizpanda' ),
            'hint'      => __( 'If your theme loads pages dynamically via ajax, set "On" to get the lockers working (if everything works properly, don\'t turn on this option).', 'bizpanda' )
        );

        $forms[] = array(
            'type'      => 'textbox',
            'way'       => 'buttons',
            'name'      => 'managed_hook',
            'title'     => __( 'Creater Trigger', 'bizpanda' ),
            'hint'      => __( 'Optional. Set any jQuery trigger bound to the root document to create lockers. By default lockers are created on loading a page.', 'bizpanda' )
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
            'name'      => 'hide_content_on_loading',
            'default'   => false,
            'title'     => __( 'Hide Content On Loading', 'bizpanda' ),
            'hint'      => __( 'By default if the blurring or transparent mode is used, the content may be visible during a short time before the locker appears. Set this option to "On" to hide the locked content when the page is loading until the locker is created.', 'bizpanda' )
        );
        
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

