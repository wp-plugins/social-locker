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
    <p><?php _e('4. In the sidebar on the left, select "APIs & auth" > "APIs".', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/2a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Find and enable the following APIs:', 'bizpanda' ) ?></p>
    <ul>
        <li><?php _e('<strong>Google+ API</strong>', 'bizpanda' ) ?></li>
        <li><?php _e('<strong>YouTube APIs</strong> <em>(if you are going to attract subscribers for your Youtube channel)</em>', 'bizpanda' ) ?></li> 
    </ul>
    <p><?php _e('To enable these APIs, click on a title of the required API in the list and then click the button "Enable API".', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/3a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('6. In the sidebar on the left, select "APIs & auth" > "Credentials" and create new credentials "OAuth 2.0 client ID"', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/4a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('7. Google may ask you to set a product name before creating  OAuth client ID, at this case follow the Google instruction and then return back:', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/8.png' />
    </p>
</div>

<?php
    $origin = null;
    $pieces = parse_url( site_url() );
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $origin = $regs['domain'];
    }
?>

<div class="onp-help-section">
    <p><?php _e('8. Fill up the form:', 'bizpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'bizpanda') ?></th>
                <th><?php _e('How To Fill', 'bizpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Application Type', 'bizpanda') ?></td>
                <td>
                    <p>Web Application</p>
                </td>
            </tr>   
            <tr>
                <td class="onp-title"><?php _e('Authorized Javascript origins', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Add the origins:', 'bizpanda') ?></p>
                    <p><i><?php echo 'http://' . str_replace('www.', '', $origin) ?></i></p>
                    <p><i><?php echo 'http://www.' . $origin ?></i></p>
                    
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
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/5a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('9. After clicking on the button Create, you will see your new Client ID:', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/google-app/6a.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('10. Copy and paste it on the page Global Settings > <a href="%s">Social Options</a>.', 'bizpanda' ), opanda_get_settings_url('social') ) ?></p>
</div>