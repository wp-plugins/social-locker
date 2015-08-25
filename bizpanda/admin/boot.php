<?php

require_once OPANDA_BIZPANDA_DIR . '/admin/activation.php';
require_once OPANDA_BIZPANDA_DIR . '/admin/troubleshooting.php';
require_once OPANDA_BIZPANDA_DIR . '/admin/bulk-lock.php';
require_once OPANDA_BIZPANDA_DIR . '/admin/helpers.php';
require_once OPANDA_BIZPANDA_DIR . '/extras/visual-composer/boot.php';

// ---
// Pages
//

#comp merge
require_once OPANDA_BIZPANDA_DIR . '/admin/pages/base.php';
require_once OPANDA_BIZPANDA_DIR . '/admin/pages/new-item.php';

require_once OPANDA_BIZPANDA_DIR . '/admin/pages/leads.php';
    
require_once OPANDA_BIZPANDA_DIR . '/admin/pages/stats.php';    
require_once OPANDA_BIZPANDA_DIR . '/admin/pages/settings.php';    
require_once OPANDA_BIZPANDA_DIR . '/admin/pages/how-to-use.php'; 
#endcomp


// ---
// Constants
//

define('OPANDA_DEPENDS_ON_LIST', 'DEPENDS_ON_LIST');

// ---
// Ajax
//

// we include a handler only if the current actions points to a given handler

if ( isset( $_REQUEST['action'] ) ) {
    switch ( $_REQUEST['action'] ) {
        
        case 'onp_sl_preview':
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/preview.php';  
            break;
        case 'opanda_avatar':
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/avatar.php';
            break;      
        case 'opanda_connect':
        case 'opanda_get_subscrtiption_lists':
        case 'opanda_get_custom_fields':            
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/proxy.php';
            break;      
        case 'opanda_loader':
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/shortcode.php';
            break;
        case 'opanda_statistics':
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/stats.php';
            break;
        case 'get_opanda_lockers':
            require OPANDA_BIZPANDA_DIR . '/admin/ajax/tinymce.php';
    }
}


// ---
// Assets
//

/**
 * Adds scripts and styles in the admin area.
 * 
 * @see the 'admin_enqueue_scripts' action
 * 
 * @since 1.0.0
 * @return void
 */
function opanda_admin_assets( $hook ) {
    
    // prints the CSS for a menu item of the Business Panda
  
    ?>
    <style>
        #menu-posts-opanda-item div.wp-menu-image,
        #menu-posts-opanda-item:hover div.wp-menu-image,
        #menu-posts-opanda-item.wp-has-current-submenu div.wp-menu-image {
            background-position: 8px -30px !important;
        }
        #menu-posts-opanda-item .wp-menu-name .onp-sl-panda {
           font-weight: bold;
        }
    </style>
    <?php
}

add_action('admin_enqueue_scripts', 'opanda_admin_assets');


// ---
// Admin Menu
//

/**
 * Removes the default 'new item' from the admin menu to add own pgae 'new item' later.
 * 
 * @see menu_order
 * @since 1.0.0
 */
function opanda_remove_new_item( $menu ) {
    global $submenu;
    if ( !isset( $submenu['edit.php?post_type=' . OPANDA_POST_TYPE] ) ) return $menu;
    unset( $submenu['edit.php?post_type=' . OPANDA_POST_TYPE][10] );
    return $menu;
}

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'opanda_remove_new_item');


/**
 * If the user tried to get access to the default 'new item',
 * redirects forcibly to our page 'new item'.
 *  
 * @see current_screen
 * @since 1.0.0
 */
function opanda_redirect_to_new_item() {
    $screen = get_current_screen();
    
    if ( empty( $screen) ) return;
    if ( 'add' !== $screen->action || 'post' !== $screen->base || OPANDA_POST_TYPE !== $screen->post_type ) return;
    if ( isset( $_GET['opanda_item'] ) ) return;
    
    global $bizpanda;
    
    $url = admin_url('edit.php?post_type=' . OPANDA_POST_TYPE . '&page=new-item-' . $bizpanda->pluginName );
    wp_redirect( $url );
    
    exit;
}

