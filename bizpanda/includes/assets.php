<?php

class OPanda_AssetsManager {
    
    private static $_requested = array();
    
    private static $_createrScriptPrinted = false;
    private static $_cssOptionsToPrint = array();
    
    public static function init() {
        self::initBulkLocking();
        self::iniDynamicThemes();
        self::userTracker();
    }
    
    private static $_cookiesPassCode = null;
    private static $_passcodeCookieSet = false;
    private static $_autoUnlock = false;    
    
    public static function autoUnlock( $itemId ) {

        if ( isset( self::$_autoUnlock[$itemId] ) ) return self::$_autoUnlock[$itemId];

        self::$_autoUnlock[$itemId] = self::isAutoUnlock( $itemId );
        return self::$_autoUnlock[$itemId];
    }
    
    public static function isAutoUnlock( $itemId ) {
        
        $debug = get_option('opanda_debug', false);        
        if ( !empty( $debug ) ) return false;

        $filterResult = apply_filters('opanda_auto_unlock', null, $itemId );
        if ( $filterResult !== null ) return $filterResult;        

        // pass code        
        
        $passcode = get_option('opanda_passcode', false);
        if ( empty( $passcode ) ) return false;

        $permanentPasscode = get_option('opanda_permanent_passcode', false);
        if ( $permanentPasscode ) { 
            
            if ( empty( self::$_cookiesPassCode ) ) self::$_cookiesPassCode = 'opanda_' . wp_create_nonce( 'passcode' );
            if ( isset( $_COOKIE[self::$_cookiesPassCode] ) || self::$_passcodeCookieSet ) return true;
        
            setcookie( self::$_cookiesPassCode, 1, time() + 60*60*24*5000, '/' );
            self::$_passcodeCookieSet = true;       
            
        } elseif ( !isset( $_GET[$passcode] ) ) {
            return false;
        }

        return true;  
    }

    /**
     *Items types to load assets.
     * @var type 
     */
    static $connectedItems = array();

    static $_fromBody = false;
    static $_fromHeader = false;
    
    /**
     * Requests adding assets for a given item type on a current page.
     * Called as an entry point in methods of Shortcodes, Bulk Lockers and Dynamic Themes.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function requestAssets( $itemId, $fromBody = false, $fromHeader = false ) {
        if ( self::autoUnlock( $itemId ) ) return;
        
        self::$_fromBody = $fromBody;
        self::$_fromHeader = $fromHeader;
                
        $type = OPanda_Items::getItemNameById( $itemId );
        $options = self::getLockerOptions( $itemId );
        
        self::defineVisibilityVars( $options, $itemId );
        
        do_action('opanda_request_resources');
        do_action('opanda_request_assets_for_' . $type, $itemId, $options, $fromBody, $fromHeader);
    }

    static $_requestedTextRes = array();
    
    /**
     * Requests text resources to print.
     */
    public static function requestTextRes( $res = array() ) {
        self::$_requestedTextRes = array_merge( self::$_requestedTextRes, $res );
    }
    
    /**
     * Requests loading assets for lockers.
     */
    public static function requestLockerAssets() {

        if ( isset( self::$_requested['locker-assets'] ) ) return;
        self::$_requested['locker-assets'] = true;
        
        if ( self::$_fromBody || self::$_fromHeader ) {
            OPanda_AssetsManager::connectLockerAssets(); 
        } else {
            add_action( 'wp_enqueue_scripts', 'OPanda_AssetsManager::connectLockerAssets' );
        }  
        
        add_action( 'wp_footer', 'OPanda_AssetsManager::printLockerScriptVars', 1 );
        add_action( 'wp_footer', 'OPanda_AssetsManager::printLockerCreatorScript', 9999 );
    }
    
    private static $_definedVisibilityVars = array();
    
