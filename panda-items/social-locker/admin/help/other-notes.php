<?php

global $bizpanda;
$lang = $bizpanda->options['lang'];
?>

<div class="onp-help-section">
    <h1><?php _e('Using the Facebook Like with the Social Locker', 'sociallocker'); ?></h1>
    
    <p>
        <?php _e('This note describes the Facebook restriction regarding using the Facebook Buttons in the Social Locker.', 'sociallocker') ?>      
        <?php _e('Since 5 Nov, you don\'t have to incentivize people to like your page to unlock the content:') ?>
    </p>
    
    <p class='onp-remark'>
        <span class="onp-inner-wrap">
            <i><?php _e('You must not incentivize people to use social plugins or to like a Page. This includes offering rewards, or gating apps or app content based on whether or not a person has liked a Page. It remains acceptable to incentivize people to login to your app, checkin at a place ...', 'sociallocker') ?></i><br />    
        <i style="display: block; margin-top: 5px;">
            <strong><?php _e('Source:', 'optionpanda') ?></strong> 
            <a href="https://developers.facebook.com/blog/post/2014/08/07/Graph-API-v2.1/" target="_blank">https://developers.facebook.com/policy#properuse</a>
        </i>
        </span>
    </p>

    <p>
        <strong><?php _e('This restriction doesn\'t affect on:</strong>', 'sociallocker') ?></strong>
        <ul>
            <li><?php _e('Facebook Sign-In and Subscribe buttons (which ask to sign in to a Facebook app).', 'sociallocker') ?></li>
            <li><?php _e('Other social networks (Twitter, Google, LinkedIn).', 'sociallocker') ?></li>
        </ul>
    </p> 
    
    <p>
        <?php _e('Technically <strong>you can ignore this restriction, the Social Locker will continue working without any problems</strong>. Also you can just update a bit the settings of your lockers to make it compatible with the new policy.', 'sociallocker') ?>    
    </p>
</div>

<div class="onp-help-section">
    <h2><?php _e('Making Social Locker compatible with the Facebook Policies', 'sociallocker'); ?></h2>
    
    <p><?php _e('If want to use the Social Locker with the Facebook Like and keep it compatible with the new Facebook Policies, you need to convert your Social Locker to "Social Reminder". What does it mean?', 'sociallocker') ?></p>

    <p><strong><?php _e('1. Enable the option Close Icon.', 'sociallocker') ?></strong></p>
    <p>
        <?php _e('You have to give people the way to skip the liking process.', 'sociallocker') ?>
        <?php _e('The Close Icon is not bright and the most people will not notice it at first time and will still click on the Like button.', 'sociallocker') ?>
    </p>
    
    <p><strong><?php _e('2. Remove any phrases like "this content is locked" from your locker.', 'sociallocker') ?></strong></p>
    <p>
        <?php _e('Don\'t write that your content is locked. Ask support you because you need it in order to keep doing what you\'re doing (provide free downloads, write good articles and so on).', 'sociallocker') ?>
    </p>
    
    <p><strong><?php _e('3. Turn on the Transparency or Blurring mode.', 'sociallocker') ?></strong></p>
    <p>
        <?php _e('It makes your locker looks like a popup which appears suddenly to ask the user to support you.', 'sociallocker') ?>
    </p>
    
    <p class='onp-img'>
        <img src='<?php echo OPANDA_BIZPANDA_URL . '/assets/admin/img/how-to-use/facebook-like/1.png' ?>' />
    </p>
</div>
