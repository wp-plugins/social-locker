<?php


class OPanda_SignInLocker_Bounces_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'signinlocker')
            ),
            'impress' => array(
                'title' => __('Impressions', 'signinlocker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ), 
            'users' => array(
                'title' => __('Visitors Who', 'signinlocker'),
                'cssClass' => 'opanda-col-common',
                'columns' => array(
                    /*
                    'ignored' => array(
                        'title' => __('Ignored Locker', 'signinlocker'),
                        'hint' => __('The number of visitors who viewed the locker but didn\'t try to interact with the one (no clicks on any buttons).', 'signinlocker'),
                        'cssClass' => 'opanda-col-number'
                    ),*/
                    'errors' => array(
                        'title' => __('Faced Errors', 'signinlocker'),
                        'hint' => __('Visitors who faced with any errors and were not able to unlock the content. This value normally should be equal 0. If not, please check settings of your locker or contact OnePress support.', 'signinlocker'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'social-fails' => array(
                        'title' => __('Declined Social Apps', 'signinlocker'),
                        'hint' => __('Visitors who refused to authorize of your social apps. If you think this value is too large, try to set less social actions to be performed on signing in through social networks.', 'signinlocker'),
                        'cssClass' => 'opanda-col-number'
                    )
                )
            )
        );
    }
    
    public function columnIgnored( $row ) {
        if ( !isset( $row['impress'] ) ) $row['impress'] = 0;
        if ( !isset( $row['interaction'] ) ) $row['interaction'] = 0;
        
        $diff = $row['impress'] - $row['interaction'];
        if ( $diff < 0 ) $diff = 0;
        echo $diff;
    }
    
    public function columnErrors( $row ) {
        if ( !isset( $row['error'] ) ) $row['error'] = 0;
        echo $row['error'];
    }
    
    public function columnNotConfirmed( $row ) {
        if ( !isset( $row['email-received'] ) ) $row['email-received'] = 0;
        if ( !isset( $row['email-confirmed'] ) ) $row['email-confirmed'] = 0;
 
        $diff = $row['email-received'] - $row['email-confirmed'];
        if ( $diff < 0 ) $diff = 0;
        
        echo $diff;
    }
    
    public function columnSocialFails( $row ) {

        if ( !isset( $row['social-app-declined'] ) ) $row['social-app-declined'] = 0;
        echo $row['social-app-declined'];
    }    
}

class OPanda_SignInLocker_Bounces_StatsChart extends OPanda_StatsChart {
    
    public $type = 'column';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            /*
            'ignored' => array(
                'title' => __('Who Ignored Locker', 'signinlocker'),
                'cssClass' => 'opanda-col-number',
                'color' => '#cccccc'
            ),*/
            'errors' => array(
                'title' => __('Who Faced Errors', 'signinlocker'),
                'cssClass' => 'opanda-col-number',
                'color' => '#F97D81'
            ),
            'social-fails' => array(
                'title' => __('Who Declined Social Apps', 'signinlocker'),
                'cssClass' => 'opanda-col-number',
                'color' => '#29264E'
            )
        );
    }
    
    public function fieldIgnored( $row ) {
        if ( !isset( $row['impress'] ) ) $row['impress'] = 0;
        if ( !isset( $row['interaction'] ) ) $row['interaction'] = 0;
        
        $diff = $row['impress'] - $row['interaction'];
        if ( $diff < 0 ) $diff = 0;
        return $diff;
    }
    
    public function fieldErrors( $row ) {
        if ( !isset( $row['error'] ) ) $row['error'] = 0;
        return $row['error'];
    }
    
    public function fieldNotConfirmed( $row ) {
        if ( !isset( $row['email-received'] ) ) $row['email-received'] = 0;
        if ( !isset( $row['email-confirmed'] ) ) $row['email-confirmed'] = 0;
 
        $diff = $row['email-received'] - $row['email-confirmed'];
        if ( $diff < 0 ) $diff = 0;
        
        return $diff;
    }
    
    public function fieldSocialFails( $row ) {

        if ( !isset( $row['social-app-declined'] ) ) $row['social-app-declined'] = 0;
        return $row['social-app-declined'];
    } 
}