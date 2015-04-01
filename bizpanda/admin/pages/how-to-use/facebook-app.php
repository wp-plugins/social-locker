<div class="onp-help-section">
    <h1><?php _e('Creating Facebook App', 'optinpanda'); ?></h1>
    
    <p>
        <?php _e('A Facebook App is required for the following buttons:', 'optinpanda'); ?>
        <ul>
            <?php if ( BizPanda::hasPlugin('sociallocker') ) { ?>
            <li><?php _e('Facebook Share of the Social Locker.', 'optinpanda') ?></li>
            <?php } ?>
            <li><?php _e('Facebook Sign-In of the Sign-In Locker.', 'optinpanda') ?></li>
            <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
            <li><?php _e('Facebook Subscribe of the Email Locker.', 'optinpanda') ?></li>      
            <?php } ?>
        </ul>
    </p>
    <p><?php _e('If you want to use these buttons, you need to register a Facebook App for your website. Otherwise you can use the default Facebook App Id (117100935120196).', 'optinpanda') ?></p>
    <p><?php _e('In other words, <strong>you don\'t need to create an own app</strong> if you\'re not going to use these Facebook buttons.') ?></p>

</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Open the website <a href="%s" target="_blank">developers.facebook.com</a> and click "Create a New App" (or Add a New App):', 'optinpanda'), 'https://developers.facebook.com/' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/1.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. When the popup appears, click the link "advanced setup":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/2.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('3. Type your app name (it will be visible for users), select your app category, for example, "utilities" and click "Create App":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/3.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('4. Enter the words you see on the image and click "Submit":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/4.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Move to the section "Settings":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/5.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('6. Twice set your site domain name: without “www” and with “www”, enter your email address, click "+ Add Platform":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/6.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('7. Click "Website":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/7.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('8. Specify an URL of your website and save the changes:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/8.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('9. Move to the section "Status & Review":', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/9.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('10. Make your app available to the general public:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/10.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('11. Move back to the section "Dashboard" and copy your app id:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/facebook-app/11.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('12. Paste your Facebook App Id on the page Global Settings > <a href="%s">Social Options</a>.', 'optinpanda' ), opanda_get_settings_url('social') ) ?></p>
</div>