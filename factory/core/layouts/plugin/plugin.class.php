<?php
/**
 * Factory Plugin
 * 
 * It's a main class for building the plugin.
 * The class allows to isolate a several plugins that use the same version of the Factory.
 */
class FactoryFR107Plugin {
    
    /**
     * Main file of the plugin.
     * @var string
     */
    public $mainFile;
    
    public $pluginSlug;
    
    public $relativePath;
    
    public $options;
    
    /**
     * Template root.
     * @var string
     */
    public $templateRoot;
    
    /**
     * Absolute URL to plugin path.
     * @var string 
     */
    public $pluginUrl;
    
    /**
     * Plugin name.
     * @var string.
     */
    public $pluginName;
    
    public $version;
    
    public $build;
    
    /**
     * Is a current page one of the admin pages?
     * @var type 
     */
    public $isAdmin;
    
    /**
     * Loaded types.
     * @var array 
     */
    public $types = array();
    
    /**
     * Shortcode blanks to register.
     * Don't use it directly to render shortcodes.
     * @var array
     */
    public $shortcodes = array();
   
    /**
     * Creates an instance of Factory plugin.
     */
    public function __construct( $pluginPath, $data ) {
        $this->options = $data;
        
        // saves plugin basic paramaters
        $this->mainFile = $pluginPath;
        $this->pluginRoot = dirname( $pluginPath );
        $this->pluginSlug = basename($pluginPath);
        $this->relativePath = plugin_basename( $pluginPath );
        $this->templateRoot = $this->pluginRoot . '/' . ( ( array_key_exists( 'templates', $data ) ) ? $data['templates'] : 'templates' );   
        $this->pluginUrl = plugins_url( null, $pluginPath );
        $this->pluginName = $data['name'];
        $this->pluginTitle = $data['title'];
        $this->version = $data['version'];
        $this->build = $data['assembly'];
        $this->host = $_SERVER['HTTP_HOST'];  

        $this->isAdmin = is_admin();

        // init actions
        $this->setupActions();
        
        // register activation hooks
        if ( $this->isAdmin ) { 
            register_activation_hook( $this->mainFile, array($this, 'forceActivationHook') );
            register_deactivation_hook( $this->mainFile, array($this, 'deactivationHook') );
        }
    }
    
    /**
     * Setups actions related with the Factory Plugin.
     */
    private function setupActions() {
        add_action('plugins_loaded', array($this, 'actionPluginLoadded'));  
        add_action('init', array($this, 'actionInit')); 

        if ( $this->isAdmin ) {
            add_action('admin_init', array($this, 'actionAdminInit'), 20);
            add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
        }
    }
    
    public function actionPluginLoadded() {
        // load_plugin_textdomain('factory', false, basename( dirname( $this->relativePath ) ) . '/factory/core/langs'); 
         
        // checks whether the plugin needs to run updates.
        if ( $this->isAdmin ) {
            
            $dbVersion = get_option('fy_plugin_version_' . $this->pluginName, false);
            if ( $dbVersion != $this->build . '-' . $this->version ) {
                $this->activationOrUpdateHook( false );
            }  
        }
        
        do_action('factory_fr107_init', $this);
    }
    
    /**
     * WP Init action.
     * Don't excite it directly.
     */
    public function actionInit() {
        
        $this->shortcodes = new FactoryFR107ShortcodeManager( $this );   
        $this->metaboxes = new FactoryFR107MetaboxManager( $this );   
        
        if ( $this->isAdmin ) {
            
            $this->notices = new FactoryFR107NoticeManager( $this );
            $this->pages = new FactoryFR107AdminPageManager( $this ); 
        
            // metaboxes
            // just includes class definition
            $metaboxes = $this->loadItem( 'metaboxes', true );
            if ( !empty( $metaboxes ) ) {
                $this->metaboxes->register( $metaboxes );
            }
            
            // view tables
            // just includes class definition
            $this->loadItem( 'viewtables', false );
            
            // admin pages
            $pages = $this->loadItem( 'pages', true );
            if ( !empty( $pages ) ) {
                $this->pages->register( $pages );
            }
        }
        
        // types
        $types = $this->loadItem( 'types', true );
        if ( !empty($types) ) {
            foreach($types as $type) { 
                $this->types[$type->name] = $type;
                $type->register();
            }
        }
        
        // shortcodes
        $shortcodes = $this->loadItem( 'shortcodes', true ); 
        if ( !empty($shortcodes) ) {
            $this->shortcodes->register( $shortcodes );
        }
    }
    
