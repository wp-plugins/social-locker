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

// creating a license manager for each plugin created via the factory
add_action('factory_forms_328_plugin_created', 'factory_forms_328_plugin_created');
function factory_forms_328_plugin_created( $plugin ) {
    $plugin->forms = new FactoryForms328_Manager( $plugin );
}

class FactoryForms328_Manager {
    
    // ----------------------------------------------------
    // Static fields and methods
    // ----------------------------------------------------
    
    /**
     * This array contains data to use a respective control.
     * 
     * @since 1.0.0
     * @var array 
     */
    public static $registeredControls = array();

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
    public function registerControl( $item ) {
        self::$registeredControls[$item['type']] = $item;
        require_once $item['include'];
    }
    
    /**
     * Registers a set of new controls.
     * 
     * @see FactoryForms328_Form::registerControl()
     * 
     * @since 1.0.0
     * @return void
     */
    public function registerControls( $data ) {
        foreach($data as $item) $this->registerControl($item);
    }  
    
    /**
     * This array contains holder data to use a respective control holder.
     * 
     * @since 1.0.0
     * @var array 
     */
    public static $registeredHolders = array();

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
    public function registerHolder( $item ) {
        self::$registeredHolders[$item['type']] = $item;
        require_once $item['include'];
    }

    /**
     * Registers a set of new holder controls.
     * 
     * @see FactoryForms328_Form::registerHolder()
     * 
     * @since 1.0.0
     * @return void
     */
    public function registerHolders( $data ) {
        foreach($data as $item) $this->registerHolder( $item );
    }  
    
    /**
     * This array contains custom form element data to use a respective elements.
     * 
     * @since 1.0.0
     * @var array 
     */
    public static $registeredCustomElements = array();

    /**
     * Registers a new custom form element.
     * 
     * @since 1.0.0
     * @return void
     */
    public function registerCustomElement( $item ) {
        self::$registeredCustomElements[$item['type']] = $item;
        require_once $item['include'];
    }

    /**
     * Registers a set of new custom form elements.
     * 
     * @see FactoryForms328_Form::registerCustomElement()
     * 
     * @since 1.0.0
     * @return void
     */
    public function registerCustomElements( $data ) {
        foreach($data as $item) $this->registerCustomElement( $item );
    }  
        
    /**
     * Contains a set of layouts registered for forms.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    public static $formLayouts = array();
    
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
    public function registerFormLayout( $data ) {
        self::$formLayouts[$data['name']] = $data;
    }
 
    /**
     * Extra propery that determines which UI is used in the admin panel.
     * 
     * @since 1.0.0
     * @var string 
     */
    public static $temper;
    
    /**
     * A flat to register control only once.
     * 
     * @since 3.0.7
     * @var bool
     */
    public static $controlsRegistered = false;
}

/**
 * An abstraction for forms.
 */
class FactoryForms328_Form {
    
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
     * A current form layout used to render a form.
     * 
     * @since 1.0.0
     * @var FactoryForms328_FormLayout 
     */
    public $layout;
    
    
    /**
     * Creates a new instance of a form.
     * 
     * @since 1.0.0
     * @param string $options Contains form options to setup.
     */
    public function __construct( $options = array(), $plugin = null ) {
        global $wp_version;

        // register controls once, when the first form is created
        if ( !FactoryForms328_Manager::$controlsRegistered ) {
            do_action('factory_forms_328_register_controls', $plugin);
            do_action('factory_forms_register_controls', $plugin);
            if ( !empty( $plugin ) ) do_action('factory_forms_register_controls_' . $plugin->pluginName, $plugin);
            FactoryForms328_Manager::$controlsRegistered = true;
        }

        //$isFlat = version_compare( $wp_version, '3.8', '>='  );
        $isFlat = true;
        
        $this->scope = isset( $options['scope'] ) ? $options['scope'] : null;
        $this->name = isset( $options['name'] ) ? $options['name'] : $this->name;
        
        if ( isset( $options['formLayout'] ) ) {
            $this->formLayout = $options['formLayout'];
        } else {
            $this->formLayout = 'bootstrap-3';
        }
        
        if ( !FactoryForms328_Manager::$temper ) FactoryForms328_Manager::$temper = $isFlat ? 'flat' : 'volumetric';
    }
    
    /**
     * Sets a provider for the control.
     * 
     * @since 1.0.0
     * @param IFactoryForms328_ValueProvider $provider
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
            
            if ( $this->isControlHolder( $item ) && $this->isControl( $item ) ) {
                
                $this->controls[] = $this->createControl( $item );
                $this->createControls( $item );
                
            // if a current item is a control holder
            } elseif ( $this->isControlHolder( $item ) ) {
                
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

            $controlData = FactoryForms328_Manager::$registeredControls[$item['type']];
           
            require_once ($controlData['include']);

            $options = $item;
            $options['scope'] = $this->scope;
            
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
     * @return FactoryForms328_Holder A control holder object.
     */
    public function createHolder( $item ) {
        $object = null;
        
        if ( is_array( $item ) ) {

            $holderData = FactoryForms328_Manager::$registeredHolders[$item['type']];
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
     * @return FactoryForms328_FormElement A custom form element object.
     */
    public function createCustomElement( $item ) {
        $object = null;
        
        if ( is_array( $item ) ) {

            $data = FactoryForms328_Manager::$registeredCustomElements[$item['type']];
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

        if ( !isset( FactoryForms328_Manager::$formLayouts[$this->formLayout] ) )
            die( sprintf( '[ERROR] The form layout %s was not found.', $this->formLayout ) ); 
        
        // include a render code
        $layoutData = FactoryForms328_Manager::$formLayouts[$this->formLayout];
        require_once ($layoutData['include']);

        $this->connectAssets();
        
        if ( $this->provider ) $this->provider->init();        
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
        $layoutData = FactoryForms328_Manager::$formLayouts[$this->formLayout];
        
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
        if ( !is_array( $item ) ) return;

        $type = $item['type'];

        $haystack = array();
        if ( self::isControl($type) ) $haystack = FactoryForms328_Manager::$registerControls;
        elseif ( self::isControlHolder($type) ) $haystack = FactoryForms328_Manager::$registeredHolders;

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
        }

        if ( isset($item['items'] ))
            self::connectAssetsForItem( $item['items'] );
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
            
            $values = $control->getValuesToSave();
            foreach( $values as $keyToSave => $valueToSave ) {
                $this->provider->setValue($keyToSave, $valueToSave);
            }

            $nameOption = $control->getOption('name') . '_is_active';
            $isActive = ( isset( $_POST[$nameOption] ) && intval( $_POST[$nameOption] ) == 0 ) ? 0 : 1;
            $this->provider->setValue($nameOption, $isActive );
        }

        return $this->provider->saveChanges();
    }

    /**
     * Returns true if a given item is an input control item.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isControl( $item ) {
        return isset( FactoryForms328_Manager::$registeredControls[ $item['type'] ] );
    }
    
    /**
     * Returns true if a given item is an control holder item.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isControlHolder( $item ) {
        return isset( FactoryForms328_Manager::$registeredHolders[ $item['type'] ] );
    } 
    
    /**
     * Returns true if a given item is html markup.
     * 
     * @since 1.0.0
     * @param mixed[] $item
     * @return bool
     */
    public static function isCustomElement( $item ) {
        return isset( FactoryForms328_Manager::$registeredCustomElements[ $item['type'] ] );
    }
}