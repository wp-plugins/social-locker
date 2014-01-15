<?php
/**
 * The file contains a class that is a base for all activator classes using licensing.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * A base activator that all activator using licensing.
 * 
 * @since 1.0.0
 */
class OnpLicensing300_Activator extends Factory300_Activator {
    
    public function activate() {

            $this->license(array(
                'Category'      => 'free',
                'Build'         => 'free',
                'Title'         => 'OnePress Public License',
                'Description'   => 'Public License is a GPLv2 compatible license. 
                                    It allows you to change this version of the plugin and to
                                    use the plugin free. Please remember this license 
                                    covers only free edition of the plugin. Premium versions are 
                                    distributed with other type of a license.'
            ));
        

    }
    
    /**
     * Installs default license.
     * @param type $data
     */
    protected function license($data) {
        $data['Activated'] = 0; 
        $data['Expired'] = 0; 
        
        $defaultLicense = get_option('onp_default_license_' . $this->plugin->pluginName, null);

        if ( empty($defaultLicense) ) {
            update_option('onp_default_license_' . $this->plugin->pluginName, $data);    
        }
    }
}