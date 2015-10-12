<?php

class OPanda_Stats {
    
    /**
     * Returns data to show charts.
     * 
     * Charts show the stats for a specified item and for all or a single posts.
     * @return mixed[]
     */
    public static function getChartData( $options ) {
        global $wpdb;
        
        $postId = isset( $options['postId'] ) ? $options['postId'] : null;   
        $itemId = isset( $options['itemId'] ) ? $options['itemId'] : null;
        
        $rangeStart = isset( $options['rangeStart'] ) ? $options['rangeStart'] : null;
        $rangeEnd = isset( $options['rangeEnd'] ) ? $options['rangeEnd'] : null;   

        $rangeStartStr = gmdate("Y-m-d", $rangeStart);
        $rangeEndStr = gmdate("Y-m-d", $rangeEnd);
        
        // building and executeing a sql query
        
        $extraWhere = '';
        if ($postId) $extraWhere .= ' AND t.post_id=' . $postId;
        if ($itemId) $extraWhere .= ' AND t.item_id=' . $itemId;

        $sql = "SELECT 
                    t.aggregate_date AS aggregate_date,
                    t.metric_name AS metric_name,  
                    SUM(t.metric_value) AS metric_value
                 FROM 
                    {$wpdb->prefix}opanda_stats_v2 AS t
                 WHERE 
                    (aggregate_date BETWEEN '$rangeStartStr' AND '$rangeEndStr')
                    $extraWhere
                 GROUP BY
                    t.aggregate_date, t.metric_name";  
                    
        $rawData = $wpdb->get_results($sql, ARRAY_A);

        // extracting metric names stored in the database &
        // grouping the same metrics data per day
        
        $metricNames = array();
        $data = array();
        
        foreach( $rawData as $row ) {
            $metricName = $row['metric_name'];
            $metricValue = $row['metric_value'];
            
            if ( !in_array( $metricName, $metricNames ) ) $metricNames[] = $metricName;    
            
            $timestamp = strtotime( $row['aggregate_date'] );
            $data[$timestamp][$metricName] = $metricValue;
        } 
             
        // normalizing data by adding zero value for skipped dates

        $resultData = array();
        
        $currentDate = $rangeStart;
        while($currentDate <= $rangeEnd) {
            
            $phpdate = getdate($currentDate);
            $resultData[$currentDate] = array();

            $resultData[$currentDate]['day'] = $phpdate['mday'];
            $resultData[$currentDate]['mon'] = $phpdate['mon'] - 1;
            $resultData[$currentDate]['year'] = $phpdate['year'];
            $resultData[$currentDate]['timestamp'] = $currentDate;
            
            foreach ($metricNames as $metricName) {
                
                if ( !isset( $data[$currentDate][$metricName] ) )
                    $resultData[$currentDate][$metricName] = 0;
                else 
                    $resultData[$currentDate][$metricName] = $data[$currentDate][$metricName];
            }

            $currentDate = strtotime("+1 days", $currentDate);
        }

        
        return $resultData;
    }
    
