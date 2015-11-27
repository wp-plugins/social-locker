<?php

/**
 * Re-creating the leads table, adding the fields table for leads, adds the option 'opanda_catch_leads' for lockers.
 * 
 * @since 4.1.2
 */
class SocialLockerUpdate040109 extends Factory325_Update {

    public function install() {

        // adds the option 'opanda_catch_leads'
        
        $lockers = get_posts(array(
            'post_type' => OPANDA_POST_TYPE,
            'numberposts' => -1
        ));
        
        foreach( $lockers as $locker ) {
            $itemType = get_post_meta($locker->ID, 'opanda_item', true);
            if ( 'email-locker' !== $itemType ) continue;
            
            $requireName = get_post_meta($locker->ID, 'opanda_subscribe_name', true);
            add_post_meta( $locker->ID, 'opanda_form_type', empty( $requireName ) ? 'email-form' : 'name-email-form' );
        }
        
        // updaing the database
        
        global $wpdb;
        
        $sql = "
            CREATE TABLE {$wpdb->prefix}opanda_leads_fields (
                lead_id int(10) UNSIGNED NOT NULL,
                field_name varchar(255) NOT NULL,
                field_value text NOT NULL,
                field_custom bit(1) NOT NULL DEFAULT b'0',
                KEY IDX_wp_opanda_leads_fields_field_name (field_name),
                UNIQUE KEY UK_wp_opanda_leads_fields (lead_id,field_name)
            );";
            
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);  
    }
}