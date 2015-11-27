<?php


class OPanda_SocialLocker_Detailed_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'sociallocker')
            ),
            'unlock' => array(
                'title' => __('Total', 'sociallocker'),
                'hint' => __('The total number of unlocks made by visitors.', 'sociallocker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'channels' => array(
                'title' => __('Unlocks Via', 'sociallocker'),
                'cssClass' => 'opanda-col-common',
                'columns' => array(
                    'unlock-via-facebook-like' => array(
                        'title' => __('FB Like'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-facebook-share' => array(
                        'title' => __('FB Share'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-twitter-tweet' => array(
                        'title' => __('Twitter Tweet'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-twitter-follow' => array(
                        'title' => __('Twitter Follow'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-google-plus' => array(
                        'title' => __('Google +1s'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-google-share' => array(
                        'title' => __('Google Share'),
                        'cssClass' => 'opanda-col-number'
                    ),
                    'unlock-via-youtube-subscribe' => array(
                        'title' => __('YouTube'),
                        'cssClass' => 'opanda-col-number'
                    ), 
                    'unlock-via-linkedin-share' => array(
                        'title' => __('LinkedIn Share'),
                        'cssClass' => 'opanda-col-number'
                    )
                )
            )
        ); 
    }
}

class OPanda_SocialLocker_Detailed_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {

        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'unlock-via-facebook-like' => array(
                'title' => __('FB Likes'),
                'color' => '#7089be'
            ),
            'unlock-via-facebook-share' => array(
                'title' => __('FB Shares'),
                'color' => '#566a93'
            ),
            'unlock-via-twitter-tweet' => array(
                'title' => __('Tweets'),
                'color' => '#3ab9e9'
            ),
            'unlock-via-twitter-follow' => array(
                'title' => __('Twitter Followers'),
                'color' => '#1c95c3'
            ),
            'unlock-via-google-plus' => array(
                'title' => __('Google +1s'),
                'color' => '#e26f61'
            ),
            'unlock-via-google-share' => array(
                'title' => __('Google Shares'),
                'color' => '#ba5145'
            ),
            'unlock-via-youtube-subscribe' => array(
                'title' => __('YouTube'),
                'color' => '#8f352b'
            ),            
            'unlock-via-linkedin-share' => array(
                'title' => __('LinkedIn Shares'),
                'color' => '#006080'
            )
        );
    }
}