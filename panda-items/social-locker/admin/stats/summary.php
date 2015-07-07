<?php


class OPanda_SocialLocker_Summary_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'sociallocker')
            ),
            'impress' => array(
                'title' => __('Impressions', 'sociallocker'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'sociallocker'),
                'hint' => __('The number of unlocks made by visitors.', 'sociallocker'), 
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'conversion' => array(
                'title' => __('Conversion', 'sociallocker'),
                'hint' => __('The ratio of the number of unlocks to impressions, in percentage.', 'sociallocker'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
}

class OPanda_SocialLocker_Summary_StatsChart extends OPanda_StatsChart {
    
    public function getSelectors() {
        return null;
    }
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks'),
                'color' => '#0074a2'
            )
        );
    }
}