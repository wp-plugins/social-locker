<?php

class OnpSL_AssetsManager {
    
    private static $_requested = false;
    private static $_createrScriptPrinted = false;
    private static $_cssOptionsToPrint = array();
    
    public static function init() {
        self::initBulkLocking();
        self::iniDynamicThemes();
    }
    
    /**
     * Requests connection social locker assets on a current page.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function requestAssets( $fromBody = false, $fromHook = false ) {

        if ( self::$_requested ) return;
        self::$_requested = true;
        
        add_action( 'wp_footer', 'OnpSL_AssetsManager::printCreaterScript', 9999 );

        if ( $fromBody || $fromHook ) {
            OnpSL_AssetsManager::connectAssets(); 
        } else {
            add_action( 'wp_enqueue_scripts', 'OnpSL_AssetsManager::connectAssets' );
        }
        
        if ( !$fromBody ) {
            add_action( 'wp_head', 'OnpSL_AssetsManager::printFacebookScript' );
        } else {
            add_action( 'wp_footer', 'OnpSL_AssetsManager::printFacebookScript', 1 );  
        }
    }
        
    /**
     * Prints a script that creates social lockers via css selectors
     * 
     * @since 1.0.0
     * @return void
     */
    public static function printCreaterScript() {
        if ( self::$_createrScriptPrinted ) return;
        self::$_createrScriptPrinted = true;
    ?>
        <!-- 
            Creater Script
        
            Created by the Social Locker plugin (c) OnePress Ltd
            http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
            (function($){ if ( window.onpsl && window.onpsl.lockers ) { window.onpsl.lockers(); } else 
            { $(function(){ window.onpsl.lockers(); }); } })(jQuery);
        </script>
        <!-- / -->
    <?php
    }
    
    /**
     * Loades and initiing Facebook SDK.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function printFacebookScript() {

        $appId = get_option('sociallocker_facebook_appid');
        $lang = get_option('sociallocker_lang', 'en_US');

        ?>
        <!-- 
            Facebook SDK
        
            Created by the Social Locker plugin (c) OnePress Ltd
            http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
            window.fbAsyncInit = function() {
                window.FB.init({
                    appId: <?php echo $appId ?>,
                    status: true,
                    cookie: true,
                    xfbml: true
                });
                window.FB.init = function(){};
            };
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/<?php echo $lang ?>/all.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- / -->
        <?php
    }
    
    public static function printCssSelectorOptions() {
        ?>
        <!-- 
            Social Locker CSS Selectors (Bulk Locking)

            Created by the Social Locker plugin (c) OnePress Ltd
            http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
            if ( !window.onpsl ) window.onpsl = {};
            window.onpsl.bulkCssSelectors = [];
            <?php foreach( self::$_cssOptionsToPrint as $options ) { ?>
            window.onpsl.bulkCssSelectors.push({
                lockId: '<?php echo $options['locker-options-id'] ?>',
                selector: '<?php echo $options['css-selector'] ?>'
            });
            <?php } ?>
        </script>
        <style>
            <?php foreach( self::$_cssOptionsToPrint as $options ) { ?>
            <?php echo $options['css-selector'] ?> { display: none; }
            <?php } ?>
        </style>
        <!-- / -->
        <?php
        
        self::$_cssOptionsToPrint = array();
    }
    
    /**
     * Conencts scripts and styles of Social Locker.
     * 
     * @sincee 1.0.0
     * @return void
     */
    public static function connectAssets() {

        wp_enqueue_style( 
            'onp-sociallocker', 
            ONP_SL_PLUGIN_URL . '/assets/css/jquery.op.sociallocker.030000.css'
        );  

        wp_enqueue_script( 
            'onp-sociallocker', 
            ONP_SL_PLUGIN_URL . '/assets/js/jquery.op.sociallocker.min.030000.js', 
            array('jquery', 'jquery-effects-core', 'jquery-effects-highlight'), false, true
        );  

        $facebookSDK = array( 
            'appId' => get_option('sociallocker_facebook_appid'),
            'lang' => get_option('sociallocker_lang', 'en_US') 
        ); 

        wp_localize_script( 'onp-sociallocker', 'facebookSDK', $facebookSDK );
    }
    
