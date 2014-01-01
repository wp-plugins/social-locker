<?php
/**
 * The file contains a class that represnets an abstraction for forms.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * An abstraction for forms.
 */
class FactoryForms300_Form {
    
    // ----------------------------------------------------
    // Static fields and methods
    // ----------------------------------------------------
    
    /**
     * This array contains data to use a respective control.
     * 
     * @since 1.0.0
     * @var array 
     */
    private static $_registeredControls = array();

    /**
     * Registers a new control.
     * 
     * @since 1.0.0
     * @param mixed[] $item Control data having the following format:
     *                      type      => a control type
     *                      class     => a control php class
     *                      include   => a path to include control code
     * @return void
     */
    public static function registerControl( $item ) {
        self::$_registeredControls[$data['type']] = $item;
    }
    
    /**
     * Registers a set of new controls.
     * 
     * @see FactoryForms300_Form::registerControl()
     * 
     * @since 1.0.0
     * @return void
     */
    public static function registerControls( $data ) {
        foreach($data as $item)
            self::$_registeredControls[$item['type']] = $item;
    }  
    
    /**
     * This array contains holder data to use a respective control holder.
     * 
     * @since 1.0.0
     * @var array 
     */
    private static $_registeredHolders = array();

    /**
     * Registers a new holder.
     * 
     * @since 1.0.0
     * @param mixed[] $item Holder data having the follwoin format:
     *                      type      => a control holder type
     *                      class     => a control holder php class
     *                      include   => a path to include control holder code
     * @return void
     */
    public static function registerHolder( $item ) {
        self::$_registeredHolders[$data['type']] = $item;
    }

    /**
     * Registers a set of new holder controls.
     * 
     * @see FactoryForms300_Form::registerHolder()
     * 
     * @since 1.0.0
     * @return void
     */
    public static function registerHolders( $data ) {
        foreach($data as $item)
            self::$_registeredHolders[$item['type']] = $item;
    }  
    
    /**
     * This array contains custom form element data to use a respective elements.
     * 
     * @since 1.0.0
     * @var array 
     */
    private static $_registeredCustomElements = array();

    /**
     * Registers a new custom form element.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function registerCustomElement( $item ) {
        self::$_registeredCustomElements[$item['type']] = $item;
    }

    /**
     * Registers a set of new custom form elements.
     * 
     * @see FactoryForms300_Form::registerCustomElement()
     * 
     * @since 1.0.0
     * @return void
     */
    public static function registerCustomElements( $data ) {
        foreach($data as $item)
            self::$_registeredCustomElements[$item['type']] = $item;
    }  
    
    /**
     * Contains a set of theme registered for controls.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    private static $_controlThemes = array();
    
    /**
     * Registers a new theme for controls.
     * 
     * @since 1.0.0
     * @param type $data A theme data. Has the following format:
     *                      name      => a name of the theme
     *                      class     => a theme php class
     *                      include   => a path to include theme code
     * @return void
     */
    public static function registerControlTheme( $data ) {
        self::$_controlThemes[$data['name']] = $data;
    }
    
    /**
     * Contains a set of layouts registered for forms.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    private static $_formLayouts = array();
    
    /**
     * Registers a new layout for forms.
     * 
     * @since 1.0.0
     * @param type $data A layout data. Has the following format:
     *                      name      => a name of the layout
     *                      class     => a layout php class
     *                      include   => a path to include layout code
     * @return void
     */
    public static function registerFormLayout( $data ) {
        self::$_formLayouts[$data['name']] = $data;
    }
 
    /**
     * Extra propery that determines which UI is used in the admin panel.
     * 
     * @since 1.0.0
     * @var string 
     */
    public static $temper;
    
    // ----------------------------------------------------
    // Object fields and methods
    // ----------------------------------------------------
    
    /**
     * A value provider of the form that is used to save and load values.
     * 
     * @since 1.0.0
     * @var IFactoryValueProvider 
     */
    private $provider;
    