    /**
     * Definces visibility vars.
     */
    public static function defineVisibilityVars( $options, $itemId ) {

        if ( empty( $options['opanda_visibility_filters'] ) ) return;
        $visibility = json_decode( $options['opanda_visibility_filters'], true );

        $params = array();
        foreach( $visibility as $filter ) {
            if ( empty( $filter['conditions'] ) ) continue;
            
            foreach( $filter['conditions'] as $scope ) {
                if ( empty( $scope['conditions'] ) ) continue;
            
                foreach( $scope['conditions'] as $condition ) {
                    $params[] = $condition['param'];
                }
            }
        }

        foreach( $params as $param ) {
            $value = apply_filters('bp_visibility_param_' . $param, null );
            $value = apply_filters('bp_visibility_param', $value, $param );

            self::$_definedVisibilityVars[$param] = $value;
        }
    }
    
    /**
     * Connects scripts and styles of Opt-In Panda.
     * 
     * @sincee 1.0.0
     * @return void
     */
    public static function connectLockerAssets() {

        wp_enqueue_style( 
            'opanda-lockers', 
            OPANDA_BIZPANDA_URL . '/assets/css/lockers.010104.min.css'
        );

        wp_enqueue_script( 
            'opanda-lockers',
            OPANDA_BIZPANDA_URL . '/assets/js/lockers.010104.min.js',
            array('jquery', 'jquery-effects-core', 'jquery-effects-highlight'), false, true
        );
        
        if ( get_option('opanda_debug', false ) ) {

            wp_enqueue_style( 
                'opanda-lockers-debugger',
                OPANDA_BIZPANDA_URL . '/assets/css/lockers.debugger.css'
            );

            wp_enqueue_script( 
                'opanda-lockers-debugger',
                OPANDA_BIZPANDA_URL . '/assets/js/lockers.debugger.js',
                array('opanda-lockers'), false, true
            );   
            
        }
        
        $facebookSDK = array( 
            'appId' => get_option('opanda_facebook_appid'),
            'lang' => get_option('opanda_lang', 'en_US') 
        ); 

        wp_localize_script( 'opanda-lockers', 'facebookSDK', $facebookSDK );
        
        do_action('opanda_connect_locker_assets');
    }
    
    /**
     * Prints variables required for the locker script.
     */
    public static function printLockerScriptVars() {
       
        $resToPrint = array();
        foreach( self::$_requestedTextRes as $res ) {
            $value = get_option('opanda_res_' . $res, false );
            if ( false === $value ) continue;
            $resToPrint[$res] = $value;
        }

        wp_localize_script( 'opanda-lockers', '__pandalockers', array(
            'lang' => $resToPrint,
            'visibility' => self::$_definedVisibilityVars,
            'managedInitHook' => get_option('opanda_managed_hook', false)
        ));
    }

    /**
     * Prints a script that creates lockers.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function printLockerCreatorScript() {
        
        do_action('opanda_before_locker_creator_script');
        
        $args = array();
        $args[opanda_get_robust_key()] = opanda_get_robust_script_key();
                
        $robustLoader = add_query_arg($args, site_url() );
        
        ?> 
        <!--
            Lockers: script checks if the locker assets were successfully loaded and creates lockers.
            OnePress, bizpanda.com
        -->
        <script>
            (function($){ if ( window.bizpanda && window.bizpanda.initLockers ) { window.bizpanda.initLockers(); } })(jQuery); (function($){ $(function(){ if ( window.bizpanda && window.bizpanda.initLockers ) return; $.getScript( "<?php echo $robustLoader; ?>", function() { if ( window.bizpanda && window.bizpanda.initLockers ) window.bizpanda.initLockers(); }); }); })(jQuery);
        </script>
        <?php
    
        do_action('opanda_after_locker_creator_script');
    }
    
    /**
     * Requests loading Facebook SDK.
     */
    public static function requestFacebookSDK() {
        
        if ( isset( self::$_requested['facebook-sdk'] ) ) return;
        self::$_requested['facebook-sdk'] = true;

        if ( !self::$_fromBody ) {
            add_action( 'wp_head', 'OPanda_AssetsManager::connectFacebookSDK' );
        } else {
            add_action( 'wp_footer', 'OPanda_AssetsManager::connectFacebookSDK', 1 );  
        }
    }
    
