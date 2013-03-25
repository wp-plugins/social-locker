<?php

class SociallockerPreviewMetaBox extends FactoryFR103Metabox
{
    public $title = 'Locker Preview';
    public $priority = 'core';
    
    public function render()
    {
        ?>
        <script>
            function updateFrameSize(height) {
                jQuery("#lock-preview-wrap iframe").height(height);
            }
        </script>
        <p class="note"><strong>Note</strong>: It's just a preview. The locker and the social buttons don't work correctly in the admin area.</p>
        <div id="lock-preview-wrap" 
             data-lang="<?php echo get_option('sociallocker_lang') ?>" 
             data-short-lang="<?php echo get_option('sociallocker_short_lang') ?>" 
             data-facebook-appid="<?php echo get_option('sociallocker_facebook_appid') ?>" 
             data-url="<?php echo $this->plugin->pluginUrl ?>/admin/locker-preview.php">
            <iframe 
                allowtransparency="1" 
                frameborder="0" 
                hspace="0" 
                marginheight="0"
                marginwidth="0"
                name="preview"
                vspace="0"
                width="100%">
                Your browser doen't support the iframe tag.
            </iframe>
        </div>
        <?php
    }
}