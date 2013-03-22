<?php

class factoryFormFR101ListFormControl extends FactoryFormFR101StandartFormControl 
{
    public $type = 'list';
        
    protected function renderInput( $c, $value, $fullname ) {
        
        $dataOption = $c['data'];
        $data = array();
        
        if (
            is_array($dataOption) && 
            count($dataOption) == 2 && 
            gettype($dataOption[0]) == 'object' ) {
            
            $data = call_user_func($dataOption);
        } elseif ( gettype($dataOption) == 'string' ) {  
            
            $data = $dataOption();
        } elseif ( gettype($dataOption) == 'array' ) {
            
            $data = $dataOption;
        }
        
        ?>

        <select id="<?php echo $fullname ?>" name="<?php echo $fullname ?>" />
        <?php foreach($data as $item) {
            $selected = ( $item[0] == $value ) ? 'selected="selected"' : '';
            ?>
            <option value="<?php echo $item[0] ?>" <?php echo $selected ?>>
                <?php echo $item[1] ?>
            </option>
        <?php } ?>
        </select>
        <?php if ( !empty( $c['hint'] ) ) { ?>
            <span class='help-block'><?php echo $c['hint'] ?></span>    
        <?php } ?>
            
        <?php
    }
}