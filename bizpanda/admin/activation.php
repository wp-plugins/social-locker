<?php

/**
 * Activator for the Business Panda.
 * 
 * @see Factory325_Activator
 * @since 1.0.0
 */
class OPanda_Activation extends Factory325_Activator {
    
    /**
     * Runs activation actions.
     * 
     * @since 1.0.0
     */
    public function activate() {   
        global $bizpanda;

        do_action('before_bizpanda_activation', $bizpanda, $this);
        
        $this->importOptions();
        $this->presetOptions();
        
        $this->createPolicies();
        $this->createTables();
        
        do_action('after_bizpanda_activation', $bizpanda, $this);
    }
    
    /**
     * Converts options starting with 'optinpanda_' to 'opanda_'.
     * 
     * @since 1.0.0
     */
    protected function importOptions() {
        global $wpdb;
        
        $wpdb->query("UPDATE {$wpdb->options} SET option_name = REPLACE(option_name, 'optinpanda_', 'opanda_') WHERE option_name LIKE 'optinpanda_%'");
        $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_key = REPLACE(meta_key, 'optinpanda_', 'opanda_') WHERE meta_key LIKE 'optinpanda_%'");
    
        // Convers options of the Social Locker to the options of the Opt-In Panda

        $wpdb->query("UPDATE {$wpdb->options} SET option_name = REPLACE(option_name, 'sociallocker_', 'opanda_') WHERE option_name LIKE 'sociallocker_%'");
        $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_key = REPLACE(meta_key, 'sociallocker_', 'opanda_') WHERE meta_key LIKE 'sociallocker_%'");        
    }

    /**
     * Presets some options required for the plugin.
     * 
     * @since 1.0.0
     */
    protected function presetOptions() {
        
	add_option('opanda_facebook_appid', '117100935120196');
        add_option('opanda_facebook_version', 'v2.3');
        
	add_option('opanda_lang', 'en_US');
        add_option('opanda_short_lang', 'en');
  
        add_option('opanda_tracking', 'true');
        add_option('opanda_just_social_buttons', 'false');
        add_option('opanda_subscription_service', 'database');        
    }
    
    /**
     * Creates pages containing the default policies.
     * 
     * @since 1.0.0
     */
    protected function createPolicies() {

        add_option('opanda_terms_enabled', 1);
        add_option('opanda_terms_use_pages', 0);
        add_option('opanda_terms_of_use_text', file_get_contents( OPANDA_BIZPANDA_DIR . '/content/terms-of-use.html' ));
        add_option('opanda_privacy_policy_text', file_get_contents( OPANDA_BIZPANDA_DIR . '/content/privacy-policy.html' ));
    }
        
    /**
     * Creates table required for the plugin.
     * 
     * @since 1.0.0
     */
    protected function createTables() {
        
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // leads
        
        $leads = "
            CREATE TABLE {$wpdb->prefix}opanda_leads (
              ID int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              lead_display_name varchar(255) DEFAULT NULL,
              lead_name varchar(100) DEFAULT NULL,
              lead_family varchar(100) DEFAULT NULL,
              lead_email varchar(50) NOT NULL,
              lead_date int(11) NOT NULL,
              lead_email_confirmed int(1) NOT NULL DEFAULT 0 COMMENT 'email',
              lead_subscription_confirmed int(1) NOT NULL DEFAULT 0 COMMENT 'subscription',
              lead_ip varchar(45) DEFAULT NULL,
              lead_item_id int(11) DEFAULT NULL,
              lead_post_id int(11) DEFAULT NULL,
              lead_item_title varchar(255) DEFAULT NULL,
              lead_post_title varchar(255) DEFAULT NULL,
              lead_referer text DEFAULT NULL,
              PRIMARY KEY  (ID),
              UNIQUE KEY lead_email (lead_email)
            );";

        dbDelta($leads); 
        
        // leads fields
        
        $leadsFields = "
            CREATE TABLE {$wpdb->prefix}opanda_leads_fields (
                lead_id int(10) UNSIGNED NOT NULL,
                field_name varchar(255) NOT NULL,
                field_value text NOT NULL,
                field_custom bit(1) NOT NULL DEFAULT b'0',
                KEY IDX_wp_opanda_leads_fields_field_name (field_name),
                UNIQUE KEY UK_wp_opanda_leads_fields (lead_id,field_name)
            );";

        dbDelta($leadsFields); 
        
        // stats
        
        $stats = "
            CREATE TABLE {$wpdb->prefix}opanda_stats_v2 (
              ID bigint(20) NOT NULL AUTO_INCREMENT,
              aggregate_date date NOT NULL,
              post_id bigint(20) NOT NULL,
              item_id int(11) NOT NULL,
              metric_name varchar(50) NOT NULL,
              metric_value int(11) NOT NULL DEFAULT 0,
              PRIMARY KEY  (ID),
              UNIQUE KEY UK_opanda_stats_v2 (aggregate_date,item_id,post_id,metric_name)
            );";

        dbDelta($stats); 
    }
}

$bizpanda->registerActivation('OPanda_Activation');

function bizpanda_cancel_plugin_deactivation( $cancel ) {
    if ( !BizPanda::isSinglePlugin() ) return true;
    return $cancel;
}
add_filter('factory_cancel_plugin_deactivation_bizpanda', 'bizpanda_cancel_plugin_deactivation');