<?php

class SociallockerSupportMetaBox extends FactoryFR110Metabox
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
                <li>Visit our <a href="http://support.onepress-media.com" target="_blank">Knowledgebase</a> of issues.</li> 
                <li>Submit a <a href="http://support.onepress-media.com/Tickets/Submit" target="_blank">Ticket</a> to our support team.</li>       
            </ul>
            <p>We guarantee to respond to every inquiry within <strong>1 business day</strong> (typical response time is 3 hours).</p>
            <p class="pi-highlight"><a href="http://onepress-media.com/portfolio" target="_blank">View other OnePress's plugins &#9656;</a></p>
        </div>
        <?php
    }
}

$socialLocker->registerMetabox('SociallockerSupportMetaBox');