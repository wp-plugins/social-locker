<?php

// includes scripts and styles for this page
add_action('admin_enqueue_scripts', 'sociallocker_unlocking_statistics_scripts');
function sociallocker_unlocking_statistics_scripts($hook) {
    
    if (!empty( $hook ) && $hook == 'social-locker_page_sociallocker_statistics') {
        
        wp_enqueue_script( 
            'sociallocker-bootstrap', 
            SOCIALLOCKER_PLUGIN_URL . '/factory/core/assets/js/bootstrap.js', 
            array('jquery'), 
            false, true
        );
        
        wp_enqueue_script( 
            'sociallocker-statistics', 
            SOCIALLOCKER_PLUGIN_URL . '/assets/admin/js/statistics.020006.js', 
            array('jquery', 'jquery-ui-datepicker'), 
            false, true
        );
        
        wp_enqueue_script( 
            'bootstrap-datepicker', 
            SOCIALLOCKER_PLUGIN_URL . '/assets/admin/js/bootstrap-datepicker.js', 
            array('jquery'), 
            false, true
        );     
        
        wp_enqueue_style( 
            'sociallocker-bootstrap', 
            SOCIALLOCKER_PLUGIN_URL . '/factory/core/assets/css/bootstrap.css'
        );   
        
        wp_enqueue_style( 
            'sociallocker-statistics', 
            SOCIALLOCKER_PLUGIN_URL . '/assets/admin/css/statistics-style.020006.css'
        );    
        
        wp_enqueue_style( 
            'bootstrap-datepicker', 
            SOCIALLOCKER_PLUGIN_URL . '/assets/admin/css/datepicker.css'
        );        
    }
}

/**
 * Function that renders statistic pages
 */
