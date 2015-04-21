<?php 
/**
 * License page is a place where a user can check updated and manage the license.
 */
class SocialLocker_LicenseManagerPage extends OnpLicensing325_LicenseManagerPage  {
 
    public $purchasePrice = '$24';
    
    public function configure() {
                $this->purchasePrice = '$24';
            

        

            $this->menuPostType = OPANDA_POST_TYPE;
        

    }
}

FactoryPages321::register($sociallocker, 'SocialLocker_LicenseManagerPage');
 