    /**
     * A prefix that will be used for names of input fields in the form.
     * 
     * @since 1.0.0
     * @var string
     */
    public $scope;
    
    /**
     * A form name that is used to call hooks and filter data.
     * 
     * @since 1.0.0
     * @var string
     */
    public $name = 'default';
    
    /**
     * It's not yet input controls. The array contains names of input controls and their 
     * options that are used to render and process input controls.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    protected $items = array();
    
    /**
     * Full set of input controls available after building the form.
     * 
     * The array contains objects.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    private $controls = array();
    
    /**
     * A layout for the form.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $formLayout;
    
    /**
     * A default theme for controls.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $controlTheme;
    
    /**
     * A current form layout used to render a form.
     * 
     * @since 1.0.0
     * @var FactoryForms300_FormLayout 
     */
    public $layout;
    
    
    /**
     * Creates a new instance of a form.
     * 
     * @since 1.0.0
     * @param string $options Contains form options to setup.
     */
    public function __construct( $options = array () ) {
        global $wp_version;
        $isFlat = version_compare( $wp_version, '3.8', '>='  );
        
        $this->scope = isset( $options['scope'] ) ? $options['scope'] : null;
        
        if ( isset( $options['formLayout'] ) ) {
            $this->formLayout = $options['formLayout'];
        } else {
            if ( $isFlat ) {
                $this->formLayout = 'bootstrap-3';
            } else {
                $this->formLayout = 'bootstrap-2';
            }
        }
        
        if ( !self::$temper ) self::$temper = $isFlat ? 'flat' : 'volumetric';
        $this->controlTheme = isset( $options['controlTheme'] ) ? $options['controlTheme'] : null; 
    }
    
    /**
     * Sets a provider for the control.
     * 
     * @since 1.0.0
     * @param IFactoryForms300_ValueProvider $provider
     * @return void
     */
    public function setProvider( $provider ) {
        $this->provider = $provider;
    }
    
    /**
     * Adds items into the form.
     * 
     * It's base method to use during configuration form.
     * 
     * @since 1.0.0
     * @param array $array An array of items.
     */
    public function add( $array ) {

        if ( (bool)count(array_filter(array_keys($array), 'is_string')) ) {
            $this->items[] = $array;
        } else {
            $this->items = array_merge($this->items, $array);
        }
    }
    
    /**
     * Returns items to render.
     * 
     * Has the follwoing hooks:
     *  'factory_form_items' ( $formName, $items ) to filter form controls before building.
     * 
     * @since 1.0.0
     * @return mixed[] Items to render.
     */
    public function getItems() {
        return apply_filters('factory_form_items', $this->items, $this->name );
    }
    
    /**
     * Returns form controls (control objects).
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getControls() {
        if ( !empty($this->controls) ) return $this->controls;
        $this->createControls();
        return $this->controls;
    }
    
    /**
     * Builds a form items to the control objects ready to use.
     * 
     * @since 1.0.0
     * @return void
     */
    public function createControls( $holder = null ) {
        $items = ( $holder == null ) ? $this->getItems() : $holder['items'];

        foreach($items as $item ) {
            
            // if a current item is a control holder
            if ( $this->isControlHolder( $item ) ) {
                
                $this->createControls( $item );
            
            // if a current item is an input control
            } elseif ( $this->isControl( $item ) ) {

                $this->controls[] = $this->createControl( $item );
                
            // if a current item is an input control
            } elseif ( $this->isCustomElement( $item ) ) {
                
                // nothing
                
            // otherwise, show the error
            } else {
                print_r($item);
                die( '[ERROR] Invalid item.' ); 
            }
        }

        return $this->controls;
    }
    
    /**
     * Create an element.
     * 
     * @since 1.0.0
     * @param type $item Item data.
     * @return FactoryFrom_FormElement|null A form element.
     */
    public function createElement( $item ) {
 
        if ( $this->isControl( $item )) {
            return $this->createControl( $item );
        } elseif ( $this->isControlHolder( $item ) ) {
            return $this->createHolder( $item );
        } elseif ( $this->isCustomElement( $item ) ) {
            return $this->createCustomElement( $item );  
        } else {
            printf( '[ERROR] The element with the type <strong>%s</strong> was not found.', $item['type'] );
            exit;
        }
    }
    
