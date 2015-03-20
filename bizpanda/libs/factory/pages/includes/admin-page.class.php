<?php

class FactoryPages321_AdminPage extends FactoryPages321_Page {
    
    /**
     * Visible page title.
     * For example: 'License Manager'
     * @var string 
     */
    public $pageTitle;
    
    /**
     * Visible title in menu.
     * For example: 'License Manager'
     * @var string 
     */
    public $menuTitle = null;
	
    /**
     * If set, an extra sub menu will be created with another title.
     * @var type 
     */
    public $menuSubTitle = null;
    
    /**
     * Menu icon (only if a page is placed as a main menu).
     * For example: '~/assets/img/menu-icon.png'
     * For example dashicons: '\f321'
     * @var string 
     */
    public $menuIcon = null;
    
    /**
     * Menu position (only if a page is placed as a main menu).
     * @link http://codex.wordpress.org/Function_Reference/add_menu_page
     * @var string 
     */
    public $menuPosition = null;
    
    /**
     * Menu type. Set it to add the page to the specified type menu.
     * For example: 'post'
     * @var type 
     */
    public $menuPostType = null;
    
    /**
     * if specified the page will be added to the given menu target as a submenu.
     * For example: 'edit.php?post_type=custom-post-type'
     * @var string
     */
    public $menuTarget = null;
    
    /**
     * if true, then admin.php is used as a base url.
     * @var bool 
     */
    public $customTarget = false;
    
    /**
     * Capabilities for roles that have access to work with this page.
     * Leave it empty to use inherited capabilities for custom post type menu.
     * @link http://codex.wordpress.org/Roles_and_Capabilities
     * @var array An array of the capabilities.
     */
    public $capabilitiy = null;
    
    /**
     * If true, the page will not added to the admin menu.
     * @var bool 
     */
    public $internal = false;
    
    /**
     * If true, the page will not be cretaed.
     * 
     * @since 3.0.6
     * @var bool 
     */
    public $hidden = false;
    
    public function __construct($plugin = null) {
        parent::__construct($plugin);
        $this->configure();

        $this->id = empty($this->id) ? str_replace('adminpage', '', strtolower( get_class($this) ) ) : $this->id;
    }
    
    /**
     * May be used to configure the page before uts usage.
     */
    public function configure(){}
    
    /**
     * Includes the Factory Bootstrap assets for a current page.
     * 
     * @param string $hook
     * @return void
     */
    public function actionAdminBootstrapScripts( $hook ) {
        $this->scripts->connect('bootstrap');
        $this->styles->connect('bootstrap'); 
    } 
    
    /**
     * Includes the assets for a current page (all assets except Factory Bootstrap assets). 
     * 
     * @param string $hook
     * @return void
     */
    public function actionAdminScripts( $hook ) {
        $this->scripts->connect();
        $this->styles->connect(); 
    }
    
    public function getResultId() {
        if ( $this->plugin ) return $this->id . '-' . $this->plugin->pluginName;
        return $this->id;
    }
    
    /**
     * Registers admin page for the admin menu.
     */
    public function connect() {
        $resultId = $this->getResultId();
        
        $this->hidden = apply_filters('factory_page_is_hidden_' . $resultId, $this->hidden);
        if ( $this->hidden ) return;

        $this->internal = apply_filters('factory_page_is_internal_' . $resultId, $this->internal);
        if ( $this->internal ) {
            $this->menuTarget = null;
            $this->menuPostType = null;
        }
        
        // makes redirect to the page
        $controller = isset( $_GET['fy_page'] ) ? $_GET['fy_page'] : null;
        if ( $controller && $controller == $this->id ) {
            $plugin = isset( $_GET['fy_plugin'] ) ? $_GET['fy_plugin'] : null; 

            if ( $this->plugin->pluginName == $plugin ) {
                $action = isset( $_GET['fy_action'] ) ? $_GET['fy_action'] : 'index';
                $isAjax = isset( $_GET['fy_ajax'] );
                
                if ( $isAjax ) {

                    $this->executeByName( $action );
                    exit;
                    
                } else {
                    
                    $params = array();
                    foreach ($_GET as $key => $value) {
                        $params[$key] = $value;
                    }

                    unset($params['fy_page']);
                    unset($params['fy_plugin']);
                    unset($params['fy_action']);

                    $this->redirectToAction($action, $params);
                }
            }
        }
        
        // executes an action
        if ( $this->current() ) {
            ob_start();
            $action = isset( $_GET['action'] ) ? $_GET['action'] : 'index';
            $this->executeByName( $action );
            $this->result = ob_get_contents();
            ob_end_clean();
        }
   
        // calls scripts and styles, adds pages to menu
        if ( isset($_GET['page']) && $_GET['page'] == $resultId ) {
            $this->assets($this->scripts, $this->styles);

            if ( !$this->scripts->isEmpty('bootstrap')|| !$this->styles->isEmpty('bootstrap') ) {
                add_action('factory_bootstrap_enqueue_scripts_' . $this->plugin->pluginName, array($this, 'actionAdminBootstrapScripts'));
            }
            
            // includes styles and scripts
            if ( !$this->scripts->isEmpty() || !$this->styles->isEmpty() ) {
                add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
            }
        }
        
        // if this page for a custom menu page
        if ( $this->menuPostType ) {
            $this->menuTarget = 'edit.php?post_type=' . $this->menuPostType;
            if ( empty( $this->capabilitiy ) ) {
                $this->capabilitiy = 'edit_' . $this->menuPostType;
            }
        } 

        // sets default capabilities
        if ( empty( $this->capabilitiy ) ) {
            $this->capabilitiy = 'manage_options';
        }

        $this->pageTitle = !$this->pageTitle ? $this->menuTitle : $this->pageTitle;
        $this->menuTitle = !$this->menuTitle ? $this->pageTitle : $this->menuTitle;
        
        $this->pageTitle = apply_filters( 'factory_page_title_' .$resultId , $this->pageTitle ) ;
        $this->menuTitle = apply_filters( 'factory_menu_title_' .$resultId , $this->menuTitle ) ;
        
        // submenu
        if ( $this->menuTarget ) {

            add_submenu_page( 
                $this->menuTarget, 
                $this->pageTitle, 
                $this->menuTitle, 
                $this->capabilitiy, 
                $resultId, 
                array($this, 'show') );

        // global menu
        } else {

            add_menu_page( 
                $this->pageTitle, 
                $this->menuTitle, 
                $this->capabilitiy, 
                $resultId, 
                array($this, 'show'), 
                null,
                $this->menuPosition );   
				
            if ( !empty( $this->menuSubTitle ) ) {

                add_submenu_page( 
                    $resultId, 
                    $this->menuSubTitle, 
                    $this->menuSubTitle, 
                    $this->capabilitiy,
                    $resultId,
                    array($this, 'show') );
            }
 
            add_action( 'admin_head', array($this, 'actionAdminHead'));  
        }
    }
    
