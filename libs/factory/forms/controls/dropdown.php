<?php
/**
 * Dropdown List Control
 * 
 * Main options:
 *  name            => a name of the control
 *  value           => a value to show in the control
 *  default         => a default value of the control if the "value" option is not specified
 *  items           => a callback to return items or an array of items to select
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms320_DropdownControl extends FactoryForms320_Control 
{
    public $type = 'dropdown';
    
    /**
     * Returns a set of available items for the list.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    private function getItems() {
        $data = $this->getOption('data', array());
        
        // if the data options is a valid callback for an object method
        if (
            is_array($data) && 
            count($data) == 2 && 
            gettype($data[0]) == 'object' ) {
            
            return call_user_func($data);
        
        // if the data options is a valid callback for a function
        } elseif ( gettype($data) == 'string' ) {  
            
            return $data();
        }
        
        // if the data options is an array of values
        return $data;
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        $items = $this->getItems();
        $value = $this->getValue();
        
        $nameOnForm = $this->getNameOnForm();  
        
        $this->addHtmlAttr('id', $nameOnForm);
        $this->addHtmlAttr('name', $nameOnForm);
        $this->addCssClass('form-control');
        
        ?>
        <select <?php $this->attrs() ?>/>
        <?php foreach($items as $item) {
            if ( count($item) == 3 ) {
                ?>
                <optgroup label="<?php echo $item[1] ?>" >
                    <?php foreach($item[2] as $subitem) { ?>
                    <?php $selected = ( $subitem[0] == $value ) ? 'selected="selected"' : ''; ?>
                    <option value='<?php echo $subitem[0] ?>' <?php echo $selected ?>>
                    <?php echo $subitem[1] ?>
                    </option>
                    <?php } ?>
                </optgroup>
                <?php
            } else {
                $selected = ( $item[0] == $value ) ? 'selected="selected"' : '';
                ?>
                <option value='<?php echo $item[0] ?>' <?php echo $selected ?>>
                    <?php echo $item[1] ?>
                </option> 
            <?php } ?>
        <?php } ?>
        </select>
        <?php
    }
}