    /**
     * Creates a set of elements.
     * 
     * @since 1.0.0
     * @param mixed[] $item Data of items.
     * @return FactoryFrom_FormElement[] Created elements.
     */
    public function createElements( $items = array() ) {
        $objects = array();
        foreach( $items as $item ) $objects[] = $this->createElement($item);
        return $objects;
    }
    
    /**
     * Create a control.
     * 
     * @since 1.0.0
     * @param type $item Item data.
     * @return FactoryFrom_Control A control object.
     */
    public function createControl( $item ) {
        $object = null;
        
        if ( is_array( $item ) ) {

            $controlData = self::$_registeredControls[$item['type']];
            require_once ($controlData['include']);

            $options = $item;
            $options['scope'] = $this->scope;
            
            $theme = isset( $options['theme'] ) ? $options['theme'] : null;
            if ( !$theme ) $theme = $this->controlTheme;

            $options['theme'] = $theme;
            $object = new $controlData['class']( $options, $this );   

        } elseif ( gettype( $item ) == 'object' ) {
            $object = $item;
        } else {
            print_r($item);
            die( '[ERROR] Invalid input control.' ); 
        }

        $object->setProvider( $this->provider );
        return $object;
    }
    
    /**
     * Create a control holder.
     * 
     * @since 1.0.0
     * @param type $item Item data.
     * @return FactoryForms300_ControlHolder A control holder object.
     */
    public function createHolder( $item ) {
        $object = null;
        
        if ( is_array( $item ) ) {

            $holderData = self::$_registeredHolders[$item['type']];
            require_once ($holderData['include']);

            $object = new $holderData['class']( $item, $this );   

        } elseif ( gettype( $item ) == 'object' ) {
            $object = $item;
        } else {
            print_r($item);
            die( '[ERROR] Invalid control holder.' ); 
        }
        
        return $object;
    }
    
    /**
     * Create a custom form element.
     * 
     * @since 1.0.0
     * @param type $item Item data.
     * @return FactoryForms300_FormElement A custom form element object.
     */
    public function createCustomElement( $item ) {
        $object = null;
        
        if ( is_array( $item ) ) {

            $data = self::$_registeredCustomElements[$item['type']];
            require_once ($data['include']);

            $options = $item;
            $object = new $data['class']( $options, $this );   

        } elseif ( gettype( $item ) == 'object' ) {
            $object = $item;
        } else {
            print_r($item);
            die( '[ERROR] Invalid custom form element.' ); 
        }
        
        return $object;
    }    
    
    /**
     * Renders a form.
     * 
     * @since 1.0.0
     * @param mixed[] $options Options for a form layout.
     * @return void
     */
    public function html( $options = array() ) {

        if ( !isset( self::$_formLayouts[$this->formLayout] ) )
            die( sprintf( '[ERROR] The form layout %s was not found.', $this->formLayout ) ); 
        
        // include a render code
        $layoutData = self::$_formLayouts[$this->formLayout];
        require_once ($layoutData['include']);

        $this->connectAssets();
        
        $layout = new $layoutData['class']( $options, $this );
        $this->layout = $layout;
        $this->layout->render();
    }
    
