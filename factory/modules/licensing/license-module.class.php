<?php

/**
 * Class is used to manage the module data.
 */
class FactoryLicensingFR103Module {
    
    /**
     * Current plugin
     * @var FactoryPlugin 
     */
    public $plugin;
    
    /**
     * Current license manager.
     * @var FactoryLicenseManager 
     */
    public $license;
    
    public function __construct( $plugin ) {

        // licensing
        $this->plugin = $plugin;
        $this->license = new FactoryLicenseManager( $plugin, $this->plugin->options['api'] );
        $this->plugin->license = $this->license;
        add_action('admin_enqueue_scripts', array($this, 'actionAdminScripts'));
    }
    
    public function actionAdminScripts() {
        
        $licenseType = defined('FACTORY_LICENSE_TYPE') ? FACTORY_LICENSE_TYPE : $this->license->data['Category'];
        $buildType = defined('FACTORY_BUILD_TYPE') ? FACTORY_BUILD_TYPE : $this->license->data['Build'];      
        ?>
        <script>
            window['<?php echo $this->plugin->pluginName ?>-license'] = '<?php echo $licenseType ?>';
            window['<?php echo $this->plugin->pluginName ?>-build'] = '<?php echo $buildType ?>';   
        </script>
        <?php
    }
}
add_action('factory_fr103_load_licensing', 'licensing_module_load');
function licensing_module_load( $plugin ) {
    new FactoryLicensingFR103Module( $plugin ); 
}