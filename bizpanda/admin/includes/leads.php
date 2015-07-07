<?php

class OPanda_Leads {
    
    /**
     * Adds a new lead.
     * 
     * @since 1.0.7
     * @param string[] $identity An array of the identity data.
     * @param string[] $context An array of the context data.
     * @param bool $confirmed Has a lead confirmed one's email address?
     * @return int A lead ID.
     */
    public static function add( $identity = array(), $context = array(), $emailConfirmed = false, $subscriptionConfirmed = false ) {
        $email = isset( $identity['email'] ) ? $identity['email'] : false;
 
        $lead = self::getByEmail( $email );
        return self::save( $lead, $identity, $context, $emailConfirmed, $subscriptionConfirmed );
    }
    
    /**
     * Inserts or updates a lead in the database.
     * 
     * @since 1.0.7
     * @param objecy $lead A lead to update.
     * @param string[] $identity An array of the identity data.
     * @param string[] $context An array of the context data.
     * @param bool $confirmed Has a lead confirmed one's email address?
     * @return int A lead ID.
     */
    public static function save( $lead = null, $identity = array(), $context = array(), $emailConfirmed = false, $subscriptionConfirmed = false ) {
        global $wpdb;
        
        $email = isset( $identity['email'] ) ? $identity['email'] : false;
        if ( isset( $identity['social'] ) ) $emailConfirmed = true;
        
        $itemId = isset( $context['itemId'] ) ? intval( $context['itemId'] ) : 0;
        $postId = isset( $context['postId'] ) ? intval( $context['postId'] ) : null;

        $item = get_post( $itemId );
        $itemTitle = !empty( $item ) ? $item->post_title : null;
        $postTitle = self::extract('postTitle', $context);
        $postUrl = self::extract('postUrl', $context); 

        $name = self::extract('name', $identity);
        $family = self::extract('family', $identity);
        
        $displayName = self::extract('displayName', $identity );
        if ( empty( $displayName ) ) {

            if ( !empty( $name ) && !empty( $family ) ) {
                $displayName = $name . ' ' . $family;
            } elseif ( !empty( $name ) ) {
                $displayName = $name;
            } else {
                $displayName = $family;
            }
        }

        $leadId = empty( $lead ) ? null : $lead->ID;

        // counts the number of confirmed emails (subscription)
        if ( $subscriptionConfirmed && $leadId && !$lead->lead_subscription_confirmed ) {
            require_once OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php';
            OPanda_Stats::countMetrict( $itemId, $postId, 'email-confirmed');
        }
            
        if ( !$leadId ) {
            
            // counts the number of new recivied emails
            OPanda_Stats::countMetrict( $itemId, $postId, 'email-received');
            
            $data = array(
                'lead_display_name' => $displayName,
                'lead_name' => $name,
                'lead_family' => $family,
                'lead_email' => $email,
                'lead_date' => time(),
                'lead_item_id' => $itemId,
                'lead_post_id' => $postId,
                'lead_item_title' => $itemTitle,   
                'lead_post_title' => $postTitle,
                'lead_referer' => $postUrl,
                'lead_email_confirmed' => $emailConfirmed ? 1 : 0,        
                'lead_subscription_confirmed' => $subscriptionConfirmed ? 1 : 0,
                'lead_ip' => self::getIP()
            );
            
            // else inserts a new lead
            $result = $wpdb->insert( $wpdb->prefix . 'opanda_leads', $data, array(
                '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%d', '%s'
            ));
            
            if ( $result ) $leadId = $wpdb->insert_id;
            
        } else {
            
            $data = array(
                'lead_display_name' => $displayName,
                'lead_name' => $name,
                'lead_family' => $family,
                'lead_email_confirmed' => $emailConfirmed ? 1 : 0,
                'lead_subscription_confirmed' => $subscriptionConfirmed ? 1 : 0
            );
            
            $formats = array(
                'lead_display_name' => '%s',
                'lead_name' => '%s',
                'lead_family' => '%s',
                'lead_email_confirmed' => '%d',
                'lead_subscription_confirmed' => '%d'
            );
            
            if ( empty( $displayName ) ) {
                unset( $data['lead_display_name'] );
                unset( $formats['lead_display_name'] );
            }
            
            if ( empty( $name ) ) {
                unset( $data['lead_display_name'] );
                unset( $formats['lead_display_name'] );
            }
            
            if ( empty( $family ) ) {
                unset( $data['lead_display_name'] );
                unset( $formats['lead_display_name'] );
            }
            
            if ( !$emailConfirmed || $lead->lead_email_confirmed  ) {
                unset( $data['lead_email_confirmed'] );
                unset( $formats['lead_email_confirmed'] );
            }   
            
            if ( !$subscriptionConfirmed || $lead->lead_subscription_confirmed  ) {
                unset( $data['lead_subscription_confirmed'] );
                unset( $formats['lead_subscription_confirmed'] );
            }   
            
            if ( !empty( $data ) ) {
                
                $where = array(
                     'lead_email' => $email
                );
                
                $wpdb->update( $wpdb->prefix . 'opanda_leads', $data, $where, array_values( $formats ), array( '%s' ));
            }
        }
        
        // saving extra fields

        $fields = array();

        foreach( $identity as $itemName => $itemValue ) {
            if ( in_array( $itemName, array( 'email', 'name', 'family', 'displayName' ) ) ) continue;
            if ( 'image' === $itemName ) $itemName = 'externalImage';
            $fields[trim( $itemName, '{}') ] = array('value' => $itemValue, 'custom' => ( strpos($itemName, '{') === 0 )  ? 1 : 0 );
        }

        foreach( $fields as $fieldName => $fieldData ) {
            
            $sql = $wpdb->prepare("
                INSERT INTO {$wpdb->prefix}opanda_leads_fields
                ( lead_id, field_name, field_value, field_custom )
                VALUES ( %d, %s, %s, %d ) ON DUPLICATE KEY UPDATE field_value = VALUES(field_value)
            ", $leadId, $fieldName, $fieldData['value'], $fieldData['custom'] );

            $wpdb->query( $sql );
        }
        
        return $leadId;
    }
    
    private static $_leads = array();
    
    /**
     * Returns a lead.
     */
    public static function get( $leadId ) {
        if ( isset( self::$_leads[$leadId] ) ) return self::$_leads[$leadId];
        
        global $wpdb; 
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opanda_leads WHERE ID = %d", $leadId ) );  
        
        self::$_leads[$leadId] = $lead;
        return $lead;
    }
    
    /**
     * Returns custom fields
     */
    public static function getCustomFields( $leadId = null ) {
        
        if ( !empty( $leadId )) {
        
            global $wpdb; 
            $data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opanda_leads_fields WHERE lead_id = %d AND field_custom = 1", $leadId ), ARRAY_A );

            $customFields = array();
            foreach( $data as $item ) {

                $name = $item['field_name'];
                if ( strpos( $name, '_' ) === 0 ) continue;
                $customFields[$item['field_name']] = $item['field_value'];

                $fields[$name] = strip_tags( $item['field_value'] );
            }
            
            return $customFields;
        
        } else {
            
            global $wpdb; 
            $fields = $wpdb->get_results( "SELECT field_name FROM {$wpdb->prefix}opanda_leads_fields WHERE field_custom = 1 GROUP BY field_name" );
            return $fields;
        }
    }
    
    private static $_fields = array();
    
    /**
     * Returns all fields of a given lead.
     * 
     * @since 1.0.7
     * @param int $leadId An id of a lead which contains fields to return.
     * @return string[]
     */
    public static function getLeadFields( $leadId ) {
        if ( isset( self::$_fields[$leadId] ) ) return self::$_fields[$leadId];
        
        global $wpdb; 
        $data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opanda_leads_fields WHERE lead_id = %d", $leadId ), ARRAY_A );
        
        $fields = array();

        foreach( $data as $item ) {
            $fields[$item['field_name']] = $item['field_value'];
        }
        
        self::$_fields[$leadId] = $fields;
        return $fields;
    }
    
    /**
     * Returns a given field of a lead.
     * 
     * @since 1.0.7
     * @param int $leadId An id of a lead which contains fields to return.
     * @param string $fieldName A field name to return.
     * @param mixed $default A default value to return if the field is not found in the database.
     * @return string
     */
    public static function getLeadField( $leadId, $fieldName, $default = null ) {
        $fields = self::getLeadFields( $leadId );
        return isset( $fields[$fieldName] ) ? $fields[$fieldName] : $default;
    }
    
    /**
     * Removes a field of a given lead.
     *
     * @since 1.0.7
     * @param int $leadId An id of a lead which contains a field to remove.
     * @param string $fieldName A field name to remove.
     * @return void
     */
    public static function removeLeadField( $leadId, $fieldName ) {
        self::updateLeadField( $leadId, $fieldName, null );
    }
    
    /**
     * Updates a field of a given lead.
     *
     * @since 1.0.7
     * @param int $leadId An id of a lead which contains a field to update.
     * @param string $fieldName A field name to update.
     * @param string $fieldValue A field value to set.
     * @return void
     */
    public static function updateLeadField( $leadId, $fieldName, $fieldValue ) {
        global $wpdb;
        
        if ( !isset( self::$_fields[$leadId] ) ) {
            self::$_fields[$leadId] = self::getLeadFields( $leadId );
        }
        
        if ( empty( $fieldValue ) ) {
            
            $wpdb->query( $wpdb->prepare("
                DELETE FROM {$wpdb->prefix}opanda_leads_fields
                WHERE lead_id = %d AND field_name = %s
            ", $leadId, $fieldName ));
                
            unset( self::$_fields[$leadId][$fieldName] );                
            return;
        }
        
        $wpdb->query( $wpdb->prepare("
            INSERT INTO {$wpdb->prefix}opanda_leads_fields
            ( lead_id, field_name, field_value )
            VALUES ( %d, %s, %s )
            ON DUPLICATE KEY UPDATE
            field_value = VALUES(field_value)
        ", $leadId, $fieldName, $fieldValue ));
            
        self::$_fields[$leadId][$fieldName] = $fieldValue;
    }
    
    /**
     * Return an URL of the image to use as an avatar.
     * 
     * @since 1.0.7
     * @param int $leadId A lead ID for which we need to return the URL of the avatar.
     * @param int $size A size of the avatar (px).
     * @return string
     */
    public static function getAvatarUrl( $leadId, $email = null, $size = 40 ) {

        $imageSource = OPanda_Leads::getLeadField( $leadId, 'externalImage', null );
        $image = OPanda_Leads::getLeadField( $leadId, '_image' . $size, null );
        
        // getting an avatar from cache
        
        if ( !empty( $image ) ) {
            $upload_dir = wp_upload_dir(); 
     
            $path = $upload_dir['path'] . '/bizpanda/avatars/' . $image;
            $url = $upload_dir['url'] . '/bizpanda/avatars/' . $image;

            if ( file_exists( $path ) ) return $url;
            self::removeLeadField($leadId, '_image' . $size);
        }
        
        // trying to process an external image
        
        if ( !empty( $imageSource ) && function_exists('wp_get_image_editor') ) {
            return admin_url('admin-ajax.php?action=opanda_avatar&opanda_lead_id=' . $leadId) . '&opanda_size=' . $size;
        } 
        
        // else return a gravatar
        
        $gravatar = get_avatar( $email, $size );
        if ( preg_match('/https?\:\/\/[^\'"]+/i', $gravatar, $match) ) {
            return $match[0];
        }

        return null;
    }
    
    /**
     * Return a HTML code markup to display avatar.
     * 
     * @since 1.0.7
     * @param int $leadId A lead ID for which we need to return the URL of the avatar.
     * @param int $size A size of the avatar (px).
     * @return string HTML
     */
    public static function getAvatar( $leadId, $email = null, $size = 40 ) {
        
        $url = self::getAvatarUrl( $leadId, $email, $size );
        if ( empty( $url ) ) return null;
        
        $alt = __('User Avatar', 'bizpanda');
        return "<img src='$url' width='$size' height='$size' alt='$alt' />";
    }
    
    /**
     * Returns an URL of the social profile of the lead.
     * 
     * @since 1.0.7
     * @param int $leadId A lead ID for which we need to return an URL of the social profile.
     * @return string|false An URL of the social profile of the lead.
     */
    public static function getSocialUrl( $leadId ) {
        $fields = self::getLeadFields( $leadId );
        
        if ( isset( $fields['facebookUrl'] )) return $fields['facebookUrl'];
        if ( isset( $fields['twitterUrl'] )) return $fields['twitterUrl'];
        if ( isset( $fields['googleUrl'] )) return $fields['googleUrl'];
        if ( isset( $fields['linkedinUrl'] )) return $fields['linkedinUrl'];
        
        return false;
    }
    
    /**
     * Returns the following array:
     * 
     * 'confirmed' => the number of leads (int),
     * 'not-fonfirmed' => the number of leads (int)
     * 
     * @since 1.0.7
     */
    public static function getCountByStatus() {
        global $wpdb;
        
        $rows = $wpdb->get_results( 
            "SELECT COUNT(*) as status_count, lead_email_confirmed FROM {$wpdb->prefix}opanda_leads GROUP BY lead_email_confirmed", 
            ARRAY_A 
        );
            
        $result = array();
        
        foreach( $rows as $row ) {
            $status = $row['lead_email_confirmed'] == 1 ? 'confirmed' : 'not-confirmed';
            $result[$status] = intval( $row['status_count'] );
        }
        
        if ( !isset( $result['confirmed'] )) $result['confirmed'] = 0;
        if ( !isset( $result['not-confirmed'] )) $result['not-confirmed'] = 0;
        
        return $result;
    }
    
    /**
     * Returns a lead by email or null.
     */
    public static function getByEmail( $email ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opanda_leads WHERE lead_email = %s", $email ));
    }

    protected static function extract( $name, $source, $default = null ) {
        $value = isset( $source[$name] ) ? trim( $source[$name] ) : $default;
        if ( empty( $value ) ) $value = $default;
        return $value;
    }
    
    public static function getCount( $cache = true ) {
        global $wpdb;
        
        $count = null;
        
        if ( $cache ) {
            $count = get_transient('opanda_subscribers_count');
            if ( $count === '0' || !empty( $count ) ) return intval( $count );
        }
        
        if( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}opanda_leads'") === $wpdb->prefix . 'opanda_leads' ) {
            $count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}opanda_leads" );
            set_transient('opanda_subscribers_count', $count, 60 * 5);
        }

        return $count;
    }
    
    public static function updateCount() {
        self::getCount( false );
    }
    
    public static function getIP( ) {
        $ip = '';
        
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if ( array_key_exists($key, $_SERVER) !== true) continue;
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip);
                if ( !self::validateIP($ip) ) continue;
                return $ip;
            }
        }
        
        return $ip;
    }
    
    public static function validateIP($ip) {
        if (strtolower($ip) === 'unknown') return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
    }
}