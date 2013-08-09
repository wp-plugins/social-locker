<?php

/**
 * Class is used to manage the module data.
 */
class FactoryLicensingFR108Module {
    
    /**
     * Current plugin
     * @var FactoryPlugin 
     */
    public $plugin;
    
    public function __construct( $plugin ) {

        // licensing
        $this->plugin = $plugin;
        $this->license = new FactoryLicensingFR108Manager( $plugin );
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

add_action('factory_fr108_load_licensing', 'factory_licensing_fr108_module_load');
function factory_licensing_fr108_module_load( $plugin ) {
    new FactoryLicensingFR108Module( $plugin ); 
}