<?php

class SocialLockerBasicOptionsMetaBox extends FactoryFormFR107Metabox
{
    public $title = 'Basic Options';
    public $scope = 'sociallocker';
    public $priority = 'core';
    
    public function form( FactoryFormFR107 $form ) {
        
            $form->add(array(  

                array(
                    'type'          => 'textbox',
                    'name'          => 'common_url',
                    'title'         => 'URL to share',
                    'hint'          => 'Enter common URL to like, to tweet and to plus or leave this 
                                        field empty to use URL of a current page.<br />Need a separate 
                                        URL for each button? Try a <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get">premium version</a> of the plugin.',
                    'placeholder'   => 'http://url-to-share.com'
                ),
            )); 
        
        

        
        $form->add(array(  
            
            array(
                'type'      => 'textbox',
                'name'      => 'header',
                'title'     => 'Locker header',
                'hint'      => 'Enter here a header of the locker. You can leave this field empty.',
                'default'   => 'This content is locked!'
            ),
            
            array(
                'type'      => 'editor',
                'name'      => 'message',
                'title'     => 'Locker message',
                'hint'      => 'Enter here a message users will see under the header.<br /><br />Shortcodes available: [post_title], [post_url].',
                'default'   => 'Please support us, use one of the buttons below to unlock the content.',
                'eventCallback' => 'sociallocker_editor_callback'
            ),
        ));
    }
}

$socialLocker->registerMetabox('SocialLockerBasicOptionsMetaBox');