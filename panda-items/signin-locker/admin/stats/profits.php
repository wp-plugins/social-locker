<?php

class OPanda_SignInLocker_Profits_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        $columns = array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'opanda')
            ),
            'unlock' => array(
                'title' => __('Unlocks', 'opanda'),
                'hint' => __('The number of unlocks made by visitors.', 'opanda'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            )
        );

        if ( BizPanda::hasPlugin('optinpanda') ) {
            
            $columns['emails'] = array(
                'title' => __('Emails', 'opanda'),
                'hint' => __('The number of new emails added to the database. If the email exists in the database, this email will not be counted.', 'opanda'),
                'cssClass' => 'opanda-col-number'
            );
        }
        
        $columns['account-registered'] = array(
            'title' => __('Accounts', 'opanda'),
            'hint' => __('The number of new accounts created for visitors.', 'opanda'),
            'cssClass' => 'opanda-col-number',
            'prefix' => '+'
        );
        
        if ( BizPanda::hasPlugin('sociallocker') ) {
            
            $columns['got-twitter-follower'] = array(
                'title' => __('Twitter Followers', 'opanda'),
                'hint' => __('The number of new followers attracted via the locker. If the user was a follower before, this user will not be counted.', 'opanda'),
                'cssClass' => 'opanda-col-number',
                'prefix' => '+'
            );
            
            $columns['tweet-posted'] = array(
                'title' => __('Tweets', 'opanda'),
                'hint' => __('The number of new tweets posted via the locker.', 'opanda'),
                'cssClass' => 'opanda-col-number',
                'prefix' => '+'
            );
            
            $columns['got-youtube-subscriber'] = array(
                'title' => __('Youtube Subscribers', 'opanda'),
                'hint' => __('The number of new subscribers attracted via the locker. If the user was a subscribers before, this user will not be counted.', 'opanda'),
                'cssClass' => 'opanda-col-number',
                'prefix' => '+'
            );
            
            $columns['got-linkedin-follower'] = array(
                'title' => __('LinkedIn Followers', 'opanda'),
                'hint' => __('The number of new followers attracted via the locker. If the user was a follower before, this user will not be counted.', 'opanda'),
                'cssClass' => 'opanda-col-number',
                'prefix' => '+'
            );  
        }
        
        return $columns;
    }
    
    public function columnEmails( $row ) {
        if ( !isset( $row['email-received'] ) ) $row['email-received'] = 0;
        if ( !isset( $row['email-confirmed'] ) ) $row['email-confirmed'] = 0;
        
        if ( $row['email-received'] > 0 ) echo '+';
        echo $row['email-received'];
        
        if ( BizPanda::getSubscriptionServiceName() !== 'database' ) {
            echo ' <em>(' . $row['email-confirmed'] . ' confirmed)';
        }
    }
}

class OPanda_SignInLocker_Profits_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {
        
        $fields = array();
        
        $fields['aggregate_date'] = array(
            'title' => __('Date')
        );

        if ( BizPanda::hasPlugin('optinpanda') ) {
            
            $fields['got-twitter-follower'] = array(
                'title' => __('Emails', 'opanda'),
                'color' => '#FFCC66'
            );
        }
        
        $fields['account-registered'] =  array(
            'title' => __('Accounts', 'opanda'),
            'color' => '#336699'
        );
        
        if ( BizPanda::hasPlugin('sociallocker') ) {
            
            $fields['got-twitter-follower'] = array(
                'title' => __('Twitter Tweets', 'opanda'),
                'color' => '#3bb4ea'
            );
            $fields['tweet-posted'] = array(
               'title' => __('Twitter Followers', 'opanda'),
               'color' => '#1e92c9'
            );
            $fields['got-youtube-subscriber'] = array(
               'title' => __('Youtube Subscribers', 'opanda'),
               'color' => '#ba5145'
            );
            $fields['got-linkedin-follower'] = array(
               'title' => __('LinkedIn Followers', 'opanda'),
               'color' => '#006080'
            );
        }
        
        return $fields;
    }
}