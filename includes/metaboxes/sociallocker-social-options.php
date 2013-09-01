<?php
#build: premium, offline

class SocialLockerSocialOptionsMetaBox extends FactoryFormPR108Metabox
{
    public $title = '3. Social Options';
    public $scope = 'sociallocker';
    public $priority = 'core';
        
    public function form( FactoryFormPR108 $form ) {

        $form->add(array(  
            
            array(
                'type'      => 'hidden',
                'name'      => 'buttons_order',
                'default'   => 'twitter-tweet,facebook-like,google-plus'
            ),
            
            // Vertical tab
            
            array(
                'type'      => 'tab',
                'tabType'   => 'vertical',
                'class'     => 'social-settings-tab',
                'hint'      => '<strong>Hint</strong>: Drag and drop the tabs to change the order of the buttons.'
            ),
            
                // - Facebook Like Tab
            
                array(
                    'type'      => 'tab-item',
                    'title'     => '',
                    'name'      => 'facebook-like'
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the Like Button.',
                    'name'  => 'facebook-like_available',
                    'default' => true
                ),        
                array(
                    'type'  => 'url',
                    'title' => 'URL to like',
                    'hint'  => 'Set any URL to like (a fanpage or website). Leave this field empty 
                                to use a current page.',
                    'name'  => 'facebook_like_url'
                ),
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'facebook_like_title',
                    'default' => 'like'
                ),      
            
                // - Twitter Tweet Tab
            
                array(
                    'type'      => 'tab-item',
                    'title'     => '',
                    'name'      => 'twitter-tweet'
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the Tweet Button.',
                    'name'  => 'twitter-tweet_available',
                    'default' => true
                ),        
                array(
                    'type'  => 'url',
                    'title' => 'URL to tweet',
                    'hint'  => 'Set any URL to tweet. Leave this field empty 
                                to use a current page.',
                    'name'  => 'twitter_tweet_url'
                ),
                array(
                    'type'  => 'textarea',
                    'title' => 'Tweet',
                    'hint'  => 'Leave this field empty to use default tweet (page title + URL). Also you can use shortcode: [post_title].',
                    'name'  => 'twitter_tweet_text'
                ), 
                array(
                    'type'  => 'url',
                    'title' => 'Counter URL',
                    'hint'  => 'Optional. If you use a shorter tweet URL, paste here a full URL for the counter.',
                    'name'  => 'twitter_tweet_counturl'
                ),
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'twitter_tweet_title',
                    'default' => 'tweet'
                ),      
            
                // - Google Plus Tab
            
                array(
                    'type'      => 'tab-item',
                    'name'      => 'google-plus',
                    'title'     => ''
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the Google +1 Button.',
                    'name'  => 'google-plus_available',
                    'default' => true
                ),      
                array(
                    'type'  => 'url',
                    'title' => 'URL to +1',
                    'hint'  => 'Set any URL to +1 (for example, main page of your site). 
                                Leave this field empty to use a current page.',
                    'name'  => 'google_plus_url'
                ),
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'google_plus_title',
                    'default' => '+1 us'
                ),    
            
                // - Facebook Share Tab
            
