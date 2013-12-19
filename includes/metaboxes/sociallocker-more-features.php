<?php
#build: free

class SociallockerMoreFeatures extends FactoryFR110Metabox
{
    public $title = 'More Features?';
    public $priority = 'core';
    public $context = 'side';
    
    public function render()
    {
    ?>
        <div class="sl-header">
            <strong>More Features?</strong>
            <p>You Use Only 30% of Social Locker!</p>
            <div class="progress progress-striped active">
              <div class="bar bar-danger" style="width: 30%;"></div>
            </div>
        </div>
        <div class="sl-seporator"></div>
        <ul>
            <li><span data-target="advanced-social-options">Extra social buttons (+4)</span></li>
            <li><span data-target="advanced-themes">Extra themes (+2)</span></li>
            <li><span data-target="advanced-options">Advanced options (+7)</span></li>
        </ul>
        <div class="sl-seporator"></div>
        
        <?php if ( !get_option('fy_trial_activated_' . $this->plugin->pluginName, false) ) { ?>
            <div class="sl-footer">
                <p>These features are available in a premium version of the plugin</p>
                <a href="<?php onepress_fr110_link_license_manager($this->plugin->pluginName, 'activateTrial') ?>" class="btn btn-danger btn-large">
                    Try 7-days Trial Version<br /><span>(activate by one click)</span>
                </a>
                <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="sl-buy">or <strong>buy</strong> the full premium version now!</a>
            </div>
        <?php } else { ?>
            <div class="sl-footer">
                <p>These features are available in a premium version of the plugin</p>
                <a href="http://onepress-media.com/plugin/social-locker-for-wordpress/get" class="btn btn-danger btn-large">
                    Get Premium for $21<br /><span>(it will take no more a minute)</span>
                </a>
                <a href="<?php onepress_fr110_link_license_manager($this->plugin->pluginName, 'activateTrial') ?>" class="sl-buy">or <strong>try</strong> the trial version</a>
            </div>
        <?php } ?>
       
        <div style="display: none">
            <div class="advanced-social-options"></div>
            <div class="advanced-themes"></div>
            <div class="advanced-options"></div> 
            <div class="advanced-support"></div> 
            <div class="advanced-stats"></div>   
        </div>
    <?php
    }
}

$socialLocker->registerMetabox('SociallockerMoreFeatures');