<?php

abstract class FactoryPR108Shortcode {
    
    /**
     * Shortcode name.
     * @var string
     */
    public $shortcode = null;
    
    /**
     * Current factory
     * @var Factory
     */
    public $plugin;
    
    /**
     * Scripts to include on the same page.
     * @var FactoryScriptList 
     */
    public $scripts;
    
    /**
     * Styles to include on the same page.
     * @var FactoryStyleList 
     */
    public $styles;
    
    /**
     * Processor data for shortcodes.
     * Standart processors:
     * - wordpress (by default)
     * - meta (meta tag pre-processor)
     * @var array 
     */
    public $processor = array(
        'type' => 'wordpress'
    );
        
    public function __construct( FactoryPR108Plugin $factory ) {
        $this->factory = $factory;
        $this->scripts = new FactoryPR108ScriptList( $factory );
        $this->styles = new FactoryPR108StyleList( $factory );     
        
        if ( !is_array( $this->shortcode )) {
            $this->shortcode = array( $this->shortcode );
        }
    }

    /**
     * Shortcode configuration.
     */
    public abstract function assets(FactoryPR108ScriptList $scripts, FactoryPR108StyleList $styles);
    
    public function registerForPublic() {}    
    public function registerForAdmin() {} 
    
    /**
     * Renders shortcode.
     */
    public abstract function render($attr, $content);
    
    public function execute($attr, $content) {
        
        ob_start();
        $this->render($attr, $content);
        $html = ob_get_clean();
        
        return $html;
    }
    
    public function renderTemplate($templateName, array $viewData) {
        
        $path = $this->factory->templateRoot . '/' . $templateName . '.tpl.php';
        $model = (object)$viewData;

        include($path);
    }
    
    // -------------------------------------------------------------------------------------
    // Shortcode Tracking
    // 
    // It allows to call a custom function every time when a post, that contains a current 
    // shortcode was changed. To turn on the tracking set the propery $tracking to the 
    // value true. For example, you can use the tracking to reset some cache of shortcodes
    // when post was changed.   
    // -------------------------------------------------------------------------------------
    
    /**
     * Defines whether the changes of post what includes shortcodes are tracked.
     * Set true to turn on the tracking.
     * @var boolean 
     */
    public $tracking = false;
    
    /**
     * The function that will be called when a post containing a current shortcode is changed. 
     * @param string $shortcode
     * @param mixed[] $attr
     * @param string $content
     * @param integer $postId
     * @throws ErrorException Raises an exception if the method is not implemented
     */
    public function trackingCallback($shortcode, $attr, $content, $postId) { 
        throw new ErrorException('The tracking callback for a shortcode is not implemented.');
    }
}