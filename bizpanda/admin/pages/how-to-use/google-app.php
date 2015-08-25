<div class="onp-help-section">
    <h1><?php _e('Getting Google Client ID', 'bizpanda'); ?></h1>

    <p>
        <?php _e('A Google Client ID is required for the following buttons:', 'bizpanda'); ?>
        <ul>
            <li><?php _e('YouTube Subscribe of the Social Locker.', 'bizpanda') ?></li>            
            <li><?php _e('Google Sign-In of the Sign-In Locker.', 'bizpanda') ?></li>
            <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
            <li><?php _e('Google Subscribe of the Email Locker.', 'bizpanda') ?></li>  
            <?php } ?>
        </ul>
    </p>
    
    <p><?php _e('If you want to use these buttons, you need to get Google Client ID App for your website.', 'bizpanda') ?>
    <?php _e('<strong>You don\'t need to get a Client ID</strong> if you\'re not going to use these buttons.') ?></p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Go to the <a href="%s" target="_blank">Google Developers Console</a>.', 'bizpanda'), 'https://console.developers.google.com/project' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. Click the button "Create Project", enter your website name as a new project name.', 'bizpanda') ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/1a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('3. Wait until your new project is created. After that you will be automatically redirected to your project dashboard.', 'bizpanda' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('4. Select "APIs & auth" > "Consent screen" in the sidebar on the left.', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/2.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Fill up and save the form:', 'bizpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'bizpanda') ?></th>
                <th><?php _e('How To Fill', 'bizpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Email Address', 'bizpanda') ?></td>
                <td><?php _e('Select your email address from the list.', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Product Name', 'bizpanda') ?></td>
                <td><?php _e('The best name is your website name.', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Homepage URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website URL:', 'bizpanda') ?></p>
                    <p><i><?php echo site_url() ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Product Logo', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('If you have any suitable logo, paste it here. Recommended to set for better conversion.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Privacy Policy URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Recommended to set it in order to make the work of the plugin transparent for the users. While the plugin activation, we created one for you:', 'bizpanda') ?></p>
                    <p><i><?php echo opanda_privacy_policy_url() ?></i></p>
                    <p><?php _e('You can change it or create your own one.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Terms of Service URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Recommended to set it in order to make the work of the plugin transparent for the users. While the plugin activation, we created one for you:', 'bizpanda') ?></p>
                    <p><i><?php echo opanda_terms_url() ?></i></p>
                    <p><?php _e('You can change it or create your own one.', 'bizpanda') ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="onp-help-section">
    <p><?php _e('6. In the sidebar on the left, select "APIs" and enable the following APIs:', 'bizpanda' ) ?></p>
    <ul>
        <li><?php _e('<strong>Google API</strong>', 'bizpanda' ) ?></li>
        <li><?php _e('<strong>YouTube APIs</strong> <em>(if you are going to attract subscribers for your Youtube channel)</em>', 'bizpanda' ) ?></li> 
    </ul>
    <p><?php _e('To enable these APIs, click on a title of the required API in the list and then click the button "Enable API".', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/3a.png' />
    </p>
</div>
<div class="onp-help-section">
    <p><?php _e('7. In the sidebar on the left, select "Credentials", then click the button "Create new Client ID".', 'bizpanda' ) ?></p>
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
    <p><?php _e('8. In the popup window, select "Web application" and fill up the form:', 'bizpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'bizpanda') ?></th>
                <th><?php _e('How To Fill', 'bizpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Javascript Origin', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website root path:', 'bizpanda') ?></p>
                    <p><i><?php echo $origin ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Redirect URIs', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste the URL:', 'bizpanda') ?></p>
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
    <p><?php _e('9. After closing the popup window, you will see your new Client ID:', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/7.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('10. Copy and paste it on the page Global Settings > <a href="%s">Social Options</a>.', 'bizpanda' ), opanda_get_settings_url('social') ) ?></p>
</div>