<?php
/**
 * Radio Control
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

class FactoryForms328_RadioControl extends FactoryForms328_Control 
{
    public $type = 'radio';
    
    /**
     * Returns a set of available items for the radio.
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
     * Preparing html attributes before rendering html of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        $nameOnForm = $this->getNameOnForm();
        $this->addHtmlAttr('name', $nameOnForm);
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
        ?>
        <?php foreach($items as $item) {
            $checked = ( $item[0] == $value ) ? 'checked="checked"' : '';
            ?>
            <span class="factory-form-radio-item">
                <label class="factory-from-radio-label"><?php echo $item[1] ?></lable>
                <input type="radio" <?php $this->attrs() ?> value="<?php echo $item[0] ?>" <?php echo $checked ?>/>
            </span>
        <?php }
    }
}
