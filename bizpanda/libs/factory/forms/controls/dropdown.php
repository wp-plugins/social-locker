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

class FactoryForms328_DropdownControl extends FactoryForms328_Control 
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
     * Returns true, if the data should be loaded via ajax.
     * 
     * @since 1.0.0
     * @return bool
     */
    protected function isAjax() {
        
        $data = $this->getOption('data', array());
        return is_array($data) && isset($data['ajax']);
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {
        
        $way = $this->getOption('way', 'default');
        $this->addHtmlData('way', $way);
        
        if ( $this->isAjax() ) {
            
            $data = $this->getOption('data', array());
            $ajaxId = 'factory-dropdown-' . rand(1000000, 9999999);
            
            $value = $this->getValue();
            if ( empty( $value ) || empty( $value[0] )) $value = null;
                    
            ?>
            <div class="factory-ajax-loader <?php echo $ajaxId . '-loader'; ?>"></div>
            <script>
                window['<?php echo $ajaxId ?>'] = {
                    'loader': '.<?php echo $ajaxId . '-loader' ?>',
                    'url': '<?php echo $data['url'] ?>',
                    'data': <?php echo json_encode( $data['data'] ) ?>,
                    'selected': '<?php echo $value ?>',
                    'emptyList': '<?php echo $this->getOption('empty', __('The list is empty.', 'factory_forms_328') ) ?>'
                };
            </script>
            <?php
            
            $this->addHtmlData('ajax', true);            
            $this->addHtmlData('ajax-data-id', $ajaxId);
            $this->addCssClass('factory-hidden');  
        }
        
        if ( 'buttons' == $way ) {
            $this->buttonsHtml();   
        } elseif ( 'ddslick' == $way ) {
            $this->ddslickHtml();   
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
     * Shows the ddSlick dropbox.
     * 
     * @since 3.2.8
     * @return void
     */
    protected function ddslickHtml() {
        $items = $this->getItems();
        $value = $this->getValue();
        
        $nameOnForm = $this->getNameOnForm();  
        
        $this->addCssClass('factory-ddslick-way');      
        $this->addHtmlData('name', $nameOnForm);
        
        $this->addHtmlData('width', $this->getOption('width', 300));
        $this->addHtmlData('align', $this->getOption('imagePosition', 'right'));
        
        ?>
        <div <?php $this->attrs() ?>>
            
            <script>
                //Dropdown plugin data
                var factory_<?php echo $nameOnForm ?>_data = [
                    <?php foreach ( $items as $item ) { ?>
                    {
                        text: "<?php echo $item['title'] ?>",
                        value: "<?php echo $item['value'] ?>",
                        selected: <?php if ( $value == $item['value'] ) { echo 'true'; } else { echo 'false'; } ?>,
                        description: "<?php echo ( isset( $item['hint'] ) ? $item['hint'] : '' ); ?>",
                        imageSrc: "<?php echo ( isset( $item['image'] ) ? $item['image'] : '' ); ?>",
                        imageHoverSrc: "<?php echo ( isset( $item['hover'] ) ? $item['hover'] : '' ); ?>"                    
                    },      
                    <?php } ?>
                ];  
            </script>
            
            <div class="factory-ddslick"></div>
            <input type="hidden" class="factory-result" id="<?php echo $nameOnForm ?>" name="<?php echo $nameOnForm ?>" value="<?php echo $value ?>" />
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
        
        $hasGroups = $this->getOption('hasGroups', true);
        $hasHints = $this->getOption('hasHints', false);
        
        foreach($items as $item) {
            if ( !isset( $item['hint'] ) ) continue;
            if ( empty( $item['hint'] ) ) continue;  
            $hasHints = true;
            break;
        }
        
        $isEmpty = $this->isAjax() || empty( $items );
        $emptyList = $this->getOption('empty', __('- empty -', 'factory_forms_328') );

        ?>
            
        <select <?php $this->attrs() ?>>
        <?php if ( $isEmpty ) { ?>
            
            <option value='' class="factory-empty-option" >
                <?php echo $emptyList ?>
            </option> 
                    
        <?php } else { ?>
            
            <?php $this->printItems( $items, $value ) ?>

        <?php } ?>
        </select>
            
        <?php if ( $hasHints ) { ?>
        <div class="factory-hints">
            <?php foreach($items as $item) { 
                
                $hint = isset( $item[2] ) ? $item[2] : null;
                $hint = isset( $item['hint'] ) ? $item['hint'] : null;
                
                $value = isset( $item[0] ) ? $item[0] : null;
                $value = isset( $item['value'] ) ? $item['value'] : null;
                 
                if ( !empty( $hint ) ) { ?>
                    <div style="display: none;" class="factory-hint factory-hint-<?php echo $value ?>" <?php if ( $value !== $value ) { echo 'style="display: none;"'; } ?>><?php echo $hint ?></div>
                <?php }
            } ?>  
        </div>
        <?php } ?>
        <?php
    }
    
    protected function printItems( $items, $selected = null ) {

        foreach( $items as $item ) {
            
            $subitems = array();
            $data = null;
            
            // this item is an associative array
            if ( isset( $item['type'] ) || isset( $item['value'] ) ) {

                $type = isset( $item['type'] ) ? $item['type'] : 'option';
                if ( 'group' === $type ) $subitems = isset( $item['items'] ) ? $item['items'] :array();
                
                $value = isset( $item['value'] ) ? $item['value'] : '';
                $title = isset( $item['title'] ) ? $item['title'] : __( '- empty -', 'factory_forms_328' );
                
                $data = isset( $item['data'] ) ? $item['data'] : null;

            } else {

                $type = ( count($item) == 3 && $item[0] === 'group' ) ? 'group' : 'option';
                if ( 'group' === $type ) $subitems = $item[2];
                
                $title = $item[1];
                $value = $item[0];

            }
            
            if ( 'group' === $type ) {
                ?>
            
                <optgroup label="<?php echo $item[1] ?>" >
                    <?php $this->printItems( $subitems, $selected ); ?>
                </optgroup>
                
                <?php
            } else {
  
                $attr = ( $selected == $value ) ? 'selected="selected"' : '';
 
                $strData = '';
                if ( !empty( $data ) ) {

                    foreach( $data as $key => $values ) {
                        $strData = $strData . ' data-' . $key . '="' . ( is_array( $values ) ? implode(',', $values ) : $values ) . '"';
                    }    
                } 
 
                ?>
                <option value='<?php echo $value ?>' <?php echo $attr ?> <?php echo $strData ?>>
                    <?php echo $title ?>
                </option> 
                <?php
            }
        }
    }
}
