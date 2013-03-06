<?php
/**
 * Factory Plugin
 * 
 * It's a main class for building the plugin.
 * The class allows to isolate a several plugins that use the same version of the Factory.
 */
class FactoryFR100Plugin {
    
    /**
     * Main file of the plugin.
     * @var string
     */
    public $mainFile;
    
    public $pluginSlug;
    
    public $relativePath;
    
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
     * Licenase manger for a plugin.
     * @var FactoryFR100LicenseManager
     */
    public $license;
   
    /**
     * Creates an instance of Factory plugin.
     * 
     * @param string $pluginPath        An absolute path to the main plugin file.
     * @param string $pluginName        An unique plugin name.
     * @param string $version           A version of the plugin.
     * @param string $build             A build of the plugin.
     * @param string $factoryItems      A folder placed at $pluginPath that includes Factory items.
     * @param string $factoryTemplates  A folder placed at $pluginPath that includes Factory templates.
     */
    public function __construct( 
            $pluginPath, 
            $pluginName, 
            $version,
            $build,
            $server,
            $factoryItems = 'items', 
            $factoryTemplates = 'templates' ) {
        
        // saves plugin basic paramaters
        $this->mainFile = $pluginPath;
        $this->pluginSlug = basename($pluginPath);
        $this->relativePath = plugin_basename( $pluginPath );
        $this->itemRoot = dirname( $pluginPath ) . '/' . $factoryItems;  
        $this->templateRoot = dirname( $pluginPath ) . '/' . $factoryTemplates;  
        $this->pluginUrl = plugins_url( null, $pluginPath );
        $this->pluginName = $pluginName;
        $this->version = $version;
        $this->build = $build;
        $this->host = $_SERVER['HTTP_HOST'];  
        
        $this->isAdmin = is_admin();
        $this->license = new FactoryFR100LicenseManager( $this, $server );

        // init actions
        $this->setupActions();
        
        // finds all factory items (caching is used)
        $this->findItems();

        // register activation hooks
        if ( $this->isAdmin ) { 
            register_activation_hook( $this->mainFile, array($this, 'activationHook') );
            register_deactivation_hook( $this->mainFile, array($this, 'deactivationHook') );
        }
    }
    
    /**
     * Setups actions related with the Factory Plugin.
     */
    private function setupActions() {
        add_action('init', array($this, 'actionInit'));
        if ( $this->isAdmin ) {
            add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
        }
    }
    
    /**
     * WP Init action.
     * Don't excite it directly.
     */
    public function actionInit() {
        
        if ( $this->isAdmin ) { 
            $dbVersion = get_option('fy_plugin_version_' . $this->pluginName, false);
            if ( $dbVersion != $this->build . '-' . $this->version ) {
                $this->updateHook($dbVersion, $this->version);
                update_option('fy_plugin_version_' . $this->pluginName, $this->build . '-' . $this->version);
            }
        }
        
        $this->shortcodes = new FactoryFR100ShortcodeManager( $this );   
        $this->metaboxes = new FactoryFR100MetaboxManager( $this );   
        
        if ( $this->isAdmin ) {
            
            $this->pages = new FactoryFR100AdminPageManager( $this ); 
        
            // metaboxes
            // just includes class definition
            $metaboxes = $this->loadItem( 'metaboxes', true );
            $this->metaboxes->register( $metaboxes );
            
            // view tables
            // just includes class definition
            $this->loadItem( 'viewtables', false );
            
            // admin pages
            $pages = $this->loadItem( 'pages', true );
            $this->pages->register( $pages );
        }
        
        // types
        $types = $this->loadItem( 'types', true );
        foreach($types as $type) { 
            $this->types[$type->name] = $type;
            $type->register();
        }
        
        // shortcodes
        $shortcodes = $this->loadItem( 'shortcodes', true ); 
        $this->shortcodes->register( $shortcodes );
    }
    
    /**
     * It's invoked on plugin actionvation.
     * Don't excite it directly.
     */
    public function activationHook() {
        
         // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
        
        // set cron tasks
        $this->license->activationHook();
        
        $item = $this->loadItem( 'activation', true );
        $item->activate();     
        
        // sets type capabilities for roles
        $types = $this->loadItem( 'types', true );
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
        
        // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
    }
    
    /**
     * It's invoked on plugin deactionvation.
     * Don't excite it directly.
     */
    public function deactivationHook() {
        
        // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
        
        // clear cron tasks
        $this->license->deactivationHook();
        
        $item = $this->loadItem( 'activation', true );
        $item->deactivate();  
        
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        
        // remove type capabilities for roles
        $types = $this->loadItem( 'types', true );
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
        
        // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
    }
    
    public function updateHook( $old, $new ) {
        
        $this->clearCache();
        $this->findItems();
        $item = $this->loadItem( 'activation', true );
        $item->update( $old, $new ); 
    }
     
