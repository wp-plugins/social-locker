<?php

/**
 * The base class of the BizPanda Framework.
 * 
 * @since 1.0.0
 */
class BizPanda {
    
    /**
     * Stores the number of the plugins using the BizPanda Framework.
     * 
     * @since 1.0.0
     * @var int 
     */
    protected static $pluginCount = 1;

    /**
     * Returns the number of plugins using the BizPanda Framework.
     * 
     * @since 1.0.0
     * @var int 
     */
    public static function getPluginCount() {
        return count( self::$_installedPlugins );
    }
    
    /**
     * [obsoleted]
     * 
     * @since 1.0.0
     * @var void 
     */
    public static function countCallerPlugin() {
        // nothing
    }
    
    /**
     * Returns true if only one plugin is usigin the  Framework.
     * 
     * @since 1.0.0
     * @var bool 
     */
    public static function isSinglePlugin() {
        return count( self::$_installedPlugins ) == 1;
    }
    
    protected static $_features = array();
    
    public static function hasFeature( $featureName ) {
        return isset( self::$_features[$featureName] ) && self::$_features[$featureName];
    }
    
    public static function enableFeature( $featureName ) {
        self::$_features[$featureName] = true;
    }
    
    public static function disableFeature( $featureName ) {
        self::$_features[$featureName] = true;
    } 
    
    
    protected static $_plugins = array();
    protected static $_installedPlugins = array();
    
    protected static $_hasPremiumPlugins = false;
    
    public static function hasPlugin( $name ) {
        return isset( self::$_plugins[$name] );
    }
    
    public static function registerPlugin( $plugin, $name = null, $type = null ) {
        $pluginName = empty( $name ) ? $plugin->pluginName : $name;
        $pluginType = empty( $type ) ? ( $plugin->options['build'] !== 'free' ? 'premium' : 'free' ) : $type;
        
        if ( !isset( self::$_plugins[$pluginName] ) ) self::$_plugins[$pluginName] = array();
        self::$_plugins[$pluginName][$pluginType] = $plugin;
        
        self::$_installedPlugins[] = array(
            'name' => $pluginName,
            'type' => $type,
            'plugin' => $plugin
        );

        self::$_hasPremiumPlugins = self::$_hasPremiumPlugins || 'premium' === $pluginType;
    }  

    public static function hasDefaultMenuIcon() {
        $default = OPANDA_BIZPANDA_URL . '/assets/admin/img/menu-icon.png';
        $current = self::getMenuIcon();
        return $default == $current;        
    }
    
    public static function getMenuIcon() {
        $default = OPANDA_BIZPANDA_URL . '/assets/admin/img/menu-icon.png';
        return apply_filters('opanda_menu_icon', $default );
    }
    
    public static function getShortCodeIcon() {
        $default = OPANDA_BIZPANDA_URL . '/assets/admin/img/opanda-shortcode-icon.png';
        return apply_filters('opanda_shortcode_icon', $default );
    }
    
    public static function getMenuTitle() {
        $menuTitle = __('Biz<span class="onp-sl-panda">Panda</span>', 'bizpanda');
        return apply_filters('opanda_menu_title', $menuTitle );      
    }
    
    public static function getSubscriptionServiceName() {
        return get_option('opanda_subscription_service', 'database'); 
    }
    
    public static function hasPremiumPlugins() {
        return self::$_hasPremiumPlugins;
    }
    
    public static function getPlugin() {

        if ( isset( self::$_installedPlugins[0] )) return self::$_installedPlugins[0]['plugin'];
        return false;
    }

    public static function getPluginNames( $full = false ) {
        if ( !$full ) return array_keys( self::$_plugins );
        
        $names = array();
        foreach( self::$_installedPlugins as $pluginInfo ) {
            $plugin = self::$_installedPlugins[0]['plugin'];
            $name = $plugin->options['name'] . '-' .$plugin->options['assembly']; 
            $names[] = $name;
        }
        
        return $names;
    }
    
    public static function getInstalledPlugins() {
        return self::$_installedPlugins;
    }
    
    public static function hasInstalled( $pluginName, $pluginType = null ) {
        
        if ( empty( $pluginType ) ) {
            return isset( self::$_plugins[$pluginName] );
        } else {
            return isset( self::$_plugins[$pluginName][$pluginType] );
        }
    }
}

/**
 * Returns an URL of the admin page of Business Panda.
 * 
 * @since 1.0.0
 * 
 * @param string $page A page id (for example, how-to-use).
 * @param array $args Extra query args.
 * @return string
 */
function opanda_get_admin_url( $page = 'how-to-use', $args = array() ) {
    $baseUrl = admin_url('edit.php?post_type=' . OPANDA_POST_TYPE);
    
    $args['page'] = $page . '-bizpanda';
    return add_query_arg( $args, $baseUrl );
}

