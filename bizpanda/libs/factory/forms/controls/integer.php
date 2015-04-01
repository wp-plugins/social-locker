<?php
/** 
 * Integer Control
 * Main options:
 *  name         => a name of the control
 *  way          => Тип значения 'slider' - слайдер, 'checkbox-slider' - чекбокс активирует слайдер, по умолчанию input
 *  checkbox     => Указывается если, 'way' имеет значение 'checkbox-slider'
 *                  Пример:
 *                      array(
 *                          'on'  => __('Show shadow', 'optinpanda'),
 *                          'off' => __('Hide shadow', 'optinpanda'),
 *                      )
 *  title        => Заголовок контрола
 *  slider-title => Заголовок слайдера( Только если 'way' имеет значение 'checkbox-slider' )
 *  range        => Диапазон значений, указывается если 'way' имеет значение 'slider' или 'checkbox-slider'
 *                  Пример:  array(0,100) 
 *  units        => Единицы измерения(px,pt,em,%)  
 *  isActive     => Включение, отключение поля                 
 *  value        => a value to show in the control
 *  default      => a default value of the control if the "value" option is not specified
 * 
 * @author Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 2013, OnePress Ltd 
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

class FactoryForms328_IntegerControl extends FactoryForms328_Control 
{
    public $type = 'integer';
  
    /**
     * Converting string to integer.
     * 
     * @since 1.0.0
     * @return integer
     */
    public function html() { 
        
        $name = $this->getNameOnForm();          
        $value = $this->getValue();          
        $isActive = $this->getOption( 'isActive', 1 );  
        $unit  = $this->getOption('units'); 
 
        $way = $this->getOption('way');
        if ( empty( $way ) ) $way = 'text';
        
        $hasSlider = false;

        if ( in_array( $way, array( 'slider', 'checkbox-slider' ) ) ) {
            $range = $this->getOption('range', array(0, 99));
            $slider_title = $this->getOption('slider-title');
            $checkbox = $this->getOption('checkbox');
            $step = $this->getOption('step', 1);  
            $hasSlider = true;
        }
        
        $this->addCssClass('factory-way-' . $way);
        if ( $hasSlider ) $this->addCssClass('factory-has-slider');
        ?>

        <div <?php $this->attrs() ?>>

        <?php if ( $hasSlider ) { ?>

            <?php if( 'checkbox-slider' == $way ) { ?> 

                <div>
                    <label for="<?php echo $name; ?>_checker"><?php echo $isActive ? $checkbox['off'] : $checkbox['on']; ?></label><br>    
                    <input type="checkbox" id="<?php echo $name; ?>_checker" class="factory-checkbox" name="<?php echo $name; ?>_checker" <?php echo $isActive ? 'checked' : '' ?>></p>
                </div>

            <?php } ?>

            <div 
                data-units="<?php echo $unit ?>"    
                data-range-start="<?php echo $range[0] ?>" 
                data-range-end="<?php echo $range[1] ?>"  
                data-step="<?php echo $step ?>"               
                <?php echo !$isActive ? ' style="display:none;"' : '' ?> 
                class="factory-slider-container factory-slider-container-<?php echo $name; ?>">
                
                <?php if( !empty($slider_title) ): ?>    
                <label class="factory-title">
                    <?php echo $this->getOption('slider-title'); ?>
                </label>
                <?php endif; ?>
                
                <div class="factory-slider">
                    
                    <div class="factory-bar"></div>
                    <span class="factory-visible-value">
                        <?php echo $value ?><?php echo $unit ?>
                    </span>
                                
                </div>
                
                <input type="hidden" name="<?php echo $name; ?>" class="factory-result" value="<?php echo $value; ?>" />
            </div>

        <?php } else { ?>

            <input type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" class="factory-input-text" />
            <span class="factory-units"><?php echo $unit ?></span> 

        <?php } ?>
        </div><!-- .factory-integer -->
        <?php
    }
    
    /**
     * Форматирует значение без единиц измерения
     * @param string $values
     * @param string $unit
     * @return string
     */
    public function valueFormatWithoutUnit($values, $unit) {
         if(!is_numeric($values)) 
            return str_replace($unit, '', $values);
        else                                     
            return $values;                            
    }
    /**
     * Форматирует значение c единицами измерения
     * @param string $values
     * @param string $unit
     * @return string
     */
     public function valueFormatWithUnit($values, $unit) {
         if(is_numeric($values)) 
            return $values.$unit;
        else                                     
            return $values;                            
    }
}
