<?php

class SocialLockerBasicOptionsMetaBox extends FactoryFormPR108Metabox
{
    public $title = 'Basic Options';
    public $scope = 'sociallocker';
    public $priority = 'core';
    
    public function form( FactoryFormPR108 $form ) {
        
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

            $form->add(array(  
                array(
                    'type'      => 'list',
                    'name'      => 'style',
                    'data'      => array(
                        array('starter', 'Starter (default)'),
                        array('secrets', 'Secrets'),
                        array('dandyish', 'Dandyish'),
                        array('glass', 'Glass'),
                    ), 
                    'title'     => 'Theme',
                    'hint'      => 'See the documentation to learn how to customize the theme.',
                    'default'   => 'ui-locker-facebook-popup'
                )
            )); 
            
        

    }
}

$socialLocker->registerMetabox('SocialLockerBasicOptionsMetaBox');