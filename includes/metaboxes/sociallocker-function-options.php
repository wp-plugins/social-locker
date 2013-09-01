<?php
#build: premium, offline

class SocialLockerFunctionOptionsMetaBox extends FactoryFormPR108Metabox
{
    public $title = '4. Advanced Functions';
    public $scope = 'sociallocker';
    public $priority = 'core';
    public $context = 'side'; 
      
    public function form( FactoryFormPR108 $form ) {

        $form->add(array(  
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'close',
                'title'     => 'Close Icon',
                'hint'      => 'Shows the Close Icon at the corner.',
                'icon'      => '~/admin/img/close-icon.png',
                'default'   => false
            ),
            
            array(
                'type'      => 'textbox',
                'name'      => 'timer',
                'size'      => 4,
                'title'     => 'Timer Inerval',
                'hint'      => 'Sets a timeout interval of the timer.',
                'icon'      => '~/admin/img/timer-icon.png',
                'default'   => false
            ),
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'ajax',
                'title'     => 'AJAX',
                'hint'      => 'Allows to hide the locked content in the source code.',
                'icon'      => '~/admin/img/ajax-icon.png',
                'default'   => false
            ),
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'mobile',
                'title'     => 'Mobile',
                'hint'      => 'Defines whether the locker must appear for mobile devices.',
                'icon'      => '~/admin/img/mobile-icon.png',
                'default'   => true
            ),
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'highlight',
                'title'     => 'Highlight',
                'hint'      => 'Defines whether the locker uses the Highlight effect.',
                'icon'      => '~/admin/img/highlight-icon.png',
                'default'   => true
            ),
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'hide_for_member',
                'title'     => 'Hide for members',
                'hint'      => 'Set On to hide the locker for registered members.',
                'icon'      => '~/admin/img/member-icon.png',
                'default'   => false
            ),
            
            array(
                'type'      => 'mv-checkbox',
                'name'      => 'rss',
                'title'     => 'Content for RSS',
                'hint'      => 'Set On to make locked content visible in the RSS feed.',
                'icon'      => '~/admin/img/rss-icon.png',
                'default'   => false
            )
        ));  
    }
}

$socialLocker->registerMetabox('SocialLockerFunctionOptionsMetaBox');