    // -----------------------------------------------
    // Working with locker options.
    // -----------------------------------------------
    
    private static $_lockerOptions = array();
    private static $_lockerOptionsToPrint = array();
    
    /**
     * Prints locker options.
     * 
     * @since 1.0.0
     * @global type $post
     */
    public static function printLockerOptions() {
        
        $data = array();
        
        foreach(self::$_lockerOptionsToPrint as $name => $id) {
            $lockData = self::getLockerDataToPrint( $id );
            
            $data[] = array(
                'name' => $name,
                'options' => $lockData
            );
        }
        ?>
        <!-- 
            Options of Bulk Lockers
        
            Created by the Social Locker plugin (c) OnePress Ltd
            http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
        if ( !window.onpsl ) window.onpsl = {};
        if ( !window.onpsl.lockerOptions ) window.onpsl.lockerOptions = {};
        <?php foreach( $data as $item ) { ?>
            window.onpsl.lockerOptions['<?php echo $item['name'] ?>'] = <?php echo json_encode( $item['options'] ) ?>;
        <?php } ?>
        </script>
        <!-- / -->
        <?php
        
        self::$_lockerOptionsToPrint = array();
    }
    
    public static function getLockerDataToPrint( $id, $lockData = array() ) {
        global $post;
                    
        $lockData['ajaxUrl'] = admin_url( 'admin-ajax.php' );
        $lockData['lockerId'] = $id;

        // Check tracking request

        $lockData['tracking'] = get_option('sociallocker_tracking', true);
        $lockData['postId'] = !empty($post) ? $post->ID : false;

        // Builds array of options to set into the jquery plugin

            $url = self::getLockerOption($id, 'common_url' );
            if ( empty($url) && !empty($post) ) {
                $url = get_permalink( $post->ID );
            }

            // FREE build options
            $params = array(
                'demo' => get_option('sociallocker_debug', false),

                'text' => array(
                    'header' => self::getLockerOption($id, 'header'), 
                    'message' => self::getLockerOption($id, 'message')             
                ),

                'theme' => 'secrets',

                'facebook' => array(
                    'url' => $url,
                    'appId' => get_option('sociallocker_facebook_appid', '117100935120196'),
                    'lang' => get_option('sociallocker_lang', 'en_GB'),
                ),
                'twitter' => array(
                    'url' => $url,     
                    'lang' => get_option('sociallocker_short_lang', 'en'),
                    'counturl' => self::getLockerOption($id, 'twitter_counturl')
                ),  
                'google' => array(
                    'url' => $url,    
                    'lang' => get_option('sociallocker_google_lang', get_option('sociallocker_short_lang', 'en' ))
                )  
            );

        


        if ( 
           !isset( $params['buttons'] ) || 
           !isset( $params['buttons']['order'] ) || 
            empty( $params['buttons']['order'] ) ) {

            unset( $params['buttons'] );
        }

        // - Replaces shortcodes in the locker message and twitter text

        $postTitle = $post != null ? $post->post_title : '';
        $postUrl = $post != null ? get_permalink($post->ID) : '';

        if ( !empty($params['twitter']['tweet']['text'] ) ) {
            $params['twitter']['tweet']['text'] = str_replace('[post_title]', $postTitle, $params['twitter']['tweet']['text']);
        }

        if ( !empty($params['text'] ) ) {
            $params['text'] = str_replace('[post_title]', $postTitle, $params['text']);
            $params['text'] = str_replace('[post_url]', $postUrl, $params['text']);  
        }

        if (empty( $params['text']['header'] )) {
            $params['text'] = $params['text']['message'];
        }

        self::_normilizeLockerOptions( $params );
        $lockData['options'] = $params;

        return $lockData;
    }
    
    /**
     * Returns locker options.
     * 
     * @since 1.0.0
     * @param integer $lockerId
     * @return mixed
     */
    public static function getLockerOptions( $lockerId ) {

        if ( isset( self::$_lockerOptions[$lockerId] ) ) return self::$_lockerOptions[$lockerId];
        $options = get_post_meta($lockerId, '');
        
        $real = array();
        foreach($options as $key => $values) {
            $real[$key] = $values[0];
        }
        
        self::$_lockerOptions[$lockerId] = $real;
        return $real;
    }
    
