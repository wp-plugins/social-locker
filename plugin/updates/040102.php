<?php

/**
 * Re-creating the leads table, adding the fields table for leads, adds the option 'opanda_catch_leads' for lockers.
 * 
 * @since 4.1.2
 */
class SocialLockerUpdate040102 extends Factory325_Update {

    public function install() {
        
        // updating the options Terms of Use and Privacy Policy
        
        $pageTermsOfUse = get_option('opanda_terms_of_use', false);
        $pagePrivacyPolicy = get_option('opanda_privacy_policy', false);
        
        if ( !empty( $pageTermsOfUse ) ) {
            add_option('opanda_terms_of_use_page', $pageTermsOfUse);
            add_option('opanda_terms_use_pages', true);
        }

        if ( !empty( $pagePrivacyPolicy ) ) {
            add_option('opanda_privacy_policy_page', $pagePrivacyPolicy);
            add_option('opanda_terms_use_pages', true);
        }

        delete_option('opanda_terms_of_use');
        delete_option('opanda_privacy_policy');
        
        add_option('opanda_terms_enabled', 1);
    }
}