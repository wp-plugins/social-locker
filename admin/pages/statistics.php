<?php
/**
 * The file contains a page that shows statistics
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OnpSL_StatisticsPage extends FactoryPages300_AdminPage  {
 
    public $menuTitle = 'Usage Statistics';
    public $menuPostType = 'social-locker';
    
    public $id = "statistics";
        
    public function assets() {
        $this->scripts->request('jquery');
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/datepicker.js');  
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/datepicker.css');      
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/statistics.030000.js');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/statistics.030000.css');   
    }
    
    /**
     * Shows an index page where a user can set settings.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        
    include_once(ONP_SL_PLUGIN_DIR . '/includes/classes/statistics-viewer.class.php');
    
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
    
    $urlBase = 'edit.php?post_type=social-locker&page=statistics-sociallocker-next';
    $postBase = $urlBase . '&sDateStart=' . $dateStart . '&dateEnd=' . $dateEnd;
    ?>
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

          // Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(function(){
              window.onpsl.statistics.drawChart();
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

            <div class="factory-bootstrap-300 factory-fontawesome-300">

            <p style="line-height: 150%; padding-bottom: 5px; margin-bottom: 0px;">
                This page provides usage statistics of social lockers on your pages. Here you can get info about how users interact with your lockers.<br />
                By default the chart shows the aggregate data for all posts. Click on the post title to view info for the one.</p>

            <div id="onp-sl-chart-area">
                
                <form method="get"> 
                <input type="hidden" name="post_type" value="social-locker" />
                <input type="hidden" name="page" value="statistics-sociallocker-next" /> 
                <div id="onp-sl-settings-bar">
                    
                    <div id="onp-sl-type-select">
                       <div class="btn-group" id="chart-type-group" data-toggle="buttons-radio">
                          <button type="button" class="btn btn-default active type-total" data-value="total"><i class="fa fa-search"></i> Total</button>
                          <button type="button" class="btn btn-default type-detailed" data-value="detailed"><i class="fa fa-search-plus"></i> Detailed</button>
                          <button type="button" class="btn btn-default type-helpers" data-value="helpers"><i class="fa fa-tint"></i> Leakages</button>     
                        </div>
                    </div>
                    <div id="onp-sl-date-select">
                            <input type="hidden" name="sPost" value="<?php echo $postId ?>" />
                            <span class="onp-sl-range-label">Date range:</span>
                            <input type="text" id="onp-sl-date-start" name="sDateStart" class="form-control" value="<?php echo $dateStart ?>" />
                            <input type="text" id="onp-sl-date-end" name="sDateEnd" class="form-control" value="<?php echo $dateEnd ?>" />
                            <a id="onp-sl-apply-dates" class="btn btn-default">
                                <?php _e('Apply', 'sociallocker') ?>
                            </a>
                    </div>
                </div>
                </form>

                <div class="chart-wrap">
                    <div id="chart" style="width: 100%; height: 195px;"></div>
                </div>
                
            </div>
            <div id="onp-sl-chart-selector">
                <div class="onp-sl-chart-item facebook-like">
                    <span class="chart-color"></span>
                    Facebook Likes
                </div>
                <?php ?>
                <div class="onp-sl-chart-item twitter-tweet">
                    <span class="chart-color"></span>
                    Twitter Tweets
                </div>  
                <?php ?>
                <div class="onp-sl-chart-item google-plus">
                    <span class="chart-color"></span>
                    Google Plusoners
                </div> 
                <?php ?>
            </div>

            <?php if ($postId) { ?>
                <div class="alert alert-warning">
                Data for the post: <strong><?php echo $post->post_title ?></strong> (<a href="<?php echo $postBase ?>">return back</a>)
                </div>
            <?php } else { ?>
                <p>Top posts and pages where you placed the lockers showing the best social indicators. Click a post title to get more details.</p>
            <?php } ?>

            <div id="onp-sl-posts-wrap">
            <table id="onp-sl-posts">
                <thead>
                    <th class="col-index"></th>
                    <th class="col-title">Post Title</th>
                    <th class="col-number col-total">Total</th>
                    <th class="col-number col-facebook-like">Facebook Likes</th>
                    <?php ?>
                    <th class="col-number col-twitter-tweet">Twitter Tweets</th>  
                    <?php ?>
                    <th class="col-number col-google-plus">Google Plusoners</th> 
                    <?php ?>
                    <th class="col-number col-timer">Timer</th>   
                    <th class="col-number col-cross">Close Icon</th>          
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

            </div>
            
            <div id="onp-sl-pagination-wrap">
                <div class="pagination">
                <ul class="pagination pagination-sm">
                <?php for( $i = 1; $i <= $pagesCount; $i++ ) { ?>
                    <li <?php if ( $i == $page ) { ?>class="active"<?php } ?>><a href="?sDateStart=<?php echo $dateStart ?>&sDateEnd=<?php echo $dateEnd ?>&post_type=social-locker&page=statistics-sociallocker-next&n=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php } ?>
                </ul>
                </div>
            </div>
                
            </div>
        </div>
    <?php
    }
}

FactoryPages300::register($sociallocker, 'OnpSL_StatisticsPage');