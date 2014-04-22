<?php

/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnpSL_LicenseManagerPage extends OnpLicensing310_LicenseManagerPage  {
 
    public $purchasePrice = '$23';
    
    public function configure() {
            $this->menuPostType = 'social-locker';
        

    }
}

FactoryPages308::register($sociallocker, 'OnpSL_LicenseManagerPage');