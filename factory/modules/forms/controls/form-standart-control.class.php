<?php

abstract class FactoryFormFR107StandartFormControl extends FactoryFormFR107Control {
    
    public function render()
    {
        if (isset($this->props['value'])) {
            $value = $this->props['value'];
        } else {
            $value = $this->provider->getValue( $this->props['name'], $this->props['default'] );
        }
        
        $aseetUrl = $this->plugin->pluginUrl . '/assets/';
        $hasIcon = !empty( $this->props['icon'] );
        if ( $hasIcon ) $icon = str_replace('~/', $aseetUrl, $this->props['icon']);
        ?>
            <div class='control-group control-group-<?php echo $this->props['name'] ?>'>
                <label class='control-label' for='<?php echo $this->props['fullname'] ?>'>
                    <?php if ($hasIcon) { ?>
                        <img class="control-icon" src="<?php echo $icon ?>" />
                    <?php } ?>
                    <?php echo $this->props['title'] ?>
                </label>
                <div class='controls'>
                    <?php $this->renderInput( $this->props, $value, $this->props['fullname'] ) ?>
                </div>
            </div>
        <?php
    }
    
    protected abstract function renderInput( $control, $value, $name );
}