    /**
     * Returns data to show in the data table.
     */
    public static function getViewTable( $options ) {
        global $wpdb;
        
 
        $per = isset( $options['per'] ) ? $options['per'] : 50;
        $page = isset( $options['page'] ) ? $options['page'] : 1;    
        $total = isset( $options['total'] ) ? $options['total'] : true;
        $order = isset( $options['order'] ) ? $options['order'] : 'unlock';
 
        $rangeStart = isset( $options['rangeStart'] ) ? $options['rangeStart'] : null;    
        $rangeEnd = isset( $options['rangeEnd'] ) ? $options['rangeEnd'] : null;    
        $postId = isset( $options['postId'] ) ? $options['postId'] : null;   
        $itemId = isset( $options['itemId'] ) ? $options['itemId'] : null;
        
        $rangeStartStr = gmdate("Y-m-d", $rangeStart);
        $rangeEndStr = gmdate("Y-m-d", $rangeEnd);  

        $start = ( $page - 1 ) * $per;
        
        $extraWhere = '';
        if ($postId) $extraWhere .= 'AND t.post_id=' . $postId;
        if ($itemId) $extraWhere .= ' AND t.item_id=' . $itemId;
        
        $count = ( $total ) ? $wpdb->get_var(
            "SELECT COUNT(Distinct t.post_id) FROM {$wpdb->prefix}opanda_stats_v2 AS t
            WHERE (aggregate_date BETWEEN '$rangeStartStr' AND '$rangeEndStr') $extraWhere") : 0;

        $sql = "
            SELECT 
                t.metric_name AS metric_name,  
                SUM(t.metric_value) AS metric_value,
                t.post_id AS post_id,
                p.post_title AS post_title
            FROM 
                {$wpdb->prefix}opanda_stats_v2 AS t
            LEFT JOIN
                {$wpdb->prefix}posts AS p ON p.ID = t.post_id
            WHERE 
                (aggregate_date BETWEEN '$rangeStartStr' AND '$rangeEndStr') $extraWhere
            GROUP BY t.post_id, t.metric_name"; 
                
        $rawData = $wpdb->get_results($sql, ARRAY_A);

        // extracting metric names stored in the database &
        // grouping the same metrics data per day
        
        $metricNames = array();
        $resultData = array();
        
        foreach( $rawData as $row ) {
            $metricName = $row['metric_name'];
            $metricValue = $row['metric_value'];
            $postId = $row['post_id'];

            if ( !in_array( $metricName, $metricNames ) ) $metricNames[] = $metricName;    
            
            if ( !isset( $resultData[$postId] ) ) {
                $title = $row['post_title'];
                if ( empty( $title ) ) $title = __('(the post not found)', 'bizpanda');
                
                $resultData[$postId]['id'] = $postId;
                $resultData[$postId]['title'] = $title;
            }
            
            $resultData[$postId][$metricName] = $metricValue;
        } 
        
        $returnData = array();
        
        foreach( $resultData as $row ) {
            $returnData[] = $row;
        }
        
        return array(
            'data' => $returnData,
            'count' => $count
        );
    }

    private static $_currentMySqlDate = null;
    
    /**
     * A helper method to return a current date in the MySQL format.
     */
    public static function getCurrentMySqlDate() {
        if ( self::$_currentMySqlDate ) return self::$_currentMySqlDate;
        
        $hrsOffset = get_option('gmt_offset');
        if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
        $hrsOffset .= ' hours';
        $time = strtotime($hrsOffset, time());
        
        self::$_currentMySqlDate = date("Y-m-d", $time);
        return self::$_currentMySqlDate;
    }
    
    /**
     * Counts custom metric.
     */
    public static function countMetrict( $itemId, $postId, $metricName ) {
        global $wpdb;
        
        if ( empty( $itemId ) || empty( $postId ) ) return;

        $sql = $wpdb->prepare( 
            "INSERT INTO {$wpdb->prefix}opanda_stats_v2
            (aggregate_date,item_id,post_id,metric_name,metric_value) 
            VALUES (%s,%d,%d,%s,1)
            ON DUPLICATE KEY UPDATE metric_value = metric_value + 1", 
            self::getCurrentMySqlDate(), $itemId, $postId, $metricName
        );

        $wpdb->query($sql);
    }
    
    /**
     * Counts an event (unlock, impress, etc.)
     */
    public static function processEvent( $itemId, $postId, $eventName, $eventType ) {

        if ( 'unlock' == $eventType ) {
            self::countMetrict( $itemId, $postId, 'unlock' );
            self::countMetrict( $itemId, $postId, 'unlock-via-' . $eventName );
        } elseif ( 'skip' == $eventType ) {
            self::countMetrict( $itemId, $postId, 'skip' );
            self::countMetrict( $itemId, $postId, 'skip-via-' . $eventName );    
        } else {
            self::countMetrict( $itemId, $postId, $eventName );
        }
        
        // updates the summary stats for the item
        
        if ( 'unlock' === $eventType ) {
            
            $unlocks = intval( get_post_meta($itemId, 'opanda_unlocks', true) );
            $unlocks++;
            update_post_meta($itemId, 'opanda_unlocks', $unlocks);
            
        } elseif ( 'impress' === $eventName ) {
            
            $imperessions = intval( get_post_meta($itemId, 'opanda_imperessions', true) );
            $imperessions++;
            update_post_meta($itemId, 'opanda_imperessions', $imperessions);
        }
    }
}
 