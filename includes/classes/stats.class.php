<?php

class StatsManager {
    
    /**
     * Unix timestamp that is used to define the start of work range.
     * @var int
     */
    public $rangeStart;
    
    /**
     * Unix timestamp that is used to define the end of work range.
     * @var int
     */
    public $rangeEnd; 
    
    public $postId = false;
        
    function __construct() {

        $fieldsToGet = array();
            
            $fieldsToGet = array(
                'total_count',
                'na_count',
                'facebook_like_count',
                'twitter_tweet_count',
                'google_plus_count',
                'facebook_share_count',
                'twitter_follow_count',
                'google_share_count',
                'linkedin_share_count',
                'timer_count',
                'cross_count'                        
            );
            
        


        $this->fieldsToGet = apply_filters('onp_sl_statistics_viewer_fields_to_get', $fieldsToGet);
        
    }
    

    public function getChartData( $dateRangeStart, $dateRangeEnd, $postId = null) {
        global $wpdb;
        
        $rangeStart = $dateRangeStart;
        $rangeEnd = $dateRangeEnd;    

        $rangeStartStr = gmdate("Y-m-d", $rangeStart);
        $rangeEndStr = gmdate("Y-m-d", $rangeEnd);

        $fieldsToGetWithSum = array();

        foreach( $this->fieldsToGet as $field ) {
            $fieldsToGetWithSum[] = "SUM(t.$field) AS $field";
        }

        $selectExtra = implode(',', $fieldsToGetWithSum);
        
        $extraWhere = '';
        if ($postId) $extraWhere .= 'AND t.PostID=' . $postId;


        $sql = "SELECT 
                    t.AggregateDate AS aggregateDate,
                    $selectExtra
                 FROM 
                    {$wpdb->prefix}so_tracking AS t
                 WHERE 
                    (AggregateDate BETWEEN '$rangeStartStr' AND '$rangeEndStr')
                    $extraWhere
                 GROUP BY 
                    t.AggregateDate";      
        
        $data = $wpdb->get_results($sql, ARRAY_A);
        $resultData = array();

        $currentDate = $rangeStart;
        while($currentDate <= $rangeEnd) {
   
            $phpdate = getdate($currentDate);
            
            $itemData = array(
                'day' => $phpdate['mday'],
                'mon' => $phpdate['mon'] - 1,
                'year' => $phpdate['year'],
                'timestamp' => $currentDate,  
            );
            
            foreach ($this->fieldsToGet as $field) $itemData[$field] = 0;
            $resultData[$currentDate] = $itemData;

            $currentDate = strtotime("+1 days", $currentDate);
        }
        
        foreach($data as $index => $row) {
            $timestamp = strtotime( $row['aggregateDate'] );
            $phpdate = getdate($timestamp);
            
            $data[$index]['day'] = $phpdate['mday'];
            $data[$index]['mon'] = $phpdate['mon'] - 1; 
            $data[$index]['year'] = $phpdate['year']; 
            $data[$index]['timestamp'] = $timestamp; 
            
            $resultData[$timestamp] = $data[$index];
        }
        

        
        return $resultData;
    }
    
    public function getViewTable( $options ) {
        global $wpdb;
        
        
        $per = isset( $options['per'] ) ? $options['per'] : 50;
        $page = isset( $options['page'] ) ? $options['page'] : 1;    
        $total = isset( $options['total'] ) ? $options['total'] : true;
        $order = isset( $options['order'] ) ? $options['order'] : 'total_count';
        
        $rangeStart = isset( $options['rangeStart'] ) ? $options['rangeStart'] : null;    
        $rangeEnd = isset( $options['rangeEnd'] ) ? $options['rangeEnd'] : null;    
        $postId = isset( $options['postId'] ) ? $options['postId'] : null;   
        
        $rangeStartStr = gmdate("Y-m-d", $rangeStart);
        $rangeEndStr = gmdate("Y-m-d", $rangeEnd);  

        $start = ( $page - 1 ) * $per;
        
        $extraWhere = '';
        if ($postId) $extraWhere .= 'AND PostID=' . $postId;
        
        // rows
        
        $sqlBase = "
            FROM 
                {$wpdb->prefix}so_tracking AS t
            INNER JOIN
                {$wpdb->prefix}posts AS p ON p.ID = t.PostID
            WHERE 
                (AggregateDate BETWEEN '$rangeStartStr' AND '$rangeEndStr') $extraWhere";
       
        $count = ( $total ) ? $wpdb->get_var('SELECT COUNT(Distinct t.PostID) ' . $sqlBase) : 0;

        $fieldsToGetWithSum = array();
        foreach( $this->fieldsToGet as $field ) {
            $fieldsToGetWithSum[] = "SUM(t.$field) AS $field";
        }
       
        $selectExtra = implode(',', $fieldsToGetWithSum);
        
        $sql = "
            SELECT 
                t.PostID AS ID,
                p.post_title AS title,
                $selectExtra 
                $sqlBase
            GROUP BY t.PostID 
            ORDER BY $order DESC
            LIMIT $start, $per"; 
       
        $data = $wpdb->get_results($sql, ARRAY_A);
        return array(
            'data' => $data,
            'count' => $count
        );
    }
    
    /**
     * Saves a track for a given post.
     * 
     * @since 3.7.2
     * @return void
     */
    public function saveTrack( $postId, $sender, $senderName = null ) {

        if ( !in_array($sender, array('na', 'button', 'timer', 'cross')) ) return;
                
        $fields = $this->fieldsToGet;
        $values = array();
        
        $senderNameToField = array(
            'facebook-like' => 'facebook_like_count',
            'twitter-tweet' => 'twitter_tweet_count',
            'google-plus' => 'google_plus_count',
            'facebook-share' => 'facebook_share_count',
            'twitter-follow' => 'twitter_follow_count',
            'google-share' => 'google_share_count',
            'linkedin-share' => 'linkedin_share_count',     
            'vk-like' => 'vk_like_count',
            'vk-share' => 'vk_share_count',
            'vk-subscribe' => 'vk_subscribe_count',
            'ok-klass' => 'ok_klass_count',
            'na' => 'na_count',
            'timer' => 'timer_count',
            'cross' => 'cross_count',
        );

        $senderNameToField = apply_filters('onp_sl_sender_name_to_field', $senderNameToField);
        $anchor = $sender === 'button' ? $senderName : $sender;
        
        if ( !isset( $senderNameToField[$anchor]) ) return;
        $senderField = $senderNameToField[$anchor];
                  
        if ( $sender === 'button' ) {
            
            $values['total_count'] = 1;
            $values[$senderField] = 1;
            
        } else {
            
            $values[$senderField] = 1;
        }
        
        $insertField = implode(',', $fields);

        // values to insert
        $insertPart = array();
        foreach($fields as $field) $insertPart[] = isset( $values[$field] ) ? '1' : '0';
        $insertPart = implode(',', $insertPart);

        // values to update
        $updatePart = array();
        foreach($fields as $field) {
            if ( !isset( $values[$field] ) ) continue;
            $updatePart[] = "$field = $field + 1";
        }
        $updatePart = implode(',', $updatePart);
        
        $hrsOffset = get_option('gmt_offset');
        if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
        $hrsOffset .= ' hours';
        $time = strtotime($hrsOffset, time());
        $date = date("Y-m-d", $time);
              
        global $wpdb;
        $sql = "INSERT INTO {$wpdb->prefix}so_tracking 
                    (AggregateDate,PostID,$insertField) 
                    VALUES ('$date',$postId, $insertPart)
                    ON DUPLICATE KEY UPDATE $updatePart";

        $wpdb->query($sql);
    }
}
 