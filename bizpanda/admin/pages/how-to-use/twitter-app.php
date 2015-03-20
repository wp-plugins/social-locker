<div class="onp-help-section">
    <h1><?php _e('Creating Twitter App', 'optinpanda'); ?></h1>

    <p>
        <?php _e('A Twitter App is required for the Twitter Sign-In button of the Sign-In Locker.', 'optinpanda'); ?>
        <?php _e('We already created one by default. It\'s completely ready to use, so you can skip this instruction.', 'optinpanda'); ?>
    </p>
    
    <p class='onp-note'>
        <?php _e('Create your own Twitter App only if you want to change the app title, url, image, description visible when the user click on the Tweet Sign-In button.', 'optinpanda') ?>
    </p>
    
    <p><?php _e('On the image below you can see which areas you can change by creating your own app.', 'optinpanda') ?></li>
    
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/twitter-app/1.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Open the website <a href="%s" target="_blank">apps.twitter.com</a> and click "Create New App".', 'optinpanda'), 'https://apps.twitter.com' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. Fill up the form, agree to the Developer Agreement, click "Create Your Twitter application".', 'optinpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'optinpanda') ?></th>
                <th><?php _e('How To Fill', 'optinpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Name', 'optinpanda') ?></td>
                <td><?php _e('The best app name is your website name.', 'optinpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Description', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Explain why you ask for the credentials, e.g:', 'optinpanda') ?></p>
                    <p><i><?php _e('This application asks your credentials in order to unlock the content. Please read the Terms of Use to know how these credentials will be used.', 'optinpanda') ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Website', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website URL:', 'optinpanda') ?></p>
                    <p><i><?php echo site_url() ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Callback URL', 'optinpanda') ?></td>
                <td>
                    <p><?php _e('Paste the URL:', 'optinpanda') ?></p>
                    <p><i><?php echo add_query_arg( array(
                            'action' => 'opanda_connect',
                            'opandaHandler' => 'twitter'
                        ), admin_url('admin-ajax.php') ) ?></i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="onp-help-section">
    <p><?php _e('3. Click the tab "Settings.', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/twitter-app/2.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('4. Mark the checkbox "Allow this application to be used to Sign in with Twitter" and, if you want, change the app icon. Then click the button "Update Settings".', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/twitter-app/3.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Move to the section "Permissions", mark "Read and Write" and save changes.', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/twitter-app/4.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('6. Move to the section "Keys and Access Tokens", find your Custumer Key and Customer Secret:', 'optinpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/twitter-app/5.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('7. Paste your key and secret on the page Global Settings > <a href="%s">Social Options</a>.', 'optinpanda' ), admin_url('admin.php?page=settings-optinpanda&opanda_screen=social') ) ?></p>
</div>