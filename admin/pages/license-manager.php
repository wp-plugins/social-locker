<?php

/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnpSL_LicenseManagerPage extends OnpLicensing308_LicenseManagerPage  {
 
    public $purchasePrice = '$23';
    
    public function configure() {
            $this->menuPostType = 'social-locker';
        

    }
}

FactoryPages306::register($sociallocker, 'OnpSL_LicenseManagerPage');