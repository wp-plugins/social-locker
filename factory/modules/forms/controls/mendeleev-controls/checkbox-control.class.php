<?php

class FactoryFormFR100CheckboxFormControl extends FactoryFormFR100StandartFormControl 
{
    public $type = 'checkbox';
    
    protected function renderInput( $c, $value, $fullname ) {
        $checked = $value ? 'checked="checked"' : '';

        ?>
            <div class="btn-group pi-checkbox" data-toggle="buttons-radio">
                <button type="button" class="btn true <?php if ($value) echo 'active' ?>" data-value="true">On</button>
                <button type="button" class="btn false <?php if (!$value) echo 'active' ?>" data-value="false">Off</button>                 
            </div>

            <input style="display: none;" type='checkbox' value='1' <?php echo $checked ?> name='<?php echo $fullname ?>' id='<?php echo $fullname ?>' />  
                
            <?php if ( !empty( $c['hint'] ) ) { ?>
                <span class="help-block"><?php echo $c['hint'] ?></span>    
            <?php } ?>
        <?php
    }
    
    public function getValue( $name ) {
        $fullname = ( !empty( $this->props['scope'] )) ? $this->props['scope'] . '_' . $name : $name;
        return isset($_POST[$fullname]) ? 'true' : 'false';
    }
}