                array(
                    'type'      => 'tab-item',
                    'title'     => '',
                    'name'      => 'facebook-share'
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => '
                                Set Off to hide the Share Button.
                                <div>
                                Please make sure that you <a style="font-weight: bold;" href="edit.php?post_type=social-locker&page=sociallocker_settings">set a facebook app id</a> for your domain, otherwise the button will not work.
                                </div>
                                ',
                    'name'  => 'facebook-share_available',
                    'default' => false
                ),        
                array(
                    'type'  => 'url',
                    'title' => 'URL to share',
                    'hint'  => 'Set any URL to share. Leave this field empty to use a current page.',
                    'name'  => 'facebook_share_url'
                ),
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'facebook_share_title',
                    'default' => 'share'
                ),
            
                array(
                    'type'      => 'collapsed',
                    'title'     => 'Show more options',
                    'count'     => 5
                ),
                    array(
                        'type'  => 'group',
                        'title' => 'Data To Share',
                        'hint'  => 'By default data extracted from the URL will be used to publish a message on a user wall. But you can specify other data you want users to share.'
                    ),
                    array(
                        'type'  => 'textbox',
                        'title' => 'Name',
                        'hint'  => 'Optional. The name of the link attachment.',
                        'name'  => 'facebook_share_message_name'
                    ),
                    array(
                        'type'  => 'textbox',
                        'title' => 'Caption',
                        'hint'  => 'Optional. The caption of the link (appears beneath the link name). If not specified, this field is automatically populated with the URL of the link.',
                        'name'  => 'facebook_share_message_caption'
                    ),
                    array(
                        'type'  => 'textbox',
                        'title' => 'Description',
                        'hint'  => 'Optional. The description of the link (appears beneath the link caption). If not specified, this field is automatically populated by information scraped from the link, typically the title of the page.',
                        'name'  => 'facebook_share_message_description'
                    ),  
                    array(
                        'type'  => 'textbox',
                        'title' => 'Image',
                        'hint'  => 'Optional. The URL of a picture attached to this post. The picture must be at least 50px by 50px (though minimum 200px by 200px is preferred) and have a maximum aspect ratio of 3:1.',
                        'name'  => 'facebook_share_message_image'
                    ),  
                    array(
                        'type'  => 'group',
                        'title' => 'Others'
                    ),
                    array(
                        'type'  => 'url',
                        'title' => 'Counter URL',
                        'hint'  => 'Optional. Paste here an URL that will be used to get the number of shares.',
                        'name'  => 'facebook_share_counter_url'
                    ),
            
                // - Twitter Follow Tab
            
                array(
                    'type'      => 'tab-item',
                    'title'     => '',
                    'name'      => 'twitter-follow'
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the Follow Button.',
                    'name'  => 'twitter-follow_available',
                    'default' => false
                ),        
                array(
                    'type'  => 'url',
                    'title' => 'User to follow',
                    'hint'  => 'Set a URL of a Twitter user to follow.',
                    'name'  => 'twitter_follow_url'
                ),  
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'twitter_follow_title',
                    'default' => 'follow us'
                ),  
            
                // - Google Share Tab
            
                array(
                    'type'      => 'tab-item',
                    'name'      => 'google-share',
                    'title'     => ''
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the Google Share Button.',
                    'name'  => 'google-share_available',
                    'default' => false
                ),      
                array(
                    'type'  => 'url',
                    'title' => 'URL to +1',
                    'hint'  => 'Set any URL to Share (for example, main page of your site). 
                                Leave this field empty to use a current page.',
                    'name'  => 'google_share_url'
                ),  
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'google_share_title',
                    'default' => 'share'
                ),  
            
                // - LinkedIn Share Tab
            
                array(
                    'type'      => 'tab-item',
                    'name'      => 'linkedin-share',
                    'title'     => ''
                ),
                array(
                    'type'  => 'mv-checkbox',
                    'title' => 'Available',
                    'hint'  => 'Set Off to hide the LingedIn Share Button.',
                    'name'  => 'linkedin-share_available',
                    'default' => false
                ),      
                array(
                    'type'  => 'url',
                    'title' => 'URL to +1',
                    'hint'  => 'Set any URL to Share (for example, main page of your site). 
                                Leave this field empty to use a current page.',
                    'name'  => 'linkedin_share_url'
                ),  
                array(
                    'type'  => 'textbox',
                    'title' => 'Button Title',
                    'hint'  => 'Optional. A visible title of the buttons that is used in some themes (by default only in the Secrets theme).',
                    'name'  => 'linkedin_share_title',
                    'default' => 'share'
                ),  
        ));  
    }
    
}

$socialLocker->registerMetabox('SocialLockerSocialOptionsMetaBox');