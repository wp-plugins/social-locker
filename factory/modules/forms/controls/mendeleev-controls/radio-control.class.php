<?php

class FactoryFormFR101PiRadioFormControl extends FactoryFormFR101StandartFormControl 
{
    public $type = 'mv-radio';
    
    protected function renderInput( $conrolInfo, $value, $fullname ) {
        ?>
           <div class="btn-group pi-radio" data-toggle="buttons-radio">
              <?php foreach($conrolInfo['data'] as $data) { ?>
              <button type="button" class="btn <?php if ($value == $data[0]) echo 'active' ?>" data-value="<?php echo $data[0] ?>"><?php echo $data[1]?></button>
              <?php } ?>
            </div>
            <input type="hidden" name="<?php echo $fullname ?>" id="<?php echo $fullname ?>" value="<?php echo $value ?>" />
            <?php if ( !empty( $conrolInfo['hint'] ) ) { ?>
                <span class="help-block"><?php echo $conrolInfo['hint'] ?></span>    
            <?php } ?>
        <?php
    }

}