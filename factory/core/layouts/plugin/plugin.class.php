<?php
/**
 * Factory Plugin
 * 
 * It's a main class for building the plugin.
 * The class allows to isolate a several plugins that use the same version of the Factory.
 */
class FactoryFR103Plugin {
    
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
        $this->itemRoot = $this->pluginRoot . '/' . ( isset( $data['bricks'] ) ? $data['bricks'] : 'bricks' );  
        $this->templateRoot = $this->pluginRoot . '/' . isset( $data['templates'] ) ? $data['templates'] : 'templates';   
        $this->pluginUrl = plugins_url( null, $pluginPath );
        $this->pluginName = $data['name'];
        $this->version = $data['version'];
        $this->build = $data['assembly'];
        $this->host = $_SERVER['HTTP_HOST'];  

        $this->isAdmin = is_admin();

        // init actions
        $this->setupActions();
        
        // finds all factory items (caching is used)
        $this->findItems();

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
            add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
        }
    }
    
    public function actionPluginLoadded() {
        
        // checks whether the plugin needs to run updates.
        if ( $this->isAdmin ) {
            
            $dbVersion = get_option('fy_plugin_version_' . $this->pluginName, false);
            if ( $dbVersion != $this->build . '-' . $this->version ) {
                $this->activationOrUpdateHook( false );
            }  
        }
        
        do_action('factory_fr103_init', $this);
    }
    
    /**
     * WP Init action.
     * Don't excite it directly.
     */
    public function actionInit() {
        
        $this->shortcodes = new FactoryFR103ShortcodeManager( $this );   
        $this->metaboxes = new FactoryFR103MetaboxManager( $this );   
        
        if ( $this->isAdmin ) {
            
            $this->pages = new FactoryFR103AdminPageManager( $this ); 
        
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
    
    public function forceActivationHook() {
        $this->activationOrUpdateHook(true);
    }
    
    public function activationOrUpdateHook( $forceActivation = false ) {
        
         // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
        $this->findItems();
        
        do_action('factory_fr103_activation_or_update');
        
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
        $this->clearCache();
        $this->findItems();
        
        do_action('factory_fr103_deactivation');
        
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

        // clears cache that is used to store path and classes of Factory Items.
        $this->clearCache();
    }
    
    /**
     * Finds migration items and install ones.
     */
    public function migrationHook($previosBuild, $currentBuild) {
        
        $migrationFile = $this->itemRoot + '/updates/' . $previosBuild . '-' . $currentBuild . '.php';
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

        $updateFiles = $this->itemRoot . '/updates';
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
        
        wp_enqueue_style('factory-admin-global', FACTORY_FR103_URL . '/assets/css/admin-global.css');
        wp_enqueue_script('factory-admin-global', FACTORY_FR103_URL . '/assets/js/admin-global.js'); 
                        
	if ( in_array( $hook, array('post.php', 'post-new.php')) && $post )
        {
            if ( !empty( $this->types[$post->post_type] ) ) {
                
		wp_enqueue_style('factory-bootstrap', FACTORY_FR103_URL . '/assets/css/bootstrap.css');	
		wp_enqueue_script('factory-bootstrap', FACTORY_FR103_URL . '/assets/js/bootstrap.js', array('jquery'));
            }
            
        } elseif ( isset($_GET['page']) && in_array($_GET['page'], $this->pages->getIds())) {
            
            wp_enqueue_style('factory-bootstrap', FACTORY_FR103_URL . '/assets/css/bootstrap.css');	
            wp_enqueue_script('factory-bootstrap', FACTORY_FR103_URL . '/assets/js/bootstrap.js', array('jquery'));
        }
    }
    
    /**
     * Loads a module by its name.
     * @param type $moduleName
     */
    public function load( $path, $name ) {
        include($this->pluginRoot . '/' . $path . '/start.php');
        do_action('factory_fr103_load_' . $name, $this);
    }
    
    /**
     * Finds plugin items and creates mapping that is saved into the cache.
     */
    private function findItems() {
        
        // clears the cache after activation
        if ( defined('FACTORY_FR103_DEBUG') ) $this->clearCache();
        
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
                    'path' => str_replace("\\", "/", $filename ),
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