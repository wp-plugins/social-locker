<?php
/**
 * The file contains the class to register a plugin in the Factory.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-core 
 * @since 1.0.0
 */

/**
 * Factory Plugin
 * 
 * @since 1.0.0
 */
class Factory320_Plugin {
    
    /**
     * Is a current page one of the admin pages?
     * 
     * @since 1.0.0
     * @var bool 
     */
    public $isAdmin;
    
    /**
     * A class name of an activator to activate the plugin.
     * 
     * @var string 
     */
    protected $activatorClass;
    
    /**
     * Creates an instance of Factory plugin.
     * 
     * @param $pluginPath A full path to the main plugin file.
     * @param $data A set of plugin data.
     * @since 1.0.0
     */
    public function __construct( $pluginPath, $data ) {
        $this->options = $data;
        
        // saves plugin basic paramaters
        $this->mainFile = $pluginPath;
        $this->pluginRoot = dirname( $pluginPath );
        $this->pluginSlug = basename($pluginPath);
        $this->relativePath = plugin_basename( $pluginPath );
        $this->pluginUrl = plugins_url( null, $pluginPath );
        
        // some extra params
        $this->pluginName = isset( $data['name'] ) ? $data['name'] : null;
        $this->pluginTitle = isset( $data['title'] ) ? $data['title'] : null;
        $this->version = isset( $data['version'] ) ? $data['version'] : null;
        $this->build = isset( $data['assembly'] ) ? $data['assembly'] : null;
        $this->tracker = isset ( $data['tracker'] ) ? $data['tracker'] : null;    
        $this->host = $_SERVER['HTTP_HOST'];

        // just caching this varibale
        $this->isAdmin = is_admin();

        // init actions
        $this->setupActions();
        
        // register activation hooks
        if ( is_admin() ) { 
            register_activation_hook( $this->mainFile, array($this, 'forceActivationHook') );
            register_deactivation_hook( $this->mainFile, array($this, 'deactivationHook') );
        }
        
        //do_action('factory_320_plugin_created', $this);
    }
    
    /**
     * Loads modules required for a plugin.
     * 
     * @since 3.2.0
     * @param mixed[] $modules
     * @return void
     */
    public function load( $modules = array() ) {
        foreach( $modules as $module ) {
            $this->loadModule( $module );
        }
    }
    
    /**
     * Loads a specified module.
     * 
     * @since 3.2.0
     * @param string $modulePath
     * @param string $moduleVersion
     * @return void
     */
    public function loadModule( $module ) {
        $scope = isset( $module[2] ) ? $module[2] : 'all';
        
        if ( 
            $scope == 'all' || 
            ( is_admin() && $scope == 'admin' ) || 
            ( !is_admin() && $scope == 'public' ) ) {
            
            require $this->pluginRoot . '/' . $module[0] . '/boot.php';
            do_action( $module[1] . '_plugin_created', $this );
        }
    }
    
    /**
     * Registers a class to activate the plugin.
     * 
     * @since 1.0.0
     * @param string A class name of the plugin activator.
     * @return void
     */
    public function registerActivation( $className ) {
        $this->activatorClass = $className;
    }
    
    /**
     * Setups actions related with the Factory Plugin.
     * 
     * @since 1.0.0
     */
    private function setupActions() {
        add_action('plugins_loaded', array($this, 'checkPluginVersioninDatabase'));  

        if ( $this->isAdmin ) {
            add_action('admin_init', array($this, 'customizePluginRow'), 20);
        }
    }
    
    /**
     * Checks the plugin version in database. If it's not the same as the currernt,
     * it means that the plugin was updated and we need to execute the update hook.
     * 
     * Calls on the hook "plugins_loaded".
     * 
     * @since 1.0.0
     * @return void
     */
    public function checkPluginVersioninDatabase() {

        // checks whether the plugin needs to run updates.
        if ( $this->isAdmin ) {
            $version = $this->getPluginVersionFromDatabase();

            if ( $version != $this->build . '-' . $this->version ) {
                $this->activationOrUpdateHook( false );
            }  
        }
    }
    
    /**
     * Returns the plugin version from database.
     * 
     * @since 1.0.0
     * @return string|null The plugin version registered in the database.
     */
    public function getPluginVersionFromDatabase() {
        $versions = get_option('factory_plugin_versions', array());
        $version = isset ( $versions[$this->pluginName] ) ? $versions[$this->pluginName] : null;
        
        // for combability with previous versions
        // @todo: remove after several updates
        if ( !$version ) {
            return get_option('fy_plugin_version_' . $this->pluginName, null );
        }
        
        return $version;
    }
    
