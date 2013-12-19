<?php

class FactoryFormFR110 {
    
    public static $controls = array();

    public static function register($name, $className) {
        self::$controls[$name] = $className;
    }

    /**
     * Current factory.
     * @var PFactory 
     */
    public $factory;
    
    /**
     * A laoder of the current factory.
     * @var PFactoryLoader 
     */
    private $loader;
    
    /**
     * Value provider of the current form used to get and set values.
     * @var IPFactoryValueProvider 
     */
    private $valueProvider;
    
    /**
     * Scope used as a prefix for controls name.
     * @var string
     */
    public $scope;
    
    /**
     * Form name that is used to call filters.
     * @var type 
     */
    public $name = 'default';
    
    /**
     * Items that were added on a form.
     * @var mixed[] 
     */
    public $items = array();
    
    /**
     * Full set of controls available after building.
     * @var array 
     */
    private $formControls = array();
    
    public function __construct( FactoryFR110Plugin $plugin, $valueProvider = null ) {
        
        $this->plugin = $plugin;
        $this->valueProvider = $valueProvider ? $valueProvider : new FactoryFR110FakeValueProvider();     
    }
    
    /**
     * Adds items to on a form.
     * @param array $array
     */
    public function add( $array ) {

        if ( (bool)count(array_filter(array_keys($array), 'is_string')) ) {
            $this->items[] = $array;
        } else {
            $this->items = array_merge($this->items, $array);
        }
    }
    
    public function getControls() {
        if ( !empty($this->formControls) ) return $this->formControls;
        return $this->build();
    }
    
    /**
     * Creates control by using given data.
     */
    private function build() {

        // loaded form controls that can be used
        $controlsStack = self::$controls;

        // applies filters to the form params before building
        $this->items = apply_filters('factory_fr110_form', $this->items, $this->scope, $this->name);    
        $this->items = apply_filters('factory_form_fr110_' . $this->name, $this->items, $this->scope);
                
        foreach($this->items as $index => $item) {
            if ( !$this->isControl($item) ) continue;
            
            $object = null;
            $type = $item['type'];
            
            if ( gettype( $type ) == 'string' && isset( $controlsStack[$type] ) ) {
                $object = new $controlsStack[$type]( $this->plugin );                             
            } elseif ( gettype($item['type']) == 'object' ) {
                $object = $item['type'];
            }
                            
            if (  !$object ) {
                print_r($item);
                die( sprintf('[ERROR] Invalid control type for the field "%s".', $item['name']));
            }
            
            $item['default'] = isset($item['default']) ? $item['default'] : null;
            $item['scope'] = $this->scope;
            
            $item['fullname'] = ( !empty($this->scope) ) 
                ? $this->scope . '_' . $item['name']
                : $item['name'];

            $object->setup($item, $this->valueProvider);
            $item['control'] = $object;
            
            $this->formControls[] = $object;
            $this->items[$index] = $object;
        }

        return $this->formControls;
    }
    
