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
    }
    
    public function setPost($postId) {
        $this->postId = $postId;
    }
    
    public function getChartData() {
        global $wpdb;
        
        $extraWhere = '';
        if ($this->postId) {
            $extraWhere .= 'AND t.PostID=' . $this->postId;
        }
            
        $sql = "
            SELECT 
                t.AggregateDate AS aggregateDate,
                SUM(t.total_count) AS total_count,
                SUM(t.facebook_like_count) AS facebook_like_count,
                SUM(t.twitter_tweet_count) AS twitter_tweet_count,
                SUM(t.google_plus_count) AS google_plus_count,
                SUM(t.facebook_share_count) AS facebook_share_count,
                SUM(t.twitter_follow_count) AS twitter_follow_count,
                SUM(t.google_share_count) AS google_share_count,   
                SUM(t.linkedin_share_count) AS linkedin_share_count,
                SUM(t.timer_count) AS timer_count,
                SUM(t.cross_count) AS cross_count   
            FROM 
                {$wpdb->prefix}so_tracking AS t
            WHERE 
                (AggregateDate BETWEEN '{$this->rangeStartStr}' AND '{$this->rangeEndStr}') $extraWhere
            GROUP BY 
                t.AggregateDate";
                
        

        
        $data = $wpdb->get_results($sql, ARRAY_A);
        $resultData = array();
        
        $currentDate = $this->rangeStart;
        while($currentDate <= $this->rangeEnd) {
   
            $phpdate = getdate($currentDate);
            $resultData[$currentDate] = array(
                'day' => $phpdate['mday'],
                'mon' => $phpdate['mon'] - 1,
                'year' => $phpdate['year'],
                'timestamp' => $currentDate,  

                'total_count' => 0,
                'facebook_like_count' => 0,    
                'twitter_tweet_count' => 0,
                'google_plus_count' => 0,   
                'facebook_share_count' => 0,
                'twitter_follow_count' => 0,
                'google_share_count' => 0,   
                'linkedin_share_count' => 0,
                'timer_count' => 0,   
                'cross_count' => 0 
            );
            
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
        
            $sql = "
                SELECT 
                    t.PostID AS ID,
                    p.post_title AS title,
                    SUM(t.total_count) AS total_count,
                    SUM(t.facebook_like_count) AS facebook_like_count,
                    SUM(t.twitter_tweet_count) AS twitter_tweet_count,
                    SUM(t.google_plus_count) AS google_plus_count,
                    SUM(t.facebook_share_count) AS facebook_share_count,
                    SUM(t.twitter_follow_count) AS twitter_follow_count,
                    SUM(t.google_share_count) AS google_share_count,   
                    SUM(t.linkedin_share_count) AS linkedin_share_count,
                    SUM(t.timer_count) AS timer_count,
                    SUM(t.cross_count) AS cross_count           
                " . $sqlBase . "
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