    protected function current() {
        
        if (!isset($_GET['page']) ) return false;
        $resultId = $this->getResultId();
        if ( $resultId == $_GET['page'] ) return true;
        return false;
    }

    protected function redirectToAction($action, $queryArgs = array()) {
 
        wp_redirect( $this->getActionUrl($action, $queryArgs) );     
        exit;
    }
    
    public function actionUrl($action = null, $queryArgs = array()) {
        echo $this->getActionUrl($action, $queryArgs); 
    }
    
    public function getActionUrl($action = null, $queryArgs = array()) {
        $baseUrl = $this->getBaseUrl();

        if ( !empty( $action )) $queryArgs['action'] = $action;
        $url = add_query_arg($queryArgs, $baseUrl);
        return $url;
    }
    
    protected function getBaseUrl() {
        $resultId = $this->getResultId();
                
        if ( $this->menuTarget ) {
            if ( $this->customTarget ) return admin_url('admin.php') . '?page=' . $resultId;
            return $this->menuTarget . '&page=' . $resultId;     
        } else {
            return 'admin.php?&page=' . $resultId;     
        } 
    }
    
    public function actionAdminHead() 
    {     
        $resultId = $this->getResultId();
        
        if (!empty($this->menuIcon)) {
            
            if(preg_match('/\\\f\d{3}/', $this->menuIcon)) {              
                $iconCode = $this->menuIcon;                
            } else {
                $iconUrl = str_replace('~/', $this->plugin->pluginUrl . '/', $this->menuIcon); 
            }            
        }          
        
        global $wp_version;
        if ( version_compare( $wp_version, '3.7.3', '>'  ) ) {
        ?>
            <style type="text/css" media="screen">

                <?php if ( !empty($iconUrl) ) { ?>

                a.toplevel_page_<?php echo $resultId ?> .wp-menu-image {
                    background: url('<?php echo $iconUrl ?>') no-repeat 10px -30px !important;
                }

                <?php } ?>

                a.toplevel_page_<?php echo $resultId ?> .wp-menu-image:before {
                    content: "<?php echo !empty($iconCode) ? $iconCode : ''; ?>" !important;
                }
                a.toplevel_page_<?php echo $resultId ?>:hover .wp-menu-image, 
                a.toplevel_page_<?php echo $resultId ?>.wp-has-current-submenu .wp-menu-image,                 
                a.toplevel_page_<?php echo $resultId ?>.current .wp-menu-image {
                    background-position:10px 2px !important;
                }
            </style>
        <?php } else { ?>
            <style type="text/css" media="screen">
                a.toplevel_page_<?php echo $resultId ?> .wp-menu-image {
                    background: url('<?php echo $iconUrl ?>') no-repeat 6px -33px !important;
                }
                a.toplevel_page_<?php echo $resultId ?>:hover .wp-menu-image, 
                a.toplevel_page_<?php echo $resultId ?>.current .wp-menu-image {
                    background-position:6px -1px !important;
                }
            </style>
        <?php
        }
        
        if ($this->internal) {
            ?>
            <style type="text/css" media="screen">
                li.toplevel_page_<?php echo $resultId ?> {
                    display: none;
                }
            </style>
            <?php
        }
    }
}