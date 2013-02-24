<?php

/**
 * License page is a place where a user can check updated and manage the license.
 */
class PluginManagerAdminPage extends OnePressFR100LicenseManagerAdminPage  {
 
    public $purchaseUrl = 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=onepress';
    public $purchasePrice = '$21';
    
    public function configure() { global $socialLocker;
            $this->menuPostType = 'social-locker';
        

    }
}