    public function actionAdminInit() {
        remove_action("after_plugin_row_" . $this->relativePath, 'wp_plugin_update_row');
        add_action("after_plugin_row_" . $this->relativePath, array($this, 'showCustomPluginRow'), 10, 2);
    }
    
    public function forceActivationHook() {
        $this->activationOrUpdateHook(true);
    }
    
    public function activationOrUpdateHook( $forceActivation = false ) {
        do_action('factory_fr107_activation_or_update-' . $this->pluginName);
        
        $dbBuildVersion = get_option('fy_plugin_version_' . $this->pluginName, false);

        // there are not any previous version of the plugin in the past
        if ( !$dbBuildVersion ) {
            $this->activationHook();
            
            update_option('fy_plugin_version_' . $this->pluginName, $this->build . '-' . $this->version);
            return;
        }

        $parts = split('-', $dbBuildVersion);
        $prevousBuild = $parts[0];
        $prevousVersion = $parts[1];

        // if another build was used previously
        if ( $prevousBuild != $this->build ) {
            $this->migrationHook($prevousBuild, $this->build);
            $this->activationHook();
            
            update_option('fy_plugin_version_' . $this->pluginName, $this->build . '-' . $this->version);
            return;
        }

        // if another less version was used previously
        if ( version_compare($prevousVersion, $this->version, '<') ){
            $this->updateHook($prevousVersion, $this->version); 
            
            update_option('fy_plugin_version_' . $this->pluginName, $this->build . '-' . $this->version);
            return;
        }

        // standart plugin activation
        if ( $forceActivation && $dbBuildVersion ) {
            $this->activationHook();
        }
        
        // else nothing to do
        update_option('fy_plugin_version_' . $this->pluginName, $this->build . '-' . $this->version);
        return;
    }
    
    /**
     * It's invoked on plugin actionvation.
     * Don't excite it directly.
     */
    public function activationHook() {
 
        $item = $this->loadItem( 'activation', true );
        if ( !empty($item) ) $item->activate();     
        
        // sets type capabilities for roles
        $types = $this->loadItem( 'types', true );
        if ( !empty($types) ) {
            foreach($types as $type) {
                if ( empty( $type->capabilities )) continue;
                foreach( $type->capabilities as $roleName ) {
                    $role = get_role( $roleName );
                    if ( !$role ) continue;

                    $role->add_cap( 'edit_' . $type->name ); 
                    $role->add_cap( 'read_' . $type->name );
                    $role->add_cap( 'delete_' . $type->name );
                    $role->add_cap( 'edit_' . $type->name . 's' );
                    $role->add_cap( 'edit_others_' . $type->name . 's' );
                    $role->add_cap( 'publish_' . $type->name . 's' ); 
                    $role->add_cap( 'read_private_' . $type->name . 's' );      
                }
            }
        }
    }
    
    /**
     * It's invoked on plugin deactionvation.
     * Don't excite it directly.
     */
    public function deactivationHook() {
        do_action('factory_fr107_deactivation-' . $this->pluginName);;
        
        $item = $this->loadItem( 'activation', true );
        if ( !empty($item) ) {
            $item->deactivate();  
        }

        global $wp_roles;
        $all_roles = $wp_roles->roles;
        
        // remove type capabilities for roles
        $types = $this->loadItem( 'types', true );
        if ( !empty( $types ) ) {
            foreach($types as $type) {
                if ( empty( $type->capabilities )) continue;

                foreach( $all_roles as $roleName => $roleInfo ) {

                    $role = get_role( $roleName );
                    if ( !$role ) continue;

                    $role->remove_cap( 'edit_' . $type->name ); 
                    $role->remove_cap( 'read_' . $type->name );
                    $role->remove_cap( 'delete_' . $type->name );
                    $role->remove_cap( 'edit_' . $type->name . 's' );
                    $role->remove_cap( 'edit_others_' . $type->name . 's' );
                    $role->remove_cap( 'publish_' . $type->name . 's' ); 
                    $role->remove_cap( 'read_private_' . $type->name . 's' );      
                } 
            }
        }
    }
    
    /**
     * Finds migration items and install ones.
     */
    public function migrationHook($previosBuild, $currentBuild) {
        
        $migrationFile = $this->options['updates'] . $previosBuild . '-' . $currentBuild . '.php';
        if ( !file_exists($migrationFile) ) return;
        
        $classes = $this->getClasses($migrationFile);
        if ( count($classes) == 0 ) return;
        
        include_once($migrationFile);
        $migrationClass = $classes[0]['name'];
        
        $migrationItem = new $migrationClass( $this->plugin );
        $migrationItem->install();
    }
    
