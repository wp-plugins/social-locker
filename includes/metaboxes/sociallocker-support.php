<?php

class SociallockerSupportMetaBox extends FactoryFR107Metabox
{
    public $title = 'Support';
    public $priority = 'core';
    public $context = 'side';
    
    public function render()
    {
        ?>
        <div class="panel">
            <p>If you have any questions or need any help please use one of the ways below:</p>
            <ul>
                <li>Visit our <a href="http://support.onepress-media.com/Knowledgebase/List/Index/6/wordpress-version" target="_blank">Knowledgebase</a> of issues.</li> 
                <li>Submit a <a href="http://support.onepress-media.com/Tickets/Submit" target="_blank">Ticket</a> to our support team.</li>       
                <li>Mail us: <a href="mailto: support@onepress-media.com">support@onepress-media.com</a></li>
            </ul>
            <p>We guarantee to respond to every inquiry within <strong>1 business day</strong> (typical response time is 3 hours).</p>
            <p class="pi-highlight"><a href="http://onepress-media.com/portfolio" target="_blank">View other OnePress's plugins &#9656;</a></p>
        </div>
        <?php
    }
}

$socialLocker->registerMetabox('SociallockerSupportMetaBox');