<?php

class FactoryFormFR100TextboxFormControl extends FactoryFormFR100StandartFormControl 
{
    public $type = 'textbox';
    
    protected function renderInput( $c, $value, $fullname ) {
        $sizeAtrr = (!empty( $c['size'] )) ? 'maxlength="' . $c['size'] . '"' : '';
        $this->addClass( ( !empty( $c['size'] ) ) ? ('size-' . $c['size']) : '' );
        
        $isAppend = (!empty( $c['append'] ));  
        $placeholder = (!empty( $c['placeholder'] )) ? $c['placeholder'] : '';
        ?>

            <input 
                type="text" <?php echo $sizeAtrr ?> 
                value="<?php echo $value ?>" 
                class="<?php echo $this->getClasses() ?>"
                placeholder="<?php echo $placeholder ?>"
                id="<?php echo $fullname ?>" name="<?php echo $fullname ?>" />
            
            <span><?php if ($isAppend) { echo $c['append']; } ?></span>
        
        
        <?php if ( !empty( $c['hint'] ) ) { ?>
            <span class='help-block'><?php echo $c['hint'] ?></span>    
        <?php } 
    }   
}