function sociallocker_statistics() {
    include_once(SOCIALLOCKER_PLUGIN_ROOT . '/includes/classes/statistics-viewer.class.php');
    
    $postId = isset($_REQUEST['sPost']) ? intval($_REQUEST['sPost']) : false;
    $post = ($postId) ? get_post($postId) : false;
    
    $dateStart = isset($_REQUEST['sDateStart']) ? $_REQUEST['sDateStart'] : false;  
    $dateEnd = isset($_REQUEST['sDateEnd']) ? $_REQUEST['sDateEnd'] : false; 
    
    $hrsOffset = get_option('gmt_offset');
    if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
    $hrsOffset .= ' hours';

    // by default shows a 30 days' range
    if (empty($dateEnd) || ($dateRangeEnd = strtotime($dateEnd)) === false) {
        $phpdate = getdate( strtotime($hrsOffset, time()) );
        $dateRangeEnd = mktime(0, 0, 0, $phpdate['mon'], $phpdate['mday'], $phpdate['year']);
    }
    
    if (empty($dateStart) || ($dateRangeStart = strtotime($dateStart)) === false) {
        $dateRangeStart = strtotime("-1 month", $dateRangeEnd);
    }
    
    // creates a statistic viewer
    $statistic = new StatisticViewer($dateRangeEnd, $dateRangeStart);
    if ($postId) $statistic->setPost($postId);

    // gets data for the chart
    $chartData = $statistic->getChartData();
    
    $page = ( isset( $_GET['n'] ) ) ? intval( $_GET['n'] ) : 1;
    if ( $page <= 0 ) $page = 1;

    // gets table to view
    $viewTable = $statistic->getViewTable(array(
        'per' => 50,
        'total' => true,
        'page' => $page,
        'order' => 'total_count'
    ));
    $tableRows = $viewTable['data'];
    $totalRows = $viewTable['count'];
    $pagesCount = ceil( $totalRows / 50 );
    
    $dateStart = date('m/d/Y', $dateRangeStart);
    $dateEnd = date('m/d/Y', $dateRangeEnd); 
    
    $urlBase = 'edit.php?post_type=social-locker&page=sociallocker_statistics';
    $postBase = $urlBase . '&sDateStart=' . $dateStart . '&dateEnd=' . $dateEnd;
?>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(function(){
          statisticContext.drawChart();
      });

      window.chartData = [
        <?php foreach($chartData as $dataRow) { ?>
        {
            'date': new Date(<?php echo $dataRow['year'] ?>,<?php echo $dataRow['mon'] ?>,<?php echo $dataRow['day'] ?>),
            'facebook-like': <?php echo $dataRow['facebook_like_count'] ?>,
            'twitter-tweet': <?php echo $dataRow['twitter_tweet_count'] ?>,
            'facebook-share': <?php echo $dataRow['facebook_share_count'] ?>,
            'twitter-follow': <?php echo $dataRow['twitter_follow_count'] ?>,
            'google-plus': <?php echo $dataRow['google_plus_count'] ?>,
            'google-share': <?php echo $dataRow['google_share_count'] ?>,
            'linkedin-share': <?php echo $dataRow['linkedin_share_count'] ?>,
            'timer': <?php echo $dataRow['timer_count'] ?>,
            'cross': <?php echo $dataRow['cross_count'] ?>
        },
        <?php } ?>
     ];
    </script>
    
    <div class="wrap">
        <h2 style="margin-bottom: 10px;">Usage Statistics</h2>

        <div class="wpbootstrap">

        <p style="line-height: 150%; padding-bottom: 5px; margin-bottom: 0px;">
            The page provides usage statistics of social lockers on your pages. Here you can get info about how users interact with the lockers.<br />
            By default the chart shows the aggregate data for all posts. Click on the post title to view info for the one.</p>

        <form method="get"> 
        <input type="hidden" name="post_type" value="social-locker" />
        <input type="hidden" name="page" value="sociallocker_statistics" /> 
        <div id="chart-settings-bar">
            <div id="chart-type-select">
               <div class="btn-group" id="chart-type-group" data-toggle="buttons-radio">
                  <button type="button" class="btn btn-small active type-total" data-value="total"><i class="icon-search"></i> Total</button>
                  <button type="button" class="btn btn-small type-detailed" data-value="detailed"><i class="icon-zoom-in"></i> Detailed</button>
                  <button type="button" class="btn btn-small type-helpers" data-value="helpers"><i class="icon-time"></i> Helpers</button>     
                </div>
            </div>
            <div id="chart-date-select">
                    <input type="hidden" name="sPost" value="<?php echo $postId ?>" />
                    <span class="date-range-label">Date range:</span>
                    <input type="text" id="date-start" name="sDateStart" value="<?php echo $dateStart ?>" />
                    <input type="text" id="date-end" name="sDateEnd" value="<?php echo $dateEnd ?>" />
                    <input type="submit" id="date-range-apply" class="btn btn-small" value="Apply" />
            </div>
        </div>
        </form>

        <div class="chart-wrap">
            <div id="chart" style="width: 100%; height: 195px;"></div>
        </div>

        <div id="chart-selector">
            <div class="chart-item facebook-like">
                <span class="chart-color"></span>
                Facebook Likes
            </div>
            <?php ?>
            <div class="chart-item twitter-tweet">
                <span class="chart-color"></span>
                Twitter Tweets
            </div>  
            <?php ?>
            <div class="chart-item google-plus">
                <span class="chart-color"></span>
                Google Plusoners
            </div> 
            <?php ?>
        </div>

        <?php if ($postId) { ?>
            <div class="chart-notice">
            Data for the post: <strong><?php echo $post->post_title ?></strong> (<a href="<?php echo $postBase ?>">return back</a>)
            </div>
        <?php } else { ?>
            <p>Top-50 posts and pages that use the social lockers. Click a post title to view the detailed statistics:</p>
        <?php } ?>

        <table id="posts-table">
            <thead>
                <th class="col-index"></th>
                <th class="col-title">Post Title</th>
                <th class="col-number col-total">Total Count</th>
                <th class="col-number col-facebook-like">Facebook Likes</th>
                <?php ?>
                <th class="col-number col-twitter-tweet">Twitter Tweets</th>  
                <?php ?>
                <th class="col-number col-google-plus">Google Plusoners</th> 
                <?php ?>
                <th class="col-number col-timer">via Timer</th>   
                <th class="col-number col-cross">via Close Icon</th>          
            </thead>
            <tbody>
            <?php foreach($tableRows as $index => $dataRow) { ?>
            <tr>
                <td class="col-index"><?php echo $index + 1 ?>.</td>
                <td class="col-title"><a href="<?php echo $postBase ?>&sPost=<?php echo $dataRow['ID'] ?>"><?php echo $dataRow['title'] ?></a></td>  
                <td class="col-number col-total"><?php echo $dataRow['total_count'] ?></td>
                <td class="col-number col-facebook-like"><?php echo $dataRow['facebook_like_count'] ?></td>
                <?php ?>
                <td class="col-number col-twitter-tweet"><?php echo $dataRow['twitter_tweet_count'] ?></td>
                <?php ?>
                <td class="col-number col-google-plus"><?php echo $dataRow['google_plus_count'] ?></td>
                <?php ?>
                <td class="col-number col-timer"><?php echo $dataRow['timer_count'] ?></td>
                <td class="col-number col-cross"><?php echo $dataRow['cross_count'] ?></td>   
            </tr>
            <?php } ?>
            </tbody>
        </table>
            
            <div class="pagination pagination-right">
                <ul>
                <?php for( $i = 1; $i <= $pagesCount; $i++ ) { ?>
                    <li <?php if ( $i == $page ) { ?>class="active"<?php } ?>><a href="?sDateStart=<?php echo $dateStart ?>&sDateEnd=<?php echo $dateEnd ?>&post_type=social-locker&page=sociallocker_statistics&n=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php } ?>
                </ul>
            </div>

        </div>
    </div>
<?php
}