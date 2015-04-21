<div class="onp-help-section">
    <h1><?php _e('Getting Google Client ID', 'optinpanda'); ?></h1>

    <p>
        <?php _e('A Google Client ID is required for the following buttons:', 'optinpanda'); ?>
        <ul>
            <li><?php _e('Google Sign-In of the Sign-In Locker.', 'optinpanda') ?></li>
            <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
            <li><?php _e('Google Subscribe of the Email Locker.', 'optinpanda') ?></li>  
            <?php } ?>
        </ul>
    </p>
    
    <p><?php _e('If you want to use these buttons, you need to get Google Client ID App for your website.', 'optinpanda') ?>
    <?php _e('<strong>You don\'t need to get a Client ID</strong> if you\'re not going to use these Google buttons.') ?></p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Go to the <a href="%s" target="_blank">Google Developers Console</a>.', 'optinpanda'), 'https://console.developers.google.com/project' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. Click the button "Create Project", enter your website name as a new project name.', 'optinpanda') ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/1.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('3. Wait until your new project is created. After that you will be automatically redirected to your project dashboard.', 'optinpanda' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('4. Select "APIs & auth" > "Consent screen" in the sidebar on the left.', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/2.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Fill up and save the form:', 'optinpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'optinpanda') ?></th>
                <th><?php _e('How To Fill', 'optinpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Email Address', 'optinpanda') ?></td>
                <td><?php _e('Select your email address from the list.', 'optinpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Product Name', 'optinpanda') ?></td>
                <td><?php _e('The best name is your website name.', 'optinpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Homepage URL', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website URL:', 'optinpanda') ?></p>
                    <p><i><?php echo site_url() ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Product Logo', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('If you have any suitable logo, paste it here. Recommended to set for better conversion.', 'optinpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Privacy Policy URL', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Recommended to set it in order to make the work of the plugin transparent for the users. While the plugin activation, we created one for you:', 'optinpanda') ?></p>
                    <p><i><?php echo opanda_privacy_policy_url() ?></i></p>
                    <p><?php _e('You can change it or create your own one.', 'optinpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Terms of Service URL', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Recommended to set it in order to make the work of the plugin transparent for the users. While the plugin activation, we created one for you:', 'optinpanda') ?></p>
                    <p><i><?php echo opanda_terms_url() ?></i></p>
                    <p><?php _e('You can change it or create your own one.', 'optinpanda') ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="onp-help-section">
    <p><?php _e('6. In the sidebar on the left, select "APIs", find "Google+ API" in the list and click the button "Off" to make it available for your project:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/3.png' />
    </p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/4.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('7. If you want to subscribe users to your Youtube channel, turn on aslo "<strong>YouTube Data API v3</strong>" additionally to "Google+ API" in the same list.', 'optinpanda' ) ?></p>
    <p><?php _e('But do not activate it if you are not going to use the Youtube subscription feature in order to avoid asking for too many permissions.', 'optinpanda' ) ?></p>
   
</div>

<div class="onp-help-section">
    <p><?php _e('8. In the sidebar on the left, select "Credentials", then click the button "Create new Client ID".', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/5.png' />
    </p>
</div>

<?php
    $origin = null;
    $pieces = parse_url( site_url() );
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $origin = 'http://' . $regs['domain'];
    }
?>

<div class="onp-help-section">
    <p><?php _e('9. In the popup window, select "Web application" and fill up the form:', 'optinpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'optinpanda') ?></th>
                <th><?php _e('How To Fill', 'optinpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Javascript Origin', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website root path:', 'optinpanda') ?></p>
                    <p><i><?php echo $origin ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Redirect URIs', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Paste the URL:', 'optinpanda') ?></p>
                    <p><i><?php echo add_query_arg( array(
                            'action' => 'opanda_connect',
                            'opandaHandler' => 'google'
                        ), admin_url('admin-ajax.php') ) ?></i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/6.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('10. After closing the popup window, you will see your new Client ID:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/7.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('11. Copy and paste it on the page Global Settings > <a href="%s">Social Options</a>.', 'optinpanda' ), opanda_get_settings_url('social') ) ?></p>
</div>