function opanda_get_help_url( $page = null ) {
    return opanda_get_admin_url( 'how-to-use', array('onp_sl_page' => $page) );
}

function opanda_get_subscribers_url() {
    return opanda_get_admin_url('leads');
}

function opanda_get_settings_url( $screen ) {
    return opanda_get_admin_url( 'settings', array('opanda_screen' => $screen) ); 
}

function opanda_proxy_url() {
    $url = admin_url('admin-ajax.php');
    return add_query_arg(array(
        'action' => 'opanda_connect'
    ), $url);
}

function opanda_terms_url() {
    $enabled = get_option('opanda_terms_enabled', false);
    if ( empty( $enabled ) ) return false;
    
    $usePages = get_option('opanda_terms_use_pages', false);
    if ( $usePages ) {
        
        $pageId = get_option('opanda_terms_of_use_page', false);   
        if ( empty( $pageId ) ) return false;
        return get_permalink( $pageId );
        
    } else {

        return add_query_arg(array(
            'bizpanda' => 'terms-of-use'
        ), site_url() );
        
    }
}

function opanda_privacy_policy_url() {
    $enabled = get_option('opanda_terms_enabled', false);
    if ( empty( $enabled ) ) return false;
    
    $usePages = get_option('opanda_terms_use_pages', false);
    if ( $usePages ) {
        
        $pageId = get_option('opanda_terms_of_use_page', false);   
        if ( empty( $pageId ) ) return false;
        return get_permalink( $pageId );
        
    } else {

        return add_query_arg(array(
            'bizpanda' => 'privacy-policy'
        ), site_url() );
        
    }
}

/**
 * Returns the global option for the panda item.
 * 
 * @since 1.0.0
 */
function opanda_get_option( $id, $default = null ) {
    return get_option( 'opanda_' . $id, $default ) ;
}

/**
 * Returns the option for a given panda item.
 * 
 * @since 1.0.0
 */
function opanda_get_item_option( $id, $name, $isArray = false, $default = null ) {
    $options = opanda_get_item_options( $id );
    $value = isset( $options['opanda_' . $name] ) ? $options['opanda_' . $name] : null;

    return ($value === null || $value === '')
        ? $default 
        : ( $isArray ? maybe_unserialize($value) : stripslashes( $value ) ); 
}

/**
 * Replaces the variables in URLs {var} and return the result URL.
 *
 * @since 1.1.3
 */
function opanda_get_dynamic_url( $id, $name, $default = null ) {
    $url = opanda_get_item_option( $id, $name, false );
    
    if( empty( $url ) ) return $default;
    return preg_replace_callback("/\{([^}]+)\}/", 'opanda_get_dunamic_url_callback', $url);
}

/**
 * A callback for 'preg_replace_callback' in the function opanda_get_dunamic_url.
 * 
 * @since 1.1.3
 */
function opanda_get_dunamic_url_callback( $match ) {
    if( array_key_exists( $match[1], $_REQUEST ) ) return $_REQUEST[$match[1]];
    return $match[0];
}

/**
 * Cache for the locker options.
 */
global $opanda_item_options;
$opanda_item_options = array();

/**
 * Returns all the options for a given panda item.
 * 
 * @since 1.0.0
 */
function opanda_get_item_options( $id ) {
    global $opanda_item_options;
    if ( isset( $opanda_item_options[$id] ) ) return $opanda_item_options[$id];
    
    $options = get_post_meta($id, '');

    $real = array();
    foreach($options as $key => $values) {
        if ( !strpos($key, '__arr') ) $real[$key] = $values[0];
        else $real[$key] = $values;
    }

    $opanda_item_options[$id] = $real;
    return $real;
}

/**
 * Returns the connect handler options.
 * 
 * @since 1.0.0
 */
function opanda_get_handler_options( $handlerName ) {
    
    switch ( $handlerName ) {
        case 'twitter':
            
            $consumerKey = 'Fr5DrCse2hsNp5odQdJOexOOA';
            $consumerSecret = 'jzNmDGYPZOGV10x2HmN8tYMDqnMTowycXFu4xTTLbw3VBVeFKm';
            
            $optDefaultKeys = get_option('opanda_twitter_use_dev_keys', 'default');
            if ( 'default' !== $optDefaultKeys ) {
                $consumerKey = get_option('opanda_twitter_consumer_key');
                $consumerSecret = get_option('opanda_twitter_consumer_secret');        
            }
            
            return array(
                'consumer_key' => $consumerKey,
                'consumer_secret' => $consumerSecret,
                'proxy' => opanda_proxy_url()
            );

        case 'subscription':
            
            return array(
                'service' => get_option('opanda_subscription_service', 'database')
            );
            
        case 'signup':
            
            return array(
                'mode' => get_option('opanda_signup_mode', 'hidden')
            );
    }
}

/**
 * Normilize the values after receving them via ajax.
 * 
 * @since 1.0.0
 */