    /**
     * WP admin_enqueue_scripts action.
     * Don't excite directly.
     */
    public function actionAdminScripts( $hook ) {
	global $post;
        
	if ( in_array( $hook, array('post.php', 'post-new.php')) && $post )
        {
            if ( !empty( $this->types[$post->post_type] ) ) {
                
		wp_enqueue_style('factory-bootstrap', FACTORY_FR100_URL . '/assets/css/bootstrap.css');	
		wp_enqueue_script('factory-bootstrap', FACTORY_FR100_URL . '/assets/js/bootstrap.js', array('jquery'));
            }
            
        } elseif ( isset($_GET['page']) && in_array($_GET['page'], $this->pages->getIds())) {
            
            wp_enqueue_style('factory-bootstrap', FACTORY_FR100_URL . '/assets/css/bootstrap.css');	
            wp_enqueue_script('factory-bootstrap', FACTORY_FR100_URL . '/assets/js/bootstrap.js', array('jquery'));
        }
        
        $licenseType = defined('FACTORY_FR100_LICENSE_TYPE') ? FACTORY_FR100_LICENSE_TYPE : $this->license->data['Category'];
        $buildType = defined('FACTORY_FR100_BUILD_TYPE') ? FACTORY_FR100_BUILD_TYPE : $this->license->data['Build'];      
        ?>
        <script>
            window['<?php echo $this->pluginName ?>-license'] = '<?php echo $licenseType ?>';
            window['<?php echo $this->pluginName ?>-build'] = '<?php echo $buildType ?>';   
        </script>
        <?php
    }
    
    /**
     * Loads a module by its name.
     * @param type $moduleName
     */
    public function load( $moduleName ) {
        include_once(FACTORY_FR100_DIR . '../../modules/' . $moduleName . '/start.php');
    }
    
    /**
     * Finds plugin items and creates mapping that is saved into the cache.
     */
    private function findItems() {
        
        // clears the cache after activation
        if ( defined('FACTORY_FR100_DEBUG') ) $this->clearCache();
        
        $cached = $this->getCache('items');
        if ( $cached ) {
            $this->itemMapping = $cached;
            return;
        }

        $files = $this->findFiles( $this->itemRoot );
        $folders = $this->findFolders( $this->itemRoot );  
        
        $this->itemMapping = array();
        foreach($files as $file) {
            $this->itemMapping[$file['name']] = $this->extractMapping( $file, false );
        }

        foreach($folders as $folder) {
            $files = $this->findFiles( $folder['path'] );
            $this->itemMapping[$folder['name']] = $this->extractMapping( $files );
        }

        $this->setCache('items', $this->itemMapping);
    }
    
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
                    'path' => addslashes( $filename ),
                    'name' => $areFiles ? str_replace('.php', '', $entryName) : $entryName
                );
            }
        }
        return $files;  
    }
    
    /**
     * Returns mapping information about classes and paths where the classes are.
     * @param array $files      a set of files to extract
     * @return mixed
     */
    private function extractMapping( $files, $isArray = true ) {

        if ( $isArray ) {

            $classes = array();  
            foreach( $files as $file ) {
                $classes = array_merge($classes, $this->getClasses( $file['path'] ));
            }
            return $this->sortClasses($classes);
            
        } else {
            
            $file = $files;
            $classes = $this->getClasses( $file['path'] );
            $class = current($classes);
            
            return array(
                'class' => $class['name'],
                'path' => $file['path'],
            );
        }
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
    
    /**
     * Sort classes in order to have ability to includes the ones without any problems 
     * according their dependencies. The method getClasses is a source of the argument.
     * 
     * @param mixed $classes    clasess to sort
     * @return mixed
     */
    private function sortClasses( $classes ) {
        $resultClasses = array();
        $securityCounter = 0;
        
        while($securityCounter <= 10) {

            $workClass = $classes;
            if (empty($workClass)) break;

            foreach($workClass as $classPath => $checkClass) {

                $hasExtender = !empty($checkClass['extends']);
                $isFound = false;
                
                // if a class has an extender, trying to find the one in other files
                if ( $hasExtender ) {
                    foreach($classes as $item) {
                        if ($item['name'] == $checkClass['extends']) {
                            $isFound = true;
                            break;
                        }
                    }
                }

                // if a class doesn't have an extender or the one was not found in other files,
                // then adding a current to the result
                if (!$hasExtender || !$isFound) {
                    unset($classes[$classPath]);
                    $resultClasses[] = array(
                        'class' => $checkClass['name'],
                        'path' => $classPath,
                    );
                }
            }
            $securityCounter++;
        }
        return $resultClasses;
    }
    
    /**
     * Loads and returns plugin items specified by its name.
     * 
     * @param string $itemName      an item name to register 
     * @param bool $create          create items or not?
     * @return mixed
     */
    private function loadItem( $itemName, $create = false ) {

        if ( isset( $this->itemMapping[$itemName] ) ) {
            $mapping = $this->itemMapping[$itemName];

            if ( !isset( $mapping['path'] ) ) {
                
                $items = array();
                foreach($mapping as $map) {
                    include_once( $map['path'] );
                    $items[] = ( $create ) ? new $map['class']( $this ) : $map['class'];
                }
                return $items;
                
            } else {

                include_once( $mapping['path'] );
                return ( $create ) ? new $mapping['class']( $this ) : $map['class'];
            }
        }
        return null;
    }
    
    // ----------------------------------------------------------------------
    // Caching for plugin items
    // ----------------------------------------------------------------------
    
    /**
     * Get cached data. 
     * The data is stored in database as a wordpress site option.
     * 
     * @param type $name    Cache item name.
     * @return mixed          
     */
    private function getCache( $name ) {
        $optionName = 'fy_' . $this->pluginName . '_' . $name;
        return get_option($optionName);
    }
    
    /**
     * Set cached data. 
     * The data is stored in database as a wordpress site option.
     * 
     * @param type $name    Cache item name.
     * @param type $value   Value to save into the cache.
     * @return mixed          
     */
    private function setCache( $name, $value ) {
        $optionName = 'fy_' . $this->pluginName . '_' . $name;
        update_option($optionName, $value );
        return true;
    }
    
    /**
     * Delete all cached data that was stored by the Factory plugin.
     */
    public function clearCache() {
        $optionName = 'fy_' . $this->pluginName . '_items';
        delete_option($optionName);
    }
}