    /**
     * Registers in the database a new version of the plugin.
     * 
     * @since 1.0.0
     * @return void
     */
    public function updatePluginVersionInDatabase() {
        $versions = get_option('factory_plugin_versions', array());
        $versions[$this->pluginName] = $this->build . '-' . $this->version;
        update_option('factory_plugin_versions', $versions);
    }
    
    /**
     * Customize the plugin row (on the page plugins.php).
     * 
     * Calls on the hook "admin_init".
     * 
     * @since 1.0.0
     * @return void
     */
    public function customizePluginRow() {
        remove_action("after_plugin_row_" . $this->relativePath, 'wp_plugin_update_row');
        add_action("after_plugin_row_" . $this->relativePath, array($this, 'showCustomPluginRow'), 10, 2);
    }
    
    /**
     * Executes an activation hook for this plugin immediately.
     * 
     * @since 1.0.0
     * @return void
     */
    public function forceActivationHook() {
        $this->activationOrUpdateHook(true);
    }
    
    /**
     * Executes an activation hook or an update hook.
     * 
     * @param bool $forceActivation If true, then executes an activation hook.
     * @since 1.0.0
     * @return void
     */
    public function activationOrUpdateHook( $forceActivation = false ) {

        $dbVersion = $this->getPluginVersionFromDatabase();
        do_action('factory_plugin_activation_or_update_' . $this->pluginName, $forceActivation, $dbVersion, $this);
        
        // there are not any previous version of the plugin in the past
        if ( !$dbVersion ) {
            $this->activationHook();
            
            $this->updatePluginVersionInDatabase();
            return;
        }

        $parts = explode('-', $dbVersion);
        $prevousBuild = $parts[0];
        $prevousVersion = $parts[1];

        // if another build was used previously
        if ( $prevousBuild != $this->build ) {
            $this->migrationHook($prevousBuild, $this->build);
            $this->activationHook();
            
            $this->updatePluginVersionInDatabase();
            return;
        }

        // if another less version was used previously
        if ( version_compare($prevousVersion, $this->version, '<') ){
            $this->updateHook($prevousVersion, $this->version); 
        }

        // standart plugin activation
        if ( $forceActivation ) {
            $this->activationHook();
        }
        
        // else nothing to do
        $this->updatePluginVersionInDatabase();
        return;
    }
    
    /**
     * It's invoked on plugin activation. Don't excite it directly.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function activationHook() {
        
        if ( !empty( $this->activatorClass )) {
            $className = $this->activatorClass;
            $activator = new $className( $this );
            $activator->activate();
        }
        
        do_action('factory_320_plugin_activation', $this);     
        do_action('factory_plugin_activation_' . $this->pluginName, $this);
    }
    
    /**
     * It's invoked on plugin deactionvation. Don't excite it directly.
     * 
     * @since 1.0.0
     * @return void
     */
    public function deactivationHook() {

        do_action('factory_320_plugin_deactivation', $this);     
        do_action('factory_plugin_deactivation-' . $this->pluginName, $this);
        
        if ( !empty( $this->activatorClass )) {
            $className = $this->activatorClass;
            $activator = new $className( $this );
            $activator->deactivate();
        }
    }
    
    /**
     * Finds migration items and install ones.
     * 
     * @since 1.0.0
     * @return void
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
     * 
     * @since 1.0.0
     * @return void
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
    
    /**
     * Converts string representation of the version to the numeric.
     * 
     * @since 1.0.0
     * @param string $version A string version to convert.
     * @return integer
     */
    protected function getVersionNumber($version) {

        preg_match('/(\d+)\.(\d+)\.(\d+)/', $version, $matches);
        if ( count($matches) == 0 ) return false;
        
        $number = '';
        $number .= ( strlen( $matches[1] ) == 1 ) ? '0' . $matches[1] : $matches[1];
        $number .= ( strlen( $matches[2] ) == 1 ) ? '0' . $matches[2] : $matches[2];
        $number .= ( strlen( $matches[3] ) == 1 ) ? '0' . $matches[3] : $matches[3];
        
        return intval($number);
    }

    // ----------------------------------------------------------------------
    // Plugin row on plugins.php page
    // ----------------------------------------------------------------------
    
    public function showCustomPluginRow($file, $plugin_data) {
        if ( !is_network_admin() && is_multisite() ) return;
        
        $messages = apply_filters('factory_plugin_row_' . $this->pluginName, array(), $file, $plugin_data);

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

        $phpCode = file_get_contents($path);
        
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
    // Public methods
    // ----------------------------------------------------------------------
    
    public function newScriptList() {
        return new Factory320_ScriptList( $this );
    }
    
    public function newStyleList() {
        return new Factory320_StyleList( $this );
    } 
}