    /**
     * Finds upate items and install the ones.
     */
    public function updateHook( $old, $new ) {

        // converts versions like 0.0.0 to 000000
        $oldNumber = $this->getVersionNumber($old);
        $newNumber = $this->getVersionNumber($new);

        $updateFiles = $this->options['updates'];
        $files = $this->findFiles( $updateFiles );
        if ( empty($files) ) return;

        // finds updates that has intermediate version 
        foreach($files as $item) {
            if ( !preg_match('/^\d+$/', $item['name']) ) continue;

            $itemNumber = intval($item['name']);
            if ( $itemNumber > $oldNumber && $itemNumber <= $newNumber ) {

                $classes = $this->getClasses($item['path']);
                if ( count($classes) == 0 ) return;
                
                foreach($classes as $path => $classData) {
                    include_once( $path );
                    $updateClass = $classData['name'];

                    $update = new $updateClass( $this );
                    $update->install();
                }
            }
        }
    }
    
    protected function getVersionNumber($version) {

        preg_match('/(\d+)\.(\d+)\.(\d+)/', $version, $matches);
        if ( count($matches) == 0 ) return false;
        
        $number = '';
        $number .= ( strlen( $matches[1] ) == 1 ) ? '0' . $matches[1] : $matches[1];
        $number .= ( strlen( $matches[2] ) == 1 ) ? '0' . $matches[2] : $matches[2];
        $number .= ( strlen( $matches[3] ) == 1 ) ? '0' . $matches[3] : $matches[3];
        
        return intval($number);
    }
     
    /**
     * WP admin_enqueue_scripts action.
     * Don't excite directly.
     */
    public function actionAdminScripts( $hook ) {
	global $post;
        
        wp_enqueue_style('factory-admin-global', FACTORY_FR107_URL . '/assets/css/admin-global.css');
        wp_enqueue_script('factory-admin-global', FACTORY_FR107_URL . '/assets/js/admin-global.js'); 
                        
	if ( in_array( $hook, array('post.php', 'post-new.php')) && $post )
        {
            if ( !empty( $this->types[$post->post_type] ) ) {
                
		wp_enqueue_style('factory-bootstrap', FACTORY_FR107_URL . '/assets/css/bootstrap.css');	
		wp_enqueue_script('factory-bootstrap', FACTORY_FR107_URL . '/assets/js/bootstrap.js', array('jquery'));
            }
            
        } elseif ( isset($_GET['page']) && in_array($_GET['page'], $this->pages->getIds())) {
            
            wp_enqueue_style('factory-bootstrap', FACTORY_FR107_URL . '/assets/css/bootstrap.css');	
            wp_enqueue_script('factory-bootstrap', FACTORY_FR107_URL . '/assets/js/bootstrap.js', array('jquery'));
        }
    }
    
    // ----------------------------------------------------------------------
    // Loading modules and registering items
    // ----------------------------------------------------------------------
    
    /**
     * Loads a module by its name.
     * @param type $moduleName
     */
    public function load( $path, $name ) {
        include($this->pluginRoot . '/' . $path . '/start.php');
        do_action('factory_fr107_load_' . $name, $this);
    }
    
    private $itemMapping = array();
    
    /**
     * Register a plugin item.
     * @param type $scope
     * @param type $className
     */
    public function registerItem( $scope, $className ) {
        if ( !isset( $this->itemMapping[$scope] ) ) $this->itemMapping[$scope] = array();
        $this->itemMapping[$scope][] = $className;
    }
    
    /**
     * Register a plugin page.
     * @param type $className
     */
    public function registerPage( $className ) {
        $this->registerItem('pages', $className);
    }
    
    /**
     * Register a set of plugin pages.
     * @param type $classes
     */
    public function registerPages( $classes ) {
        foreach($classes as $className) $this->registerItem('pages', $className);
    }
    
    /**
     * Register a plugin shortcode.
     * @param type $className
     */
    public function registerShortcode( $className ) {
        $this->registerItem('shortcodes', $className);
    }
    
    /**
     * Register a set of plugin shortcodes.
     * @param type $classes
     */
    public function registerShortcodes( $classes ) {
        foreach($classes as $className) $this->registerItem('shortcodes', $className);
    }
    
     /**
     * Register a plugin metabox.
     * @param type $className
     */
    public function registerMetabox( $className ) {
        $this->registerItem('metaboxes', $className);
    }
    
    /**
     * Register a set of plugin metaboxes.
     * @param type $classes
     */
    public function registerMetaboxes( $classes ) {
        foreach($classes as $className) $this->registerItem('metaboxes', $className);
    }
    
