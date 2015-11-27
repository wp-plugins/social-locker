<?php


class OPanda_SignInLocker_Channels_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'signinlocker')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'signinlocker'),
                'hint' => __('The number of unlocks made by visitors.', 'signinlocker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-twitter' => array(
                'title' => __('Via Twitter'),
                'cssClass' => 'opanda-col-number'
            ),  
            'unlock-via-google' => array(
                'title' => __('Via Google'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
}

class OPanda_SignInLocker_Channels_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form'),
                'color' => '#31ccab'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook'),
                'color' => '#7089be'
            ),
            'unlock-via-twitter' => array(
                'title' => __('Via Twitter'),
                'color' => '#3ab9e9'
            ),  
            'unlock-via-google' => array(
                'title' => __('Via Google'),
                'color' => '#e26f61'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn'),
                'color' => '#006080'
            ) 
        );
    }
}