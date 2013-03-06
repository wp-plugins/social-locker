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
                SUM(t.TotalCount) AS totalCount,
                SUM(t.LikeCount) AS likeCount,
                SUM(t.TweetCount) AS tweetCount,
                SUM(t.PlusCount) AS plusCount,
                SUM(t.TimerCount) AS byTimerCount,
                SUM(t.CrossCount) AS byCrossCount   
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

                'totalCount' => 0,
                'likeCount' => 0,    
                'tweetCount' => 0,
                'plusCount' => 0,   
                'byTimerCount' => 0,
                'byCrossCount' => 0                  
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
    
    public function getViewTable($count = 50, $order = 'TotalCount') {
        global $wpdb;
        
        $extraWhere = '';
        if ($this->postId) {
            $extraWhere .= 'AND PostID=' . $this->postId;
        }
        
        // rows
        
        $sql = "
            SELECT 
                t.PostID AS ID,
                p.post_title AS title,
                SUM(t.TotalCount) AS totalCount,
                SUM(t.LikeCount) AS likeCount,
                SUM(t.TweetCount) AS tweetCount,
                SUM(t.PlusCount) AS plusCount,
                SUM(t.TimerCount) AS byTimerCount,
                SUM(t.CrossCount) AS byCrossCount             
            FROM 
                {$wpdb->prefix}so_tracking AS t
            INNER JOIN
                {$wpdb->prefix}posts AS p ON p.ID = t.PostID
            WHERE 
                (AggregateDate BETWEEN '{$this->rangeStartStr}' AND '{$this->rangeEndStr}') $extraWhere
            GROUP BY 
                t.PostID
            ORDER BY $order DESC
            LIMIT $count";
    
        $data = $wpdb->get_results($sql, ARRAY_A);
        return $data;
    }
}