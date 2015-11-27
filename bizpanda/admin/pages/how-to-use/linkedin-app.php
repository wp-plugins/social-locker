<div class="onp-help-section">
    <h1><?php _e('Getting LinkedIn Client ID', 'bizpanda'); ?></h1>

    <p>
        <?php _e('A LinkedIn Client ID is required for the following buttons:', 'bizpanda'); ?>
        <ul>
            <li><?php _e('LinkedIn Sign-In of the Sign-In Locker.', 'bizpanda') ?></li>
            <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
            <li><?php _e('LinkedIn Subscribe of the Email Locker.', 'bizpanda') ?></li>     
            <?php } ?>
        </ul>
    </p>
    
    <p><?php _e('If you want to use these buttons, you need to get create a LinkedIn App for your website and generate a Client ID.', 'bizpanda') ?>
    <?php _e('<strong>You don\'t need to do it</strong> if you\'re not going to use these LinkedIn buttons.') ?></p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Go to the <a href="%s" target="_blank">LinkedIn Developer Network</a> and click <strong>Create Application</strong>.', 'bizpanda'), 'https://www.linkedin.com/secure/developer' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. Fill up the form the following way:', 'bizpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'bizpanda') ?></th>
                <th><?php _e('How To Fill', 'bizpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Company', 'bizpanda') ?></td>
                <td><?php _e('Select an existing company or create your own one (you can use your website name as a company name).', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Name', 'bizpanda') ?></td>
                <td><?php _e('The best name is your website name.', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Description', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Explain what your app does, e.g:', 'bizpanda') ?></p>
                    <p><i><?php _e('This application asks your credentials in order to unlock the content. Please read the Terms of Use to know how these credentials will be used.', 'bizpanda') ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Application Logo URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste an URL to your logo (80x80px). Or use this default logo:', 'bizpanda') ?></p>
                    <p><i><?php _e('https://cconp.s3.amazonaws.com/bizpanda/linkedin-app/default-logo.png', 'bizpanda') ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Application Use', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Select "Other" from the list.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Website URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website URL:', 'bizpanda') ?></p>
                    <p><i><?php echo site_url() ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Business Email', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Enter your email to receive updates regarding your app.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Business Phone', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Enter your phone. It will not be visible for visitors.', 'bizpanda') ?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p><?php _e('Mark the checkbox "I have read and agree to the LinkedIn API Terms of Use." and submit the form.', 'bizpanda') ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('3. On the page "Authentication", mark "<strong>r_basicprofile</strong>" and "<strong>r_emailaddress</strong>".', 'bizpanda' ) ?></p>
</div>

<div class="onp-help-section">
    <p>
        <?php _e('4. In the field "<strong>Authorized Redirect URLs</strong>" of the section "<strong>OAuth 2.0</strong>" paste the URL:', 'bizpanda' ) ?><br />
    </p>
    <p>
        <i>
            <?php echo add_query_arg( array(
                            'action' => 'opanda_connect',
                            'opandaHandler' => 'linkedin',
                            'opandaRequestType' => 'callback'
            ), admin_url('admin-ajax.php') ) ?>
        </i>
    </p>
    <p>Click the orange button "<strong>Add</strong>", then click the button button "<strong>Update</strong>" below the from.</p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. On the page "Settings", switch <strong>Application Status</strong> to <strong>Live</strong>. Click the button Update.', 'bizpanda' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('4. Return to the page "Authentication", copy your <strong>Client ID</strong> and <strong>Client Secret</strong>, paste them on the page Global Settings > <a href="%s">Social Options</a>.', 'bizpanda' ), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social') ) ?></p>
</div>