function opanda_normilize_values( $values = array() ) {
    if ( empty( $values) ) return $values;
    if ( !is_array( $values ) ) $values = array( $values );

    foreach ( $values as $index => $value ) {

        $values[$index] = is_array( $value )
                    ? opanda_normilize_values( $value ) 
                    : opanda_normilize_value( $value );
    }

    return $values;
}

/**
 * Normilize the value after receving them via ajax.
 * 
 * @since 1.0.0
 */
function opanda_normilize_value( $value = null ) {
    if ( 'false' === $value ) $value = false;
    elseif ( 'true' === $value ) $value = true;
    elseif ( 'null' === $value ) $value = null;
    return $value;
}

/**
 * Returns a website robust key to load failed assets.
 * 
 * @since 1.1.3
 */
function opanda_get_robust_key() {
    $key = get_option('opanda_robust_key', false);
    if ( empty( $key ) ) {
        $key = substr( md5( NONCE_SALT ), 0, rand(5,15) );
        update_option( 'opanda_robust_key', $key );
    }
    return $key;
}

/**
 * Returns a website robust script key to load the locker script.
 * 
 * @since 1.1.3
 */
function opanda_get_robust_script_key() {
    $key = get_option('opanda_robust_script_key', false);
    if ( empty( $key ) ) {
        $key = substr( md5( NONCE_SALT ), 15, rand(5,15) );
        update_option( 'opanda_robust_script_key', $key );
    }
    return $key;
}

/**
 * Returns available lockers.
 * 
 * @since 1.1.3
 */
function opanda_get_lockers( $lockerType = null, $output = null ) {
    
    $lockers = get_posts(array(
        'post_type' => OPANDA_POST_TYPE,
        'meta_key' => 'opanda_item',
        'meta_value' => empty( $lockerType ) ? OPanda_Items::getAvailableNames() : $lockerType,
        'numberposts' => -1
    ));
    
    foreach( $lockers as $locker ) {
        $locker->post_title = empty( $locker->post_title ) 
            ? sprintf( __( '(no titled, ID=%s)' ), $locker->ID )
            : $locker->post_title;
    } 
    
    if ( 'vc' === $output ) {
        
        $result = array();
        foreach ( $lockers as $locker ) $result[$locker->post_title] = $locker->ID;
        return $result;
    }
    
    return $lockers;
}

// ---------------------------------
// Move to hooks.php
// ---------------------------------

/**
 * Handles a frontend action linked with bizpanda.
 * 
 * @since 1.1.0
 * @return void
 */
function bizpanda_frontend_action() {
    $robustKey = opanda_get_robust_key();
    
    if ( isset( $_REQUEST['bizpanda'] ) ) {
        
        $action = $_REQUEST['bizpanda'];

        if ( 'terms-of-use' === $action ) {
            return bizpanda_show_terms_of_use();
        }

        if ( 'privacy-policy' === $action ) {
            return bizpanda_show_privacy_policy();
        }    

    } else if ( isset( $_REQUEST[$robustKey] ) ) {
        
        $action = $_REQUEST[$robustKey];
        
        if ( opanda_get_robust_script_key() === $action ) {
            echo file_get_contents(OPANDA_BIZPANDA_DIR . '/assets/js/lockers.010101.min.js');
            exit;
        }
    }
}
add_action('template_redirect', 'bizpanda_frontend_action');

/**
 * Displays the text of the Terms of Use.
 * 
 * @since 1.1.0
 * @return void
 */
function bizpanda_show_terms_of_use() {
    
    $enabled = get_option('opanda_terms_enabled', false);
    if ( empty( $enabled ) ) return;
    
    $usePages = get_option('opanda_terms_use_pages', false);
    if ( $usePages ) return;
    
    ?>

    <html>
        <title><?php echo get_bloginfo('name'); ?></title>
        <link rel='stylesheet' href='<?php echo OPANDA_BIZPANDA_URL . '/assets/css/terms.010000.css' ?>' type='text/css' media='all' />
        <body>
            <?php echo get_option('opanda_terms_of_use_text', false); ?>
        </body>
    <html>
        
    <?php
    exit;
}

/**
 * Displays the text of the Privacy Policy.
 * 
 * @since 1.1.0
 * @return void
 */
function bizpanda_show_privacy_policy() {
    
    $enabled = get_option('opanda_terms_enabled', false);
    if ( empty( $enabled ) ) return;
    
    $usePages = get_option('opanda_terms_use_pages', false);
    if ( $usePages ) return;
    
    ?>
        
    <html>
        <title><?php echo get_bloginfo('name'); ?></title>
        <link rel='stylesheet' href='<?php echo OPANDA_BIZPANDA_URL . '/assets/css/terms.010000.css' ?>' type='text/css' media='all' />
        <body>
            <?php echo get_option('opanda_privacy_policy_text', false); ?>
        </body>
    <html>
        
    <?php
    exit;
}