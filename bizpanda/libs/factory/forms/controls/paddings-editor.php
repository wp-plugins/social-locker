<?php
/** 
 * Paddings Control
 */

class FactoryForms328_PaddingsEditorControl extends FactoryForms328_Control 
{
    public $type = 'paddings-editor';
  
    /**
     * Converting string to integer.
     * 
     * @since 1.0.0
     * @return integer
     */
    public function html() { 
        
        $name = $this->getNameOnForm();          
        $rawValue = $this->getValue(); 
        
        $units = $this->getOption('units');
        $valuesWithUnits = explode(' ', $rawValue);
        
        $values = array();
        foreach($valuesWithUnits as $valueWithUnit) {
            $values[] = intval($valueWithUnit);
        }
        
        $unit  = $this->getOption('units', 'px'); 
        $range = $this->getOption('range', array(0, 99));
        $step = $this->getOption('step', 1);
        
        ?>
        <div <?php $this->attrs() ?>
            data-units="<?php echo $unit ?>"
            data-range-start="<?php echo $range[0] ?>" 
            data-range-end="<?php echo $range[1] ?>"  
            data-step="<?php echo $step ?>">
            
            <div class="factory-rectangle">
                <div class="factory-side factory-side-top" data-value="<?php echo $values[0] ?>"><span class="factory-visible-value"><?php echo $values[0] ?><?php echo $units ?></span></div>
                <div class="factory-side factory-side-bottom" data-value="<?php echo $values[1] ?>"><span class="factory-visible-value"><?php echo $values[1] ?><?php echo $units ?></span></div>
                <div class="factory-side factory-side-left" data-value="<?php echo $values[2] ?>"><span class="factory-visible-value"><?php echo $values[2] ?><?php echo $units ?></span></div>
                <div class="factory-side factory-side-right" data-value="<?php echo $values[3] ?>"><span class="factory-visible-value"><?php echo $values[3] ?><?php echo $units ?></span></div>
                <div class="factory-side factory-side-center" data-value="<?php echo $values[0] ?>"></div>     
            </div>
            
            <div class="factory-slider-container">
                
                <label class="factory-title">
                    <?php _e('Select a side and move the slider to set up:','factory_forms_328') ?>
                </label>
                
                <div class="factory-slider">
                    <div class="factory-bar"></div>    
                </div>
            </div>
            
            <input type="hidden" class="factory-result" name="<?php echo $name ?>" value="<?php echo $rawValue ?>" />
        </div>
        <?php
    }
}
