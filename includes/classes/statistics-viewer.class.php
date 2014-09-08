<?php

class StatisticViewer {
    
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
        
    function StatisticViewer($dateRangeEnd, $dateRangeStart) {
        
        $this->rangeStart = $dateRangeStart;
        $this->rangeEnd = $dateRangeEnd;    
        
        $this->rangeStartStr = gmdate("Y-m-d", $dateRangeStart);
        $this->rangeEndStr = gmdate("Y-m-d", $dateRangeEnd);
        
        // we use this var and the filter 'onp_sl_statistics_viewer_fields' 
        // in order to have an opportunity to add custom social buttons
        
        $fieldsToGet = array();
            
            $fieldsToGet = array(
                'total_count',
                'facebook_like_count',
                'twitter_tweet_count',
                'google_plus_count',
                'facebook_share_count',
                'twitter_follow_count',
                'google_share_count',
                'linkedin_share_count',
                'total_count',
                'timer_count',
                'cross_count'                        
            );
            
        

        
        $this->fieldsToGet = apply_filters('onp_sl_statistics_viewer_fields_to_get', $fieldsToGet);
        
    }
    
    public function setPost($postId) {
        $this->postId = $postId;
    }
    
    public function getChartData() {
        global $wpdb;

        $fieldsToGetWithSum = array();
        foreach( $this->fieldsToGet as $field ) {
            $fieldsToGetWithSum[] = "SUM(t.$field) AS $field";
        }
       
        $selectExtra = implode(',', $fieldsToGetWithSum);
        
        $extraWhere = '';
        if ($this->postId)
            $extraWhere .= 'AND t.PostID=' . $this->postId;


        $sql = "SELECT 
                    t.AggregateDate AS aggregateDate,
                    $selectExtra
                 FROM 
                    {$wpdb->prefix}so_tracking AS t
                 WHERE 
                    (AggregateDate BETWEEN '{$this->rangeStartStr}' AND '{$this->rangeEndStr}')
                    $extraWhere
                 GROUP BY 
                    t.AggregateDate";      
        
        $data = $wpdb->get_results($sql, ARRAY_A);
        $resultData = array();
        
        $currentDate = $this->rangeStart;
        while($currentDate <= $this->rangeEnd) {
   
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
        
        $start = ( $page - 1 ) * $per;
        
        $extraWhere = '';
        if ($this->postId) {
            $extraWhere .= 'AND PostID=' . $this->postId;
        }
        
        // rows
        
        $sqlBase = "
            FROM 
                {$wpdb->prefix}so_tracking AS t
            INNER JOIN
                {$wpdb->prefix}posts AS p ON p.ID = t.PostID
            WHERE 
                (AggregateDate BETWEEN '{$this->rangeStartStr}' AND '{$this->rangeEndStr}') $extraWhere";
       
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
}
 