     /**
     * Register a plugin viewtable.
     * @param type $className
     */
    public function registerViewtable( $className ) {
        $this->registerItem('viewtables', $className);
    }
    
    /**
     * Register a set of plugin viewtables.
     * @param type $classes
     */
    public function registerViewtables( $classes ) {
        foreach($classes as $className) $this->registerItem('viewtables', $className);
    } 
    
     /**
     * Register a plugin type.
     * @param type $className
     */
    public function registerType( $className ) {
        $this->registerItem('types', $className);
    }
    
    /**
     * Register a set of plugin types.
     * @param type $classes
     */
    public function registerTypes( $classes ) {
        foreach($classes as $className) $this->registerItem('types', $className);
    } 
    
     /**
     * Register a plugin activation action.
     * @param type $className
     */
    public function registerActivation( $className ) {
        $this->itemMapping['activation'] = $className;
    }

    /**
     * Loads and returns plugin items specified by its name.
     */
    private function loadItem( $itemName, $create = false ) {

        if ( isset( $this->itemMapping[$itemName] ) ) {
            $mapping = $this->itemMapping[$itemName];
            if ( !$create ) return $mapping;
            
            if ( is_array($mapping) ) {
                $items = array();
                foreach($mapping as $map) $items[] = new $map( $this );
                return $items;
                
            } else {
                return new $mapping( $this );
            }
        }
        
        return null;
    }
    
    // ----------------------------------------------------------------------
    // Finding files
    // ----------------------------------------------------------------------
    
    /**
     * Returns a list of files at a given path.
     * @param string $path      path for search
     */
    private function findFiles( $path ) {
        return $this->findFileOrFolders($path, true); 
    }
    
    /**
     * Returns a list of folders at a given path.
     * @param string $path      path for search
     */
    private function findFolders( $path ) {
        return $this->findFileOrFolders($path, false); 
    }
    
    /**
     * Returns a list of files or folders at a given path.
     * @param string $path      path for search
     * @param bool $files       files or folders?
     */
    private function findFileOrFolders( $path, $areFiles = true ) {
        if ( !is_dir($path)) return array();
        
        $entries = scandir( $path );
        if (empty($entries)) return array();

        $files = array();
        foreach($entries as $entryName) {
            if ( $entryName == '.' || $entryName == '..') continue;
            
            $filename = $path . '/' . $entryName;
            if ( ( $areFiles && is_file($filename) ) || ( !$areFiles && is_dir($filename) ) ) {
                $files[] = array(
                    'path' => str_replace("\\", "/", $filename ),
                    'name' => $areFiles ? str_replace('.php', '', $entryName) : $entryName
                );
            }
        }
        return $files;  
    }
    
    /**
     * Gets php classes defined in a specified file.
     * @param type $path
     */
    private function getClasses( $path ) {

        $phpCode = file_get_contents( $path );
        
        $classes = array();
        $tokens = token_get_all($phpCode);

        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
          if ( 
              is_array($tokens) 
              && $tokens[$i - 2][0] == T_CLASS
              && $tokens[$i - 1][0] == T_WHITESPACE
              && $tokens[$i][0] == T_STRING) {

              $extends = null;
              if ($tokens[$i + 2][0] == T_EXTENDS && $tokens[$i + 4][0] == T_STRING) {
                  $extends = $tokens[$i + 4][1];
              }
              
              $class_name = $tokens[$i][1];
              $classes[$path] = array( 
                  'name' => $class_name,
                  'extends' => $extends
              );
          }
        }
        
        /**
         * result example:
         * 
         * $classes['/plugin/items/filename.php'] = array(
         *      'name'      => 'PluginNameItem',
         *      'extendes'  => 'PluginNameItemBase'
         * )
         */
        return $classes;   
    }
    
    // ----------------------------------------------------------------------
    // Plugin row on plugins.php page
    // ----------------------------------------------------------------------
    
    public function showCustomPluginRow($file, $plugin_data) {
        if ( !is_network_admin() && is_multisite() ) return;
        
        $messages = apply_filters('factory_fr107_plugin_row-' . $this->pluginName, array(), $file, $plugin_data);

        // if nothign to show then, use default handle
        if ( count($messages) == 0 ) {
            wp_plugin_update_row($file, $plugin_data);
            return;
        } 

        $wp_list_table = _get_list_table('WP_Plugins_List_Table');

        foreach($messages as $message) {
            echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';
            echo $message;
            echo '</div></td></tr>'; 
        }
    }
}