<?php

/**
 * License page is a place where a user can check updated and manage the license.
 */
class OnpSL_LicenseManagerPage extends OnpLicensing312_LicenseManagerPage  {
 
    public $purchasePrice = '$22';
    
    public function configure() {
            $this->menuPostType = 'social-locker';
        

    }
}

FactoryPages311::register($sociallocker, 'OnpSL_LicenseManagerPage');