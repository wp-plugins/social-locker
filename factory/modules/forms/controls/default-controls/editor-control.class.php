<?php

class FactoryFormPR108EditorFormControl extends FactoryFormPR108Control 
{
    public $type = 'editor';
    
    public function render()
    {
    $value = $this->provider->getValue( $this->props['name'], $this->props['default'] );
    
    $tinymceInit = array();
    if ( !empty( $this->props['eventCallback'] ) )
        $tinymceInit['handle_event_callback'] = $this->props['eventCallback'];
    
    $tinymceInit['content_css'] = FACTORY_PR108_URL . '/assets/css/editor.css';  

    $aseetUrl = $this->plugin->pluginUrl . '/assets/';
    $hasIcon = !empty( $this->props['icon'] );
    if ( $hasIcon ) $icon = str_replace('~/', $aseetUrl, $this->props['icon']);
    
    ?>
        <div class='control-group pi-editor-wrap pi-editor-<?php echo $this->props['fullname'] ?>'>
            <label class='control-label' for='<?php echo $this->props['fullname'] ?>'>
                <?php if ($hasIcon) { ?>
                    <img class="control-icon" src="<?php echo $icon ?>" />
                <?php } ?>
                <?php echo $this->props['title'] ?>
                <?php if ( !empty( $this->props['hint'] ) ) { ?>
                    <span class='help-block'><?php echo $this->props['hint'] ?></span>    
                <?php } ?>
            </label>
            <div class='controls'>
                
                <div class='pi-editor'>
                <?php wp_editor( $value, $this->props['fullname'], array(
                    'textarea_name' => $this->props['fullname'],
                    'wpautop' => false,
                    'teeny' => true,
                    'tinymce' => $tinymceInit
                )); ?> 
                </div>
            
            </div>
        </div>
    <?php
    }
}