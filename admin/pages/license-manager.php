<?php
#build: free, premium

/**
 * License page is a place where a user can check updated and manage the license.
 */
class SocialLockerPluginManagerAdminPage extends OnePressPR108LicenseManagerAdminPage  {
 
    public $purchaseUrl = 'http://codecanyon.net/item/social-locker-for-wordpress/3667715/?ref=OnePress';
    public $purchasePrice = '$21';
    
    public function configure() {
// This condition is responsible for the license and features that it provides.
// Sure you can change it and get all features for free. Do it if you want.
// But please remember, we could encrypt this sources to protect it.
// But we entrusted you, the man who use our plugins, and we hope you will enjoy it.
// Thank you! Yours faithfully, OnePress.
// < condition start
global $socialLocker;
if ( in_array( $socialLocker->license->type, array( 'free' ) ) ) {

                $this->menuTitle = 'Social Locker';
                $this->menuIcon = '~/assets/admin/img/menu-icon.png';
            
}
// condition end >

// This condition is responsible for the license and features that it provides.
// Sure you can change it and get all features for free. Do it if you want.
// But please remember, we could encrypt this sources to protect it.
// But we entrusted you, the man who use our plugins, and we hope you will enjoy it.
// Thank you! Yours faithfully, OnePress.
// < condition start
global $socialLocker;
if ( !in_array( $socialLocker->license->type, array( 'free' ) ) ) {

                $this->menuPostType = 'social-locker';
            
}
// condition end >

        

    }
}

$socialLocker->registerPage('SocialLockerPluginManagerAdminPage');