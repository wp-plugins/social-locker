<?php

/**
 * Re-creating the leads table, adding the fields table for leads, adds the option 'opanda_catch_leads' for lockers.
 * 
 * @since 1.0.7
 */
class SocialLockerUpdate040008 extends Factory325_Update {

    public function install() {
        
        $done = get_option('opanda_upgrade_010007', false);
        if ( $done ) return;
        
        update_option('opanda_upgrade_010007', true);
        
        // adds new fields to the table of leads and create a table for lead fields
        
        $activator = new OPanda_Activation( $this->plugin );
        $activator->activate();
        
        // adds the option 'opanda_catch_leads'
        
        $lockers = get_posts(array(
            'post_type' => OPANDA_POST_TYPE,
            'numberposts' => -1
        ));
        
        foreach( $lockers as $locker ) {
            add_post_meta( $locker->ID, 'opanda_catch_leads', 1);
        }
        
        // importing old data from the table of leads
        
        // - copying the data between fields
        
        global $wpdb;
        $wpdb->query('UPDATE ' . $wpdb->prefix . 'opanda_leads SET lead_referer = lead_post_url, lead_email_confirmed = lead_confirmed, lead_subscription_confirmed = lead_confirmed');
        
        // - copying the social profile urls
        
        $results = $wpdb->get_results('SELECT ID, lead_social_profile FROM ' . $wpdb->prefix . 'opanda_leads' );
        $fields = array();
        
        foreach( $results as $row ) {
            if ( empty( $row->lead_social_profile) ) continue;
            
            if ( preg_match( '/facebook/i', $row->lead_social_profile) ) {
                $fields[] = array( $row->ID, 'facebookUrl', $row->lead_social_profile );
            } elseif ( preg_match( '/twitter/i', $row->lead_social_profile) ) {
                $fields[] = array( $row->ID, 'twitterUrl', $row->lead_social_profile );
            } elseif ( preg_match( '/google/i', $row->lead_social_profile) ) {
                $fields[] = array( $row->ID, 'googleUrl', $row->lead_social_profile );
            } elseif ( preg_match( '/linkedin/i', $row->lead_social_profile) ) {
                $fields[] = array( $row->ID, 'linkedinUrl', $row->lead_social_profile );
            } 
        }
        
        if ( !empty( $fields ) ){
            
            $sql = 'INSERT INTO ' . $wpdb->prefix . 'opanda_leads_fields (lead_id,field_name,field_value) VALUES ';
            $values = array();
            
            foreach( $fields as $row ) {
                $values[] = $wpdb->prepare('(%d,%s,%s)', $row[0], $row[1], $row[2]);
            }
            
            $sql .= implode(',', $values);
            $wpdb->query($sql);
        }
        
        $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'opanda_leads DROP COLUMN lead_post_url, DROP COLUMN lead_confirmed, DROP COLUMN lead_catcher, DROP COLUMN lead_catcher_data, DROP COLUMN lead_social_profile');
    }
}