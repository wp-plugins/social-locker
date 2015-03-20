<?php

global $bizpanda;
$lang = $bizpanda->options['lang'];

require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
$optinpandaUrl = OPanda_Plugins::getPremiumUrl('optinpanda');
$sociallockerUrl = OPanda_Plugins::getPremiumUrl('sociallocker');

?>

<div class="onp-help-section">
    <h1><?php _e('Sign-In Locker', 'bizpanda'); ?></h1>
    
    <?php if ( BizPanda::hasPlugin('sociallocker') ) { ?>
    
        <p>
            <?php printf( __('Sign-In Locker works the same way as the <a href="%s">Social Locker</a> but instead of liking/sharing it asks the user to sign in through social networks. Concurrently it can perform some social actions.', 'opanda'), opanda_get_help_url('what-is-social-locker') ) ?>
        </p>
    
    <?php } elseif( BizPanda::hasPlugin('optinpanda' ) ) { ?>
    
        <p>
            <?php printf( __('Sign-In Locker works the same way as the <a href="%s">Email Locker</a> but instead of entering an email it asks the user to sign in through social networks. Concurrently it can perform some social actions.', 'opanda'), opanda_get_help_url('what-is-email-locker') ) ?>
        </p>
    
    <?php } ?>

    <p><strong><?php _e('What can you do with the Sign-In Locker', 'opanda') ?></strong></p>
    
    <p>
        <?php _e('When the user signs in, the social network grants access to the user\'s contact data and allow additionally perform the following actions:', 'opanda') ?>
    </p>

    <ul style="margin-bottom: 20px;">
        <li><p><?php _e('Register the user on our website (create an account)', 'opanda') ?></p></li>
        <?php if ( !BizPanda::hasPlugin('optinpanda') ) { ?> 
        <li><?php printf( __('Subscribe the user to your mailing list (<a href="%s" target="_blank">Opt-In Panda</a> required)', 'opanda'), $optinpandaUrl ) ?></li>
        <?php } else { ?>
            <li><?php _e('Subscribe the user to your mailing list.', 'opanda') ?></li>
        <?php } ?>
        <?php if ( !BizPanda::hasPlugin('sociallocker') ) { ?> 
            <li><?php printf( __('Publish a tweet from behalf of the user (<a href="%s" target="_blank">Social Locker</a> required)', 'opanda'), $sociallockerUrl ) ?></li>
            <li><?php printf( __('Subscribe the user to your account on Twitter (<a href="%s" target="_blank">Social Locker</a> required)', 'opanda'), $sociallockerUrl ) ?></li>   
            <li><?php printf( __('Subscribe the user to your account on LinkedIn (<a href="%s" target="_blank">Social Locker</a> required)', 'opanda'), $sociallockerUrl ) ?></li>
            <li><?php printf( __('Subscribe the user to your Youtube channel (<a href="%s" target="_blank">Social Locker</a> required)', 'opanda'), $sociallockerUrl ) ?></li>
        <?php } else { ?>
            <li><?php _e('Publish a tweet from behalf of the user', 'opanda') ?></li>
            <li><?php _e('Subscribe the user to your account on Twitter (make the user a follower)', 'opanda') ?></li>   
            <li><?php _e('Subscribe the user to your account on LinkedIn (make the user a follower)', 'opanda') ?></li>
            <li><?php _e('Subscribe the user to your Youtube channel', 'opanda') ?></li>
        <?php } ?>
    </ul>
    
    <p><strong><?php _e('Know your audience') ?></strong></p>
    
    <p><?php _e('Except the user\'s contact data, the Sign-In Locker also receives some personal data (name, profile url).') ?></p>
    <p><?php _e('That allows you to know better your audinece and understand who is these people who visits your website. Also you can go and engage with them personally on their social pages on Facebook, Twitter, Google or LinkedIn.') ?></p>

    <?php if ( BizPanda::hasPlugin('sociallocker') ) { ?>

        <p><strong><?php _e('Comparing Sign-In Locker and Social Locker', 'opanda') ?></strong></p>

        <p>
            <?php _e('Although the Sign-In Locker brings more benefits per unlock, it has more lower conversion than Social Locker. Please check out the table below to learn more:', 'opanda') ?>
        </p>

        <table class="table">
            <thead>
                <tr>
                    <th>Paramters</th>
                    <th>Sing-In Locker</th>
                    <th>Social Locker</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php _e('Benefits', 'opanda') ?></td>
                    <td><?php _e('More benefits per unlock. Can be configured to execute several actions per unlock.', 'opanda') ?></td>
                    <td><?php _e('Only one action has to be perfomed to unlock the content.', 'opanda') ?></td>
                </tr>
                <tr>
                    <td><?php _e('Conversion', 'opanda') ?></td>
                    <td><?php _e('More lower conversion due to the locker asks the user to authorize your social app and grant extra permissions.', 'opanda') ?></td>
                    <td><?php _e('Extremely high conversion which may reach up to 50%. All what the user has to make is to click on the social button.', 'opanda') ?></td>
                </tr>
                <tr>
                    <td><?php _e('Content', 'opanda') ?></td>
                    <td><?php _e('As asks the user to authorize your social app to read one\'s personal data, the content you provide should have more value.', 'opanda') ?></td>
                    <td><?php _e('You can lock practically any content. Giving a like is not hard.', 'opanda') ?></td>
                </tr>    
            </tbody>
        </table>
    
    <?php } elseif( BizPanda::hasPlugin('optinpanda' ) ) { ?>

        <p><strong><?php _e('Comparing Sign-In Locker and Email Locker', 'opanda') ?></strong></p>

        <p>
            <?php _e('Although the Sign-In Locker brings more benefits per unlock, it has more lower conversion than Email Locker. Please check out the table below to learn more:', 'opanda') ?>
        </p>

        <table class="table">
            <thead>
                <tr>
                    <th>Paramters</th>
                    <th>Sing-In Locker</th>
                    <th>Email Locker</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php _e('Benefits', 'opanda') ?></td>
                    <td><?php _e('More benefits per unlock. Can be configured to execute several actions per unlock.', 'opanda') ?></td>
                    <td><?php _e('The user has to opt-in only to unlock the content.', 'opanda') ?></td>
                </tr>
                <tr>
                    <td><?php _e('Conversion', 'opanda') ?></td>
                    <td><?php _e('More lower conversion due to the locker asks the user to authorize your social app and grant extra permissions.', 'opanda') ?></td>
                    <td><?php _e('High conversion which may reach up to 40%.', 'opanda') ?></td>
                </tr>
                <tr>
                    <td><?php _e('Content', 'opanda') ?></td>
                    <td><?php _e('As asks the user to authorize your social app to read one\'s personal data, the content you provide should have more value.', 'opanda') ?></td>
                    <td><?php _e('You can lock practically any content.', 'opanda') ?></td>
                </tr>    
            </tbody>
        </table>
    
    <?php } ?>

    <p style="margin-top: 25px;">
        <a href="<?php $manager->actionUrl('index', array( 'onp_sl_page' => 'usage-example-signin-locker' )) ?>" class="btn btn-default"><?php _e('Learn how to configure and use Sign-In Locker', 'opanda') ?><i class="fa fa-long-arrow-right"></i></a>
    </p>
    
</div>