    /**
     * Renders a form.
     */
    public function render() {
    
        // checks whether there are any defined items in a form
        if (count($this->items) == 0) {
            throw new ErrorException("The form items are not defined. The form is empty.");
        }
        
        $this->build();
        
        echo '<div class="pi-metabox wpbootstrap">';
        echo '<div class="form-horizontal">';
        
        $root = new FactoryFormFR110Item(array(), null);
        $currentLevel = $root;
        
        $currentTab = null;
        
        // 1. Builds hierarchical tree of items
        
        foreach($this->items as $item) {
            
            // an item is an array
            if (is_array($item)){
 
                switch( $item['type'] ) {

                   // tab
                   case 'tab':

                       $levelToUse = $currentTab == null ? $root : $currentTab->parent;
                       $tab = new FactoryFormFR110Tab( $item, $levelToUse );

                       $currentLevel = $tab;
                       $currentTab = $tab;

                   break;

                   // tab item
                   case 'tab-item':

                       $tabItem = new FactoryFormFR110TabItem( $item, $currentTab );
                       $currentLevel = $tabItem;

                   break;

                   // group
                   case 'group':

                       $group = new FactoryFormFR110Group( $item, $currentLevel );
                       $currentLevel = $group;
                       
                    break;
                
                   // group
                   case 'collapsed':

                       $collapsed = new FactoryFormFR110Collapsed( $item, $currentLevel );
                       $currentLevel = $collapsed;
                       
                   break;  
               } 
    
            // processes an input control
            } elseif (is_object($item)) {
                
                $currentLevel->add( $item );

            // an item is ending
            } else {
                
                switch($item) {
                    case 'tab-end':           
                        $currentLevel = $currentTab->parent;
                    break;
                }
            }
        }
        
        // 2. Renders tree of items

        foreach($root->items as $item) {
            $this->renderItem($item);  
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Renders a given item.
     * @param FactoryFormItem $item
     */
    private function renderItem( $item ) {
        
        switch($item->itemType) {

            case 'tab':
                $this->renderTab($item);
                break;
            
            case 'tab-item':
                $this->renderTabItem($item);
                break;
            
            case 'group':
                $this->renderGroup($item);
                break;
            
             case 'collapsed':
                 
                $this->renderCollapsed($item);
                break;           
            
            case 'control':
                $this->renderControl($item);
                break;         
        }
    }
    
    /**
     * Renders a tab item.
     * @param FactoryFormTab $item
     */
    private function renderTab( $item ) {
        
        $firstTab = 'active';
        $verticalClass = $item->tabType == 'vertical' ? 'pi-vertical-tabs' : '';
        
        $aseetUrl = $this->plugin->pluginUrl . '/assets/';

        ?>
        <div class="pi-tabs <?php echo $verticalClass ?>">
            
            <?php if ( !empty($item->hint)) { ?>
                <div class="pi-tab-hint"><?php echo $item->hint ?></div>
            <?php } ?>
            
            <div class='pi-tab-headers-wrap'>
                <ul class="nav nav-tabs">
                <?php foreach( $item->items as $tabItem ) { 
                    $hasIcon = !empty($tabItem->tabIcon);
                    $tabIconClass = '';
                    if ( $hasIcon ) { 
                        $tabIconClass = ' pi-tab-with-icon';
                        $tabIcon = str_replace('~/', $aseetUrl, $tabItem->tabIcon);
                    }
                    ?>
                    <?php if ($hasIcon) { ?>
                        <style>
                            .pi-tab-title-<?php echo $tabItem->name ?> a {
                                background-image: url("<?php echo $tabIcon ?>");
                            }
                        </style>
                    <?php } ?>
                    <li class='pi-tab-title pi-tab-title-<?php echo $tabItem->name ?> <?php echo $firstTab . $tabIconClass ?> ' data-tab-id="<?php echo $tabItem->name ?>">
                        <a href="#tab-<?php echo $tabItem->name ?>" data-toggle="tab">
                            <?php echo $tabItem->title ?>
                        </a>
                    </li>
                <?php $firstTab = ''; } ?>
                </ul>
            </div>
            <?php      

            ?>
            <div class='tab-content pi-tab-contens-wrap <?php echo $verticalClass ?>'>
                <?php 
                $firstTab = "active";                        
                foreach( $item->items as $tabItem ) { ?>
                    <div id="tab-<?php echo $tabItem->name ?>" class="tab-pane <?php echo $firstTab ?> pi-tab-content-wrap">
                    <?php $this->renderTabItem($tabItem); ?>
                    </div>
                <?php 
                $firstTab = '';
                } ?>
            </div>
            
        </div>
        <?php
    } 
    
    /**
     * Renders a tab item
     * @param FactoryFormTabItem $tabItem
     */
    private function renderTabItem( $tabItem ) {
        
        foreach($tabItem->items as $item) {
            $this->renderItem($item);  
        }   
    }
    
    /**
     * Renders a group of items
     * @param FactoryFormGroup $groupItem
     */
    private function renderGroup( $item ) {
        ?>
        <fieldset class='fy-group' id='group-<?php echo $item->name ?>'>
            <?php if ( $item->title ) { ?>
            <legend class='fy-group-legend'>
                <p class='fy-group-title'><?php echo $item->title ?></p>
                <?php if ( $item->hint ) { ?>
                <p class='fy-group-hint'><?php echo $item->hint ?></p>
                <?php } ?>
            </legend>
            <?php } ?>
            <?php
                foreach($item->items as $sub) {
                    $this->renderItem($sub);  
                } 
            ?>
        </fieldset>
        <?php
    }
    
    /**
     * Renders a collapsed group.
     * @param FactoryFormCollapsed $groupItem
     */
    private function renderCollapsed( $item ) {
        $id = rand(100000, 999999);
        ?>
        <div class="fy-collapsed-group">
            <?php if ( $item->count ) { ?>
            <a href="#collapsed-<?php echo $id ?>" class="fy-collapsed-show"><?php echo $item->title ?> (<?php echo $item->count ?>)</a>
            <?php } else { ?>
            <a href="#collapsed-<?php echo $id ?>" class="fy-collapsed-show"><?php echo $item->title ?></a>
            <?php } ?>
            <div class='fy-collapsed-content' id="collapsed-<?php echo $id ?>" style="display: none;">
                <a href="#collapsed-<?php echo $id ?>" class='fy-collapsed-hide'>hide extra options</a>
                <?php
                    foreach($item->items as $sub) {
                        $this->renderItem($sub);  
                    } 
                ?>
            </div>
        </div>
        <?php 
    }

     /**
     * Render control item
     * @param FactoryFormTabControl $controlItem
     */
    private function renderControl( $controlItem ) {
        $controlItem->render();
    }
    
    /**
     * Returns true if a given item is a control item
     * @param type $item
     */
    private function isControl($item) {
        
        if (gettype($item) === 'string' ) return false;
        if ( in_array( $item['type'], array( 'tab', 'tab-item', 'group', 'collapsed' ) ) ) return false;
        return true;
    }
}

add_action('admin_enqueue_scripts', 'factory_form_fr110_admin_scripts2');
function factory_form_fr110_admin_scripts2() {
    wp_enqueue_style('forms-style', FACTORY_FORM_FR110_URL . '/assets/css/forms.css'); 
    wp_enqueue_style('forms-controls', FACTORY_FORM_FR110_URL . '/assets/css/controls.css'); 
    wp_enqueue_script('forms-controls', FACTORY_FORM_FR110_URL . '/assets/js/controls.js'); 
}