    /**
     * Connects assets (css and js).
     * 
     * @since 1.0.0
     * @param mixed[] $options Options for a form layout.
     * @return void
     */ 
    private function connectAssets() {
        
        $this->connectAssetsForItems();
        $layoutData = self::$_formLayouts[$this->formLayout];
        
        if ( $layoutData['name'] == 'default') {
            if ( isset( $layoutData['style'] ) )
                wp_enqueue_style('factory-form-000-default-layout', $layoutData['style']);  
            if ( isset( $layoutData['script'] ) )
                wp_enqueue_script('factory-form-000-default-layout-', $layoutData['script']);
        } else {
            if ( isset( $layoutData['style'] ) )
                wp_enqueue_style('factory-form-layout-' . $layoutData['name'], $layoutData['style']); 
            if ( isset( $layoutData['script'] ) )
                wp_enqueue_script('factory-form-layout-' . $layoutData['name'], $layoutData['script']);  
        }
        
        if ( !$this->controlTheme ) return;
        self::connectThemeAssets( $this->controlTheme );
    }
    
    
    /**
     * Connects scripts and styles of form items.
     * 
     * @since 1.0.0
     * @param mixed[] $items Items for which it's nessesary to connect scripts and styles.
     * @return void
     */
    public static function connectAssetsForItems( $items = array() ) {
        foreach($items as $item) self::connectAssetsForItem( $item );
    }
    
    /**
     * Connects scripts and styles of form item.
     * 
     * @since 1.0.0
     * @param mixed[] $item Item for which it's nessesary to connect scripts and styles.
     * @return void
     */
    public static function connectAssetsForItem( $item ) {
        if ( !is_array( $item ) ) continue;

        $type = $item['type'];

        $haystack = array();
        if ( self::isControl($type) ) $haystack = self::$_registerControls;
        elseif ( self::isControlHolder($type) ) $haystack = self::$_registeredHolders;

        if ( isset( $haystack[$type] ) ) {
             if ( isset( $haystack[$type]['style'] ) ) {
                 $style = $haystack[$type]['style'];
                 if ( !wp_style_is( $style ) ) 
                     wp_enqueue_style('factory-form-control-' . $type, $style); 
             }
             if ( isset( $haystack[$type]['script'] ) ) {
                 $script = $haystack[$type]['script'];
                 if ( !wp_script_is( $script ) ) 
                     wp_enqueue_script('factory-form-control-' . $type, $script, array('jquery')); 
             }
             
             if ( isset( $item['theme'] ) ) {
                 self::connectThemeAssets( $item['theme'] );
             }
        }

        if ( isset($item['items'] ))
            self::connectAssetsForItem( $item['items'] );
    }
    
    /**
     * Connects theme assets and styles.
     * 
     * @since 1.0.0
     * @param string $theme A theme name to connect.
     * @return void
     */
    public static function connectThemeAssets( $theme ) {
        
        if ( !isset( self::$_controlThemes[$theme]) ) {
            die( sprintf( '[ERROR] Control theme <strong>%s</strong> was not found.', $theme ));
        }
        
        $themeData = self::$_controlThemes[$theme];

        $styleUrl = str_replace('{temper}', self::$temper, $themeData['style']);
        $scriptUrl = str_replace('{temper}', self::$temper, $themeData['script']);
        
        if ( isset( $themeData['style'] ) )
            wp_enqueue_style('factory-control-theme-' . $themeData['name'], $styleUrl); 

        if ( isset( $themeData['script'] ) )
            wp_enqueue_script('factory-control-theme-' . $themeData['name'], $scriptUrl); 
    }
    
    /**
     * Saves form data by using a specified value provider.
     * 
     * @since 1.0.0
     * @return void
     */
    public function save() {
        if ( !$this->provider ) return;
        
        $controls = $this->getControls();    

        foreach($controls as $control) {
            
            $name = $control->getName();

            $value = $control->getSubmitValue($name);
            $this->provider->setValue($name, $value);
        }
        
        $this->provider->saveChanges();
    }

    /**
     * Returns true if a given item is an input control item.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isControl( $item ) {
        return isset( self::$_registeredControls[ $item['type'] ] );
    }
    
    /**
     * Returns true if a given item is an control holder item.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isControlHolder( $item ) {
        return isset( self::$_registeredHolders[ $item['type'] ] );
    } 
    
    /**
     * Returns true if a given item is html markup.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isCustomElement( $item ) {
        return isset( self::$_registeredCustomElements[ $item['type'] ] );
    }
}