add_action('current_screen', 'opanda_redirect_to_new_item');


// ---
// Editor
//

/**
 * Registers the BizPanda button for the TinyMCE
 * 
 * @see mce_buttons
 * @since 1.0.0
 */
function opanda_register_button($buttons) {  
    
    if ( !current_user_can('edit_' . OPANDA_POST_TYPE) ) return $buttons;
    array_push($buttons, "optinpanda");
    return $buttons;
}

add_filter('mce_buttons', 'opanda_register_button'); 


/**
 * Registers the BizPanda plugin for the TinyMCE 
 * 
 * @see mce_external_plugins
 * @since 1.0.0
 */
function opanda_add_plugin($plugin_array) {  
    
    if ( !current_user_can('edit_' . OPANDA_POST_TYPE) ) return $plugin_array;
    global $wp_version;

    if ( version_compare( $wp_version, '3.9', '<' ) ) {
        $plugin_array['optinpanda'] = OPANDA_BIZPANDA_URL . '/assets/admin/js/optinpanda.tinymce3.js';  
    } else {
        $plugin_array['optinpanda'] = OPANDA_BIZPANDA_URL . '/assets/admin/js/optinpanda.tinymce4.js';  
    }

    return $plugin_array;  
}

add_filter('mce_external_plugins', 'opanda_add_plugin'); 

/**
 * Adds js variable required for shortcodes.
 * 
 * @see before_wp_tiny_mce
 * @since 1.1.0
 */
function opanda_tinymce_data() {

    // styles for the plugin shorcodes
    $shortcodeIcon = BizPanda::getShortCodeIcon();
    $shortcodeTitle = strip_tags( BizPanda::getMenuTitle() );

    ?>
    <style>
        i.onp-sl-shortcode-icon {
            background: url("<?php echo $shortcodeIcon ?>");
        }
    </style>
    <script>
        var bizpanda_shortcode_title = '<?php echo $shortcodeTitle ?>';
    </script>
    <?php
}
add_action( 'before_wp_tiny_mce', 'opanda_tinymce_data' );

// ---
// Key Events
//

/**
 * Calls when anyone subscribed.
 * Adds the subsriber to the table 'leads & emails' and collects some stats data.
 * 
 * @since 1.0.0
 * @return void
 */

/**
 * Calls always when we receive contact data of a visitor.
 */
function opanda_lead_catched( $identity, $context, $emailConfirmed = false, $subscriptionConfirmed = false ) {
    $itemId = isset( $context['itemId'] ) ? intval( $context['itemId'] ) : 0;
    
    if ( empty( $itemId ) || get_post_meta($itemId, 'opanda_catch_leads', true) ) {
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/leads.php';
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php';

        OPanda_Leads::add( $identity, $context, $emailConfirmed, $subscriptionConfirmed );
    }
}

add_action('opanda_lead_catched', 'opanda_lead_catched', 10, 4);

/**
 * Calls always when we subscribe an user.
 */
function opanda_subscribe( $status, $identity, $context ) {

    if ( 'subscribed' == $status ) {
        
        // if the current service is 'database', 
        // then all emails should be added as unconfirmed
        
        $serviceName = BizPanda::getSubscriptionServiceName();
        $confirmed = $serviceName === 'database' ? false : true;
        
        do_action('opanda_lead_catched', $identity, $context, $confirmed, $confirmed );
        
    } elseif ( 'pending' == $status ) {
        do_action('opanda_lead_catched', $identity, $context, false, false  );
    }
}

add_action('opanda_subscribe', 'opanda_subscribe', 10, 3);

/**
 * Calls always when we check the subscription status of the user.
 */
function opanda_check( $status, $identity, $context ) {

    if ( 'subscribed' == $status ) {
        do_action('opanda_lead_catched', $identity, $context, true, true );
    }
}

add_action('opanda_check', 'opanda_check', 10, 3);

/**
 * Calls when a new user is registered.
 */