    /**
     * Connects scripts and styles of Opt-In Panda.
     * 
     * @sincee 1.0.0
     * @return void
     */
    public static function connectFacebookSDK() {
        
        $fb_appId = get_option('opanda_facebook_appid');
        $fb_version = get_option('opanda_facebook_version', 'v2.0');
        $fb_lang = get_option('opanda_lang', 'en_US');
        
        $url = ( $fb_version === 'v1.0' ) 
            ? "//connect.facebook.net/" . $fb_lang . "/all.js"
            : "//connect.facebook.net/" . $fb_lang . "/sdk.js?";

        ?>
        <!-- 
            Lockers: Facebook SDK
            OnePress, bizpanda.com
        -->
        <script>
            window.fbAsyncInitPredefined = window.fbAsyncInit;
            window.fbAsyncInit = function() {
                window.FB.init({
                    appId: <?php echo $fb_appId ?>,
                    status: true,
                    cookie: true,
                    xfbml: true,
                    version: '<?php echo $fb_version ?>'
                });
                window.FB.init = function(){};
                window.fbAsyncInitPredefined && window.fbAsyncInitPredefined();
            };
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "<?php echo $url ?>";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- / -->   
        <?php  
        
        do_action('opanda_connect_facebook_sdk');
    }
    
    public static function printCssSelectorOptions() {

        ?>
        <!-- 
            Lockers: CSS Selectors (Bulk Locking) 
            OnePress, bizpanda.com
        -->
        <script>
            if ( !window.bizpanda ) window.bizpanda = {};
            window.bizpanda.bulkCssSelectors = [];
            <?php foreach( self::$_cssOptionsToPrint as $options ) { ?>
            window.bizpanda.bulkCssSelectors.push({
                lockId: '<?php echo $options['locker-options-id'] ?>',
                selector: '<?php echo $options['css-selector'] ?>'
            });
            <?php } ?>
        </script>
        <style>
            <?php foreach( self::$_cssOptionsToPrint as $options ) { ?>
            <?php if ( $options['overlap-mode'] === 'full' ) { ?>
            <?php echo $options['css-selector'] ?> { display: none; }
            <?php } ?>
            <?php } ?>
        </style>
        <!-- / -->
        <?php
        
        self::$_cssOptionsToPrint = array();
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
            if ( self::autoUnlock( $id ) ) continue;
            $lockData = self::getLockerDataToPrint( $id );
            
            $data[$id] = array(
                'name' => $name,
                'options' => $lockData
            );
        }

        ?>  
        <!--
            Lockers: options of bulk lockers   
            OnePress, bizpanda.com
        -->
            <script>
            if ( !window.bizpanda ) window.bizpanda = {};
            if ( !window.bizpanda.lockerOptions ) window.bizpanda.lockerOptions = {};
            <?php foreach( $data as $item ) { ?>
                window.bizpanda.lockerOptions['<?php echo $item['name'] ?>'] = <?php echo json_encode( $item['options'] ) ?>;
            <?php } ?>
            </script>
            <?php foreach( $data as $id => $item ) { ?>
            <?php do_action( 'opanda_print_batch_locker_assets', $id, $item['options'], $item['name'] ); ?>          
            <?php } ?>
        <!-- / -->
        <?php

