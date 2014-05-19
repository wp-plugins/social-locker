<?php
/**
 * Background Control
 * 
 * Main options:
 *  name     => a name of the control
 *  value    => путь к изображению
 *  default  => по умолчанию "false" или путь к изображению
 *  images   => array(
 *                array(
 *                  'thumbian' => 'path/simple_thumb.png',
 *                  'original' => 'path/simple.png',
 *                ),
 *                array('thumbian' => 'path/simple_dark.png'),
 *              )
 * 
 * @author Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

class FactoryForms311_BackgroundControl extends FactoryForms311_Control 
{
    public $type = 'background';
    
         
    /**
     * Preparing html attributes before rendering html of the control. 
     * 
     * @since 1.0.0
     * @return void
     */
    protected function beforeHtml() {
        $name = $this->getName('name');
        
        // filters to get available patterns for the given background contols
        $this->patterns = $this->getOption('patterns', array());
        $this->patterns = apply_filters('factory_forms_311_background_patterns', $this->patterns);
        $this->patterns = apply_filters('factory_forms_311_background_patterns-' . $name, $this->patterns);
        
        $patterns = $this->getOption('patterns', array());
        if ( !empty( $patterns ) ) {
            $this->patterns = array_merge( $patterns, $this->patterns );
        }
    }
    
    /**
     * Shows the html markup of the control.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html( ) {  
        $name = $this->getNameOnForm();
        $values = $this->getValue();
        
        
        ?>
        <div class="factory-background-select">            
            <div class="factory-background-select-preview">
                <div class="factory-select-preview-image-wrap">
                    <div <?php echo (!empty($values)) ? 'style="background:url('.$values.') repeat; border:0; font-size:0;"' : ''; ?> class="factory-select-preview-image <?php echo $name; ?>"><span></span></div>                    
                    <input type="hidden" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $values; ?>">
                </div>
                <a href="#" class="factory-select-preview-button-upload factory-dahsed"><?php _e('Upload Pattern', 'factory_forms_311'); ?></a>                
            </div>            
            <ul class="factory-bgimage-pack">
                <li class="factory-bgimage-pack-item not-pattern"><div><span class="factory-cross"></span></div></li>
            <?php foreach( $this->patterns as $pattern ): ?>
                <li class="factory-bgimage-pack-item" data-pattern="<?php echo $pattern['pattern']; ?>">
                    <div class="factory-pattern-holder" style="background:url(<?php echo $pattern['preview']; ?>) repeat;"></div>
                </li>             
            <?php endforeach; ?>    
            </ul>           
            <div class="clearfix"></div>
        </div>
        <?php
    }
}