    /**
     * Returns a locker option.
     * 
     * @since 1.0.0
     * @param integer $lockerId
     * @param string $name
     * @param boolean $isArray
     * @param mixed $default
     */
    public static function getLockerOption( $lockerId, $name, $isArray = false, $default = null ) {
        $options = self::getLockerOptions($lockerId);
        $value = isset( $options['sociallocker_' . $name] ) ? $options['sociallocker_' . $name] : null;

        return empty( $value ) 
            ? $default 
            : ( $isArray ? maybe_unserialize($value) : stripslashes( $value ) ); 
    }
    
    /**
     * Notmilized locker options.
     * 
     * @since 1.0.0
     * @param type $params
     */
    private static function _normilizeLockerOptions( &$params ) {
        
        foreach( $params as $key => &$item ) {

            if ( $item === '' || $item === null || $item === 0 ) {
                unset( $params[$key] );
                continue;
            }

            if ( $item === 'true' ) {
                $params[$key] = true;
                continue;
            }            

            if ( $item === 'false' ) {
                $params[$key] = false;
                continue;
            }               

            if ( gettype($item) == 'array' ) {
                self::_normilizeLockerOptions( $params[$key] );
            }
        }
    }
       
    // -----------------------------------------------
    // Bulk Locking
    // -----------------------------------------------
    
    /**
     * Init bulk lockers.
     * 
     * The method gets array of all bulk lockers and tries to understand which of them 
     * are suitable for a current page. If a bulk locker is suitable, then the assets will be 
     * included in the <head> section of a current page. 
     * 
     * @since 3.0.0
     * @return void
     */
    public static function initBulkLocking() {
        
        $bulkLockers = get_option('onp_sl_bulk_lockers', array());
        if ( empty($bulkLockers) ) return;
                
        foreach($bulkLockers as $id => $options) {
            
            // if we have bulk lockers based on css selectors, then we have include
            // assets on every page and also print which css selectors we will use for
            // social locker creater script
            
            if ( $options['way'] == 'css-selector' ) {
                
                self::$_lockerOptionsToPrint['css-selector-' . $id] = $id;
                self::$_cssOptionsToPrint[] = array(
                    'locker-options-id' => 'css-selector-' . $id,
                    'css-selector' => $options['css_selector']
                );
                
                self::requestAssets();
                
            // if we have lockers based on the 'skip-lock' and 'more-tag' rules,
            // we need check if a current page is excluded
                
            } else {
                if ( !is_singular( $options['post_types'] ) ) continue;
                if ( !self::isPageExcluded( $id, $options ) ) {
                    self::requestAssets();
                    continue;
                }
            }
        }
        
        if ( !empty( self::$_cssOptionsToPrint ) ) {
            add_action( 'wp_head', 'OnpSL_AssetsManager::printCssSelectorOptions' );
            add_action( 'wp_head', 'OnpSL_AssetsManager::printLockerOptions' );
        } 
        
        add_filter('the_content', 'OnpSL_AssetsManager::addSocialLockerShortcodes', 1);
    }
    
    /**
     * Cache for the method isPageExcluded.
     * 
     * @since 3.0.0
     * @var mixed
     */
    private static $_cache_isPageExcluded = array();
    
    /**
     * Checks if a current page is exluded to show the bulk lockers 
     * based on the 'skip-lock' and 'more-tag' rules
     * 
     * @since 3.0.0
     * @param mixed $options
     * @return boolean
     */
    private static function isPageExcluded( $id, $options ) {
        global $post;
        if (empty($post)) return true;
        
        $key = $id . '' . $post->ID; 
        if ( isset( self::$_cache_isPageExcluded[$key] ) ) return self::$_cache_isPageExcluded[$key];

        if ( !in_array( $post->post_type, $options['post_types'] ) ) { 
            self::$_cache_isPageExcluded[$key] = true;
            return true;
        } 
           
        if ( empty( $options['exclude_posts'] ) && empty( $options['exclude_categories']  ) ) {
            self::$_cache_isPageExcluded[$key] = false;
            return false;
        }    

        if ( in_array( $post->ID, $options['exclude_posts'] ) ) {
            self::$_cache_isPageExcluded[$key] = true;
            return true;
        }

        $isPostCategoryExcluded = false;
        foreach(get_the_category() as $category) {
            if ( in_array( $category->cat_ID, $options['exclude_categories'] ) ) {
                $isPostCategoryExcluded = true;
            }
        }
        
        self::$_cache_isPageExcluded[$key] = $isPostCategoryExcluded;
        return $isPostCategoryExcluded;
    }
     