        self::$_lockerOptionsToPrint = array();
    }
    
    /**
     * Returns base options for all Panda Items.
     * 
     * @since 1.0.0
     */
    public static function getBaseOptions( $id ) {

        $hasScope = get_option('opanda_interrelation', false);
            
        $params = array(
            'demo' => self::getLockerOption($id, 'always', false, false),
            'actualUrls' => get_option('opanda_actual_urls', false),

            'text' => array(
                'header' => self::getLockerOption($id, 'header'), 
                'message' => self::getLockerOption($id, 'message')             
            ),

            'theme' => self::getLockerOption($id, 'style'), 
            'lang' => get_option('opanda_lang', 'en_US'),

            'overlap' => array(
                'mode' => self::getLockerOption($id, 'overlap', false, 'full'),
                'position' => self::getLockerOption($id, 'overlap_position', false, 'middle'),
                'altMode' => get_option('opanda_alt_overlap_mode', 'transparence')
            ),
            
            'highlight' => self::getLockerOption($id, 'highlight'),
            'googleAnalytics' => get_option('opanda_google_analytics', 1),

            'locker' => array(
                'scope' => $hasScope ? 'global' : '',
                'counter' => self::getLockerOption($id, 'show_counters', false, 1),
                'loadingTimeout' => get_option('opanda_timeout', 20000),
                'tumbler' => get_option('opanda_tumbler', false),
                'naMode' => get_option('opanda_na_mode', 'show-error')
            )
        );
            
            if ( 'blurring' === $params['overlap']['mode'] ) {
                $options['overlap']['mode'] = 'transparence';
            }   

        


        $params['proxy'] = opanda_proxy_url();
        
        // - Replaces shortcodes in the locker message
        
        global $post;
        
        $postTitle = $post != null ? $post->post_title : '';
        $postUrl = $post != null ? get_permalink($post->ID) : '';

        if ( !empty( $params['text']['message'] ) ) {
            $params['text']['message'] = str_replace('[post_title]', $postTitle, $params['text']['message']);
            $params['text']['message'] = str_replace('[post_url]', $postUrl, $params['text']['message']);  
        }

        return $params;
    }
    
    /**
     * Returns data to print.
     */
    public static function getLockerDataToPrint( $id, $lockData = array() ) {
        global $post;

        $lockData['lockerId'] = $id;

        // options for tracking

        $lockData['tracking'] = get_option('opanda_tracking', true);
        $lockData['postId'] = !empty($post) ? $post->ID : false;
        $lockData['ajaxUrl'] = admin_url( 'admin-ajax.php' );
        
        // the pande item option
        
        $baseOptions = self::getBaseOptions( $id );
        
        $itemType = OPanda_Items::getItemNameById( $id );

        $options = apply_filters("opanda_{$itemType}_item_options", $baseOptions, $id );
        $options = apply_filters("opanda_item_options", $options, $id );

        // normilize options
        
        self::_normilizeLockerOptions( $options );
        
        if ( !isset($options['text']['header']) ) $options['text']['header'] = '';
        if ( !isset($options['text']['message']) ) $options['text']['message'] = '';  
        
        $lockData['options'] = $options;
        
        $lockData['_theme'] = self::getLockerOption($id, 'style' );
        $lockData['_style'] = self::getLockerOption($id, 'style_profile' );
          
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
        if ( empty($options) ) return $options;
        
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

        $value = isset( $options['opanda_' . $name] ) ? $options['opanda_' . $name] : null;

        return ($value === null || $value === '')
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
            
            if ( $item === '1' ) {
                $params[$key] = 1;
                continue;
            }  

            if ( $item === 'false' ) {
                $params[$key] = false;
                continue;
            }   
            
            if ( $item === '0' ) {
                $params[$key] = 0;
                continue;
            }               

            if ( gettype($item) == 'array' ) {
                self::_normilizeLockerOptions( $params[$key] );
            }
        }
    }
    
    // -----------------------------------------------
    // Markup Normilizer
    // -----------------------------------------------
    
    /**
     * Normilizes the shortcode and html markup to make sure that the locker 
     * shortcode was pasted correctly
     * 
     * @since 1.1.3
     * @return string
     */
    public static function normilizerMarkup( $contentBefore, $contentInside, $shortcodeStart, $shortcodeEnd ) {
        
        $normalizeMarkup = get_option('opanda_normalize_markup', false);
        if ( !$normalizeMarkup ) return $contentBefore . $shortcodeStart . $contentInside . $shortcodeEnd;

        list( $endingElements, $endingTags ) = self::findMarkupElements( true, $contentInside );

        $allowedNames = array();
        foreach( $endingElements as $element ) $allowedNames[] = $element['name'];

        list( $startingElements, $startingTags ) = self::findMarkupElements( false, $contentBefore, $allowedNames ); 

        $end = implode("", $endingTags);
        $start = implode("", $startingTags);

        $content = $contentBefore . $end . $shortcodeStart . $start . $contentInside . $shortcodeEnd;
        return $content;
    }
    
    /**
     * Finds closing and opening shortcodes and html elements without pairs.
     * 
     * @since 1.1.3
     * @return mixed[]
     */
    public static function findMarkupElements( $closing = false, $content, $allowedNames = null ) {

        $result = array( array(), array() );
        
        $regex = array();
        
        $regex[] = '(\[(\/)?([^\[\]]*)\])';
        $regex[] = '(<(\/)?\s*([a-z0-9\-\_]+[^<>]*)>)';

        if ( !preg_match_all( '/' . implode('|', $regex) . '/', $content, $matches, PREG_SET_ORDER ) ) return $result;

        $elements = array();
        $tags = array();
        
        $stack = array();
        
        foreach( $matches as $match ) {
            $keyShift = empty( $match[3] ) ? 3 : 0;
                    
            $attrs = explode( ' ', trim( $match[3+$keyShift] ) );
            $name = trim( $attrs[0] );
            $matchClosing = !empty( $match[2+$keyShift] );
            $tag = trim( $match[1+$keyShift] );
            
            if ( !ctype_lower( $name ) )  continue;			
            if ( in_array( $name, array( 'img', 'intense_hover_box', 'Don' ) ) )  continue;
            if ( strpos($name, 'locker-bulk-') > 0 ) continue;
            
            $lastStack = end( $stack );
            
            if ( $lastStack['name'] === $name && $lastStack['closing'] !== $matchClosing ) {
                array_pop( $stack );
            } else {
                array_push( $stack, array('name' => $name, 'closing' => $matchClosing, 'tag' => $tag ) );
            }
        } 

        foreach( $stack as $element ) {
            if ( $closing !== $element['closing'] ) continue;
            
            $elements[] = $element;
            $tags[] = $element['tag'];
        }
        
        if ( !empty( $allowedNames ) ) {

            $filteredElements = array();
            $filteredTags = array();
            
            for( $i = 0; $i < count( $elements ); $i++ ) {
                if ( !in_array( $elements[$i]['name'], $allowedNames ) ) continue;
                
                $filteredElements[] = $elements[$i];
                $filteredTags[] = $elements[$i]['tag'];
            }
            
            return array( $filteredElements, $filteredTags );
        }

        return array( $elements, $tags );
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

        require_once OPANDA_BIZPANDA_DIR . '/includes/panda-items.php';
        
        foreach($bulkLockers as $id => $options) {
            if ( self::autoUnlock( $id ) ) continue;
                        
            $itemType = get_post_meta( $id, 'opanda_item', true );
            if ( !OPanda_Items::isAvailable( $itemType ) ) continue;

            // if we have bulk lockers based on css selectors, then we have to include
            // assets on every page and also print which css selectors we will use for the
            // Opt-In Panda creater script
            
            if ( $options['way'] == 'css-selector' ) {
                
                $lockData = self::getLockerDataToPrint($id);

                self::$_lockerOptionsToPrint['css-selector-' . $id] = $id;
                self::$_cssOptionsToPrint[] = array(
                    'locker-options-id' => 'css-selector-' . $id,
                    'css-selector' => $options['css_selector'],
                    'overlap-mode' => $lockData['options']['overlap']['mode']
                );
                
                self::requestAssets( $id );
                
            // if we have lockers based on the 'skip-lock' and 'more-tag' rules,
            // we need check if a current page is excluded
                
            } else {
                if ( !is_singular( $options['post_types'] ) ) continue;
                if ( !self::isPageExcluded( $id, $options ) ) {
                    self::requestAssets( $id );
                    continue;
                }
            }
        }
        
        if ( !empty( self::$_cssOptionsToPrint ) ) {
            add_action( 'wp_head', 'OPanda_AssetsManager::printCssSelectorOptions' );
            add_action( 'wp_head', 'OPanda_AssetsManager::printLockerOptions' );
        } 
        
        add_filter('the_content', 'OPanda_AssetsManager::addSocialLockerShortcodes', 1);
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
        
        global $bizpanda;
        
        $shortcodeEnds = array();
        $bulkIndex = 0;
        
        $ignoredShortcodes = array();
        
        foreach($bulkLockers as $id => $options) {
            if ( !in_array( $options['way'], array('skip-lock', 'more-tag') ) ) continue;
            if ( self::isPageExcluded( $id, $options ) ) continue;
            
            $lockerStatus = get_post_status( $id );
            if ( 'publish' !== $lockerStatus ) continue;
            
            $itemType = get_post_meta( $id, 'opanda_item', true );

            if ( 'social-locker' == $itemType && !BizPanda::hasPlugin('sociallocker') ) continue;
            if ( 'email-locker' == $itemType && !BizPanda::hasPlugin('optinpanda') ) continue;

            $bulkIndex++;

            switch ( $itemType ) {
                case 'email-locker':
                    $shortcodeName = 'emaillocker-bulk-' . $bulkIndex;
                    break;
                case 'signin-locker':
                    $shortcodeName = 'signinlocker-bulk-' . $bulkIndex;
                    break;
                default:
                    $shortcodeName = 'sociallocker-bulk-' . $bulkIndex;
                    break;
            }
            
            $shortcode = new OPanda_LockerShortcode( $bizpanda );
            add_shortcode($shortcodeName, array($shortcode, 'render'));
            
            if ( $options['way'] == 'skip-lock' ) {
                if ( $options['skip_number'] == 0 ) {;
                    $content = "[$shortcodeName id='$id']" . $content;
                    
                    if ( !isset( $shortcodeEnds[0] ) ) $shortcodeEnds[0] = array();
                    $shortcodeEnds[0][] = "[/$shortcodeName]";
                    
                } else {
                    $counter = 0;
                    $offset = 0;

                    while( preg_match('/[^\s]+((<\/p>)|(\n\r){2,}|(\r\n){2,}|(\n){2,}|(\r){2,})/i', $content, $matches, PREG_OFFSET_CAPTURE, $offset ) ) {
                        $counter++;
                        $offset = $matches[0][1] + strlen( $matches[0][0] );
                      
                        if ( $counter == $options['skip_number'] ) { 
                            
                            $beforeShortcode = substr($content, 0, $offset);
                            $insideShortcode = substr($content, $offset);

                            $content = self::normilizerMarkup( $beforeShortcode, $insideShortcode, "[$shortcodeName id='$id']", "" );
                                                   
                            if ( !isset( $shortcodeEnds[$offset] ) ) $shortcodeEnds[$offset] = array();
                            $shortcodeEnds[$offset][] = "[/$shortcodeName]";
                            
                            break;
                        }
                    }
                }

            } elseif( $options['way'] == 'more-tag' && is_singular( $options['post_types'] ) ) {
                global $post;
                
                $label = '<span id="more-' . $post->ID . '"></span>';
                $pos = strpos( $content, $label );
                if ( $pos === false ) return $content;
                
                $offset = $pos + strlen( $label );
                if ( substr($content, $offset, 4) == '</p>' ) $offset += 4;
                
                $content = substr($content, 0, $offset) . "[$shortcodeName id='$id']" . substr($content, $offset);
                
                if ( !isset( $shortcodeEnds[$offset] ) ) $shortcodeEnds[$offset] = array();
                $shortcodeEnds[$offset][] = "[/$shortcodeName]";
            }
        }

        if ( !empty( $shortcodeEnds ) ) {
            
            krsort($shortcodeEnds);
 
            foreach( $shortcodeEnds as $shortcodeEndItem ) {
                foreach( $shortcodeEndItem as $shortcodeEnd ) {
                    $content .= $shortcodeEnd;
                }
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
        $dynamicTheme = get_option('opanda_dynamic_theme', false);
        if ( !$dynamicTheme ) return;
        
        add_action( 'wp_head', 'OPanda_AssetsManager::printDynamicThemesOptions' );
        
        require_once OPANDA_BIZPANDA_DIR . '/includes/panda-items.php';
        
        $lockers = get_posts(array(
            'post_type' => OPANDA_POST_TYPE,
            'meta_key' => 'opanda_item',
            'meta_value' => OPanda_Items::getAvailableNames(),
            'numberposts' => -1
        ));

        foreach( $lockers as $locker ) {
            self::requestAssets( $locker->ID );
        }
    }
    
    /**
     * Prints options required for dynamic themes.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function printDynamicThemesOptions() {
        $isDynamic = get_option('opanda_dynamic_theme', false);
        $event = get_option('opanda_dynamic_theme_event', '');       
        ?>
        <!-- 
            Lockers: support for dynamic themes
            OnePress, bizpanda.com
        -->
        <script>
        if ( !window.bizpanda ) window.bizpanda = {};
        window.bizpanda.dynamicThemeSupport = '<?php echo $isDynamic ?>';
        window.bizpanda.dynamicThemeEvent = '<?php echo $event ?>';
        </script>     
        <?php do_action('opanda_print_dynamic_theme_options'); ?>  
        <!-- / -->     
        <?php
    }
    
    // -----------------------------------------------
    // User Tacker
    // -----------------------------------------------
    
    public static function userTracker() {
        add_action( 'wp_footer', 'OPanda_AssetsManager::printUserTrackerScript', 1 );
    }
    
    public static function printUserTrackerScript() {
        ?>
        <!-- 
            Lockers: user tracker for visibility filters
            OnePress, bizpanda.com
        -->
        <script>
            window.__bp_session_timeout = '<?php echo get_option('opanda_session_duration', 900) ?>';
            window.__bp_session_freezing = <?php echo get_option('opanda_session_freezing', 0) ?>;
            !function(){window.bizpanda||(window.bizpanda={}),window.bizpanda.bp_can_store_localy=function(){return!1},window.bizpanda.bp_ut_get_cookie=function(e){for(var n=e+"=",i=document.cookie.split(";"),o=0;o<i.length;o++){for(var t=i[o];" "==t.charAt(0);)t=t.substring(1);if(0==t.indexOf(n))return decodeURIComponent(t.substring(n.length,t.length))}return!1},window.bizpanda.bp_ut_set_cookie=function(e,n,i){var o=new Date;o.setTime(o.getTime()+24*i*60*60*1e3);var t="expires="+o.toUTCString();document.cookie=e+"="+encodeURIComponent(n)+"; "+t+"; path=/"},window.bizpanda.bp_ut_get_obj=function(e){var n=null;return(n=window.bizpanda.bp_can_store_localy()?window.localStorage.getItem("bp_ut_session"):window.bizpanda.bp_ut_get_cookie("bp_ut_session"))?(n=JSON.parse(n),n.started+1e3*e<(new Date).getTime()&&(n=null),n):!1},window.bizpanda.bp_ut_set_obj=function(e){e.started&&window.__bp_session_freezing||(e.started=(new Date).getTime());var e=JSON.stringify(e);window.bizpanda.bp_can_store_localy()?window.localStorage.setItem("bp_ut_session",e):window.bizpanda.bp_ut_set_cookie("bp_ut_session",e,5e3)},window.bizpanda.bp_ut_count_pageview=function(){var e=window.bizpanda.bp_ut_get_obj(window.__bp_session_timeout);e||(e={}),e.pageviews||(e.pageviews=0),0===e.pageviews&&(e.referrer=document.referrer,e.landingPage=window.location.href,e.pageviews=0),e.pageviews++,window.bizpanda.bp_ut_set_obj(e)},window.bizpanda.bp_ut_count_locker_pageview=function(){var e=window.bizpanda.bp_ut_get_obj(window.__bp_timeout);e||(e={}),e.lockerPageviews||(e.lockerPageviews=0),e.lockerPageviews++,window.bizpanda.bp_ut_set_obj(e)},window.bizpanda.bp_ut_count_pageview()}();
        </script>
        <!-- / -->
            <?php
            /**
            <script>
            (function(){
                if ( !window.bizpanda ) window.bizpanda = {};
                
                window.bizpanda.bp_can_store_localy = function() {
                    return false;
                    if ( !window.localStorage || !window.localStorage.getItem  || !window.localStorage.setItem ) return false;
                    var salt = '' + Math.floor((Math.random() * 1000) + 1);
                    
                    try { 
                        window.localStorage.setItem('bp_ut_test', salt); 
                        var checkSalt = window.localStorage.getItem('bp_ut_test'); 
                        window.localStorage.removeItem('bp_ut_test');

                        return checkSalt === salt;
                    } catch(e) {
                        return false;
                    } 
                };
                
                window.bizpanda.bp_ut_get_cookie = function getCookie(cname) {
                    var name = cname + "="; var ca = document.cookie.split(';');
                    for(var i=0; i<ca.length; i++) {
                        var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1);
                        if (c.indexOf(name) == 0) return decodeURIComponent( c.substring(name.length,c.length) );
                    }
                    return false;
                };
                
                window.bizpanda.bp_ut_set_cookie = function(cname, cvalue, days) {
                    var d = new Date();
                    d.setTime(d.getTime() + (days*24*60*60*1000));
                    var expires = "expires="+d.toUTCString();
                    document.cookie = cname + "=" + encodeURIComponent( cvalue ) + "; " + expires + "; path=/";
                };

                window.bizpanda.bp_ut_get_obj = function( timeout ) {
                    
                    var obj = null;
                    
                    if ( window.bizpanda.bp_can_store_localy() ) {
                        obj = window.localStorage.getItem('bp_ut_session');
                    } else {
                        obj = window.bizpanda.bp_ut_get_cookie('bp_ut_session');
                    }

                    if ( !obj ) return false;
                    
                    obj = JSON.parse(obj);
					
                    if ( ( obj.started + timeout * 1000 ) < new Date().getTime() ) obj = null;                    
                    return obj;
                };
                
                window.bizpanda.bp_ut_set_obj = function( obj, timeout ) {
                    
                    if ( !obj.started || !window.__bp_session_freezing ) {
                        obj.started = new Date().getTime();
                    }

                    var obj = JSON.stringify(obj);
                    
                    if ( window.bizpanda.bp_can_store_localy() ) {
                        window.localStorage.setItem('bp_ut_session', obj); 
                    } else {
                        window.bizpanda.bp_ut_set_cookie('bp_ut_session', obj, 5000);
                    }
                };

                window.bizpanda.bp_ut_count_pageview = function() {

                    var obj = window.bizpanda.bp_ut_get_obj( window.__bp_session_timeout );

                    if ( !obj ) obj = {};
                    if ( !obj.pageviews ) obj.pageviews = 0;

                    if ( obj.pageviews === 0 ) {
                        obj.referrer = document.referrer;
                        obj.landingPage = window.location.href;
                        obj.pageviews = 0;
                    }

                    obj.pageviews++;
                    window.bizpanda.bp_ut_set_obj( obj );
                };
                
                window.bizpanda.bp_ut_count_locker_pageview = function() {

                    var obj = window.bizpanda.bp_ut_get_obj( window.__bp_timeout );

                    if ( !obj ) obj = {};
                    if ( !obj.lockerPageviews ) obj.lockerPageviews = 0;
                    obj.lockerPageviews++;

                    window.bizpanda.bp_ut_set_obj( obj );
                };                

                window.bizpanda.bp_ut_count_pageview();
            })();
            </script>
        */
    }
}

if ( !is_admin() ) add_action('template_redirect', 'OPanda_AssetsManager::init');