function opanda_registered( $identity, $context = array() ) {
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php'; 

    $itemId = isset( $context['itemId'] ) ? intval( $context['itemId'] ) : 0;
    $postId = isset( $context['postId'] ) ? intval( $context['postId'] ) : null;
    
    OPanda_Stats::countMetrict( $itemId, $postId, 'account-registered');
}
add_action('opanda_registered', 'opanda_registered', 10, 2 );

/**
 * Calls when a new user is followerd on Twitter.
 */
function opanda_got_twitter_follower( $context = array() ) {
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php'; 
    
    $itemId = isset( $context['itemId'] ) ? intval( $context['itemId'] ) : 0;
    $postId = isset( $context['postId'] ) ? intval( $context['postId'] ) : null;

    OPanda_Stats::countMetrict( $itemId, $postId, 'got-twitter-follower');
}

add_action('opanda_got_twitter_follower', 'opanda_got_twitter_follower');

/**
 * Calls when a new user places a tweet.
 */
function opanda_tweet_posted( $context = array() ) {
    require_once OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php'; 
    
    $itemId = isset( $context['itemId'] ) ? intval( $context['itemId'] ) : 0;
    $postId = isset( $context['postId'] ) ? intval( $context['postId'] ) : null;

    OPanda_Stats::countMetrict( $itemId, $postId, 'tweet-posted');
}

add_action('opanda_tweet_posted', 'opanda_tweet_posted');


// ---
// View Table
//

// includes the view table only if the current page is the list of panda items
if ( isset( $_GET['post_type'] ) && OPANDA_POST_TYPE === $_GET['post_type'] ) {
    
    function opanda_filter_panda_items_in_view_table() {
        global $wp_query;
        
        $names = OPanda_Items::getAvailableNames();
        
        $wp_query->query_vars['meta_key'] = 'opanda_item';
        $wp_query->query_vars['meta_value'] = OPanda_Items::getAvailableNames();
    }
    add_action( 'pre_get_posts', 'opanda_filter_panda_items_in_view_table' );
    
    require OPANDA_BIZPANDA_DIR . '/admin/includes/classes/class.lockers.viewtable.php';
}

// ---
// Metaboxes
//


/**
 * Registers default options (lockers, popups, forms).
 * 
 * @since 1.0.0
 */
function opanda_add_meta_boxes() {
    global $bizpanda;
    
    $type = OPanda_Items::getCurrentItem();
    if ( empty( $type ) ) return;
    
    $typeName = $type['name'];
    
    $data = array();

    if ( OPanda_Items::isCurrentPremium() ) {

        $data[] = array(
            'class' => 'OPanda_BasicOptionsMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/basic-options.php'
        );

        $data[] = array(
            'class' => 'OPanda_PreviewMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/preview.php'
        );

        $data[] = array(
            'class' => 'OPanda_ManualLockingMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/manual-locking.php'
        );

        $data[] = array(
            'class' => 'OPanda_BulkLockingMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/bulk-locking.php'
        );

        $data[] = array(
            'class' => 'OPanda_VisabilityOptionsMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/visability-options.php'
        );

        $data[] = array(
            'class' => 'OPanda_AdvancedOptionsMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/advanced-options.php'
        );

    } else {

        $data[] = array(
            'class' => 'OPanda_BasicOptionsMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/basic-options.php'
        );

        $data[] = array(
            'class' => 'OPanda_PreviewMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/preview.php'
        );

        $data[] = array(
            'class' => 'OPanda_ManualLockingMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/manual-locking.php'
        );

        $data[] = array(
            'class' => 'OPanda_BulkLockingMetaBox',
            'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/bulk-locking.php'
        );
    }

    $data = apply_filters( "opanda_item_type_metaboxes", $data, $typeName );
    $data = apply_filters( "opanda_{$typeName}_type_metaboxes", $data );
    
    foreach( $data as $metabox ) {
        require_once $metabox['path'];
        FactoryMetaboxes321::registerFor( new $metabox['class']( $bizpanda ), OPANDA_POST_TYPE, $bizpanda);
    }
}

add_action( 'init', 'opanda_add_meta_boxes' );