    /**
     * Adds a locker shortcodes on the flight if bulk locking are turned on.
     * 
     * @param type $content
     * @return type
     */
    public static function addSocialLockerShortcodes( $content ) {
        $bulkLockers = get_option('onp_sl_bulk_lockers', array());
        if ( empty($bulkLockers) ) return $content;

        foreach($bulkLockers as $id => $options) {
            if ( !in_array( $options['way'], array('skip-lock', 'more-tag') ) ) continue;
            if ( self::isPageExcluded( $id, $options ) ) continue;

            if ( $options['way'] == 'skip-lock' ) {
                if ( $options['skip_number'] == 0 ) {;
                    return "[sociallocker-5 id='$id']" . $content . "[/sociallocker-5]";
                } else {
                    $counter = 0;
                    $offset = 0;

                    while( preg_match('/[^\s]+((\n\r){2,}|(\r\n){2,}|(\n){2,}|(\r){2,})/i', $content, $matches, PREG_OFFSET_CAPTURE, $offset ) ) {
                        $counter++;
                        $offset = $matches[0][1] + strlen( $matches[0][0] );
                      
                        if ( $counter == $options['skip_number'] ) { 
                            $content = substr($content, 0, $offset) . "[sociallocker-5 id='$id']" . substr($content, $offset) . "[/sociallocker-5]"; 
                            return $content;                            
                        }
                    }
                }
                
                return $content;
                
            } elseif( $options['way'] == 'more-tag' && is_singular( $options['post_types'] ) ) {
                global $post;
                
                $label = '<span id="more-' . $post->ID . '"></span>';
                $pos = strpos( $content, $label );
                if ( $pos === false ) return $content;
                
                $offset = $pos + strlen( $label );
                if ( substr($content, $offset, 4) == '</p>' ) $offset += 4;
                
                return substr($content, 0, $offset) . "[sociallocker-5 id='$id']" . substr($content, $offset) . "[/sociallocker-5]";                 
            }
        }
        
        return $content;
    }
    
    private static function deleteBulkLocker( $id ) {
        $bulkLockers = get_option('onp_sl_bulk_lockers', array());
        if ( isset($bulkLockers[$id]) ) unset( $bulkLockers[$id] );
        delete_option('onp_sl_bulk_lockers');
        add_option('onp_sl_bulk_lockers', $bulkLockers);     
    }
    
    // -----------------------------------------------
    // Dynamic Themes
    // -----------------------------------------------
    
    /**
     * Inits support for dynamic themes.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function iniDynamicThemes() {
        $dynamicTheme = get_option('onp_sl_dynamic_theme', false);
        if ( !$dynamicTheme ) return;
        
        add_action( 'wp_head', array($this, 'printDynamicThemesOptions') );
        self::requestAssets();
    }
    
    /**
     * Prints options required for dynamic themes.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function printDynamicThemesOptions() {
        $isDynamic = get_option('onp_sl_is_dynamic_theme', false);
        $event = get_option('onp_sl_dynamic_theme_event', '');
        ?>
        <!-- 
            Support for Dynamic Themes
        
            Created by the Social Locker plugin (c) OnePress Ltd
            http://onepress-media.com/plugin/social-locker-for-wordpress/get
        -->
        <script>
        if ( !window.onpsl ) window.onpsl = {};
        window.onpsl.dynamicThemeSupport = '<?php echo $isDynamic ?>';
        window.onpsl.dynamicThemeEvent = '<?php echo $event ?>';
        </script>
        <!-- / -->
        <?php
    }
}

add_action('wp', 'OnpSL_AssetsManager::init');