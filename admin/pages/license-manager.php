<?php

/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnpSL_LicenseManagerPage extends OnpLicensing305_LicenseManagerPage  {
 
    public $purchaseUrl = 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress';
    public $purchasePrice = '$21';
    
    public function configure() {
            $this->menuPostType = 'social-locker';
        

    }
}

FactoryPages305::register($sociallocker, 'OnpSL_LicenseManagerPage');