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

class FactoryForms324_DropdownControl extends FactoryForms324_Control 
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
        if ( 'buttons' == $this->getOption('way') ) {
            $this->buttonsHtml();
        } else {
            $this->defaultHtml();
        }
    }
    
    /**
     * Shows the Buttons Dropdown.
     * 
     * @since 1.0.0
     * @return void
     */
    protected function buttonsHtml() {
        $items = $this->getItems();
        $value = $this->getValue();
        
        $nameOnForm = $this->getNameOnForm();  
        
        $this->addCssClass('factory-buttons-way');      
        
        ?>
        <div <?php $this->attrs() ?>>
            <div class="btn-group factory-buttons-group">
                <?php foreach($items as $item) { ?>
                <button type="button" class="btn btn-default btn-small factory-<?php echo $item[0] ?> <?php if ( $value == $item[0] ) { echo 'active'; } ?>" data-value="<?php echo $item[0] ?>"><?php echo $item[1] ?></button>
                <?php } ?>
                <input type="hidden" id="<?php echo $nameOnForm ?>" class="factory-result" name="<?php echo $nameOnForm ?>" value="<?php echo $value ?>" />
            </div>
            <div class="factory-hints">
                <?php foreach($items as $item) { ?>
                    <?php if ( isset( $item[2] )) { ?>
                        <div class="factory-hint factory-hint-<?php echo $item[0] ?>" <?php if ( $value !== $item[0] ) { echo 'style="display: none;"'; } ?>><?php echo $item[2] ?></div>
                    <?php } ?>
                <?php } ?>  
            </div>  
        </div>
        <?php
    }
    
    /**
     * Shows the standart dropdown.
     * 
     * @since 1.3.1
     * @return void
     */
    protected function defaultHtml() {
        
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
