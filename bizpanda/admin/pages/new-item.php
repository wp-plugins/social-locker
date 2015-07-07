<?php
/**
 * Shows the dialog to select a type of new opt-in element.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

class OPanda_NewPandaItemPage extends OPanda_AdminPage  {
 
    public function __construct( $plugin  ) {    
        
        $this->menuTitle = __('+ New Locker', 'bizpanda');
        $this->menuPostType = OPANDA_POST_TYPE;
        $this->id = "new-item";
    
        parent::__construct( $plugin );
    }
        
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        
        $this->styles->request( array( 
            'bootstrap.core'
            ), 'bootstrap' ); 
        
        $this->scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/new-item.010000.js');  
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/new-item.010000.css');
    }
    
    /**
     * Shows the screen.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        $types = OPanda_Items::getAvailable();
        
        // checkes extra items which are not installed yet
 
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
            $suggestions = OPanda_Plugins::getSuggestions();
        

        
        ?>

        <div class="wrap factory-fontawesome-320">

            <div class="opanda-items ">
                
                <h2><?php _e('Creating New Item', 'bizpanda') ?></h2>
                <p style="margin-top: 0px;"><?php _e('Choose which items you would like to create.', 'bizpanda') ?></p>

                <?php foreach( $types as $name => $type ) { ?>
                <div class="postbox opanda-item opanda-item-<?php echo $type['type'] ?>">

                    <h4 class="opanda-title">
                        <?php echo $type['title'] ?>
                    </h4>
                    <div class="opanda-description">
                        <?php echo $type['description'] ?>
                    </div>
                    <div class="opanda-buttons">

                        <a href="<?php echo admin_url('post-new.php?post_type=opanda-item&opanda_item=' . $name); ?>" class="button button-large opanda-create">
                            <i class="fa fa-plus"></i><span><?php _e('Create Item', 'bizpanda') ?></span>
                        </a>

                        <?php if ( isset( $type['help'] )) { ?>
                        <a href="<?php echo $type['help'] ?>" class="button button-large opanda-help opanda-right" title="<?php _e('Click here to learn more', 'bizpanda') ?>">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <?php } ?>
                    </div>
                </div>

                <?php } ?>
            </div>
            
            <?php if ( !empty( $suggestions ) ) { ?>
            
            <div class="opanda-separator"></div>
            
            <div class="opanda-extra-items">
                <div class="opanda-inner-wrap">
                    
                    <h2>
                        <?php _e('More Marketing Tools To Grow Your Business', 'bizpanda') ?>
                    </h2>
                    <p style="margin-top: 0px;">
                        <?php _e('Check out other plugins which add more features to your lockers.', 'bizpanda') ?>
                    </p>
                    
                    <?php foreach( $suggestions as $suggestion ) { 
                        
                        $url = $suggestion['url'];
                        
                        if ( false === strpos( $url, 'utm_source') ) {
                            
                            if ( BizPanda::isSinglePlugin() ) {
                                
                                $plugin = BizPanda::getPlugin();
                                
                                $args = array(
                                    'utm_source'            => 'plugin-' . $plugin->options['name'],
                                    'utm_medium'            => ( $plugin->license && isset( $plugin->license->data['Category'] ) ) 
                                                                ? ( $plugin->license->data['Category'] . '-version' )
                                                                : 'unknown-version',
                                    'utm_campaign'          => 'suggestions',
                                    'tracker'               => isset( $plugin->options['tracker'] ) ? $plugin->options['tracker'] : null
                                );

                                $url = add_query_arg( $args, $url );   
                                
                            } else {

                                $url = add_query_arg( array(
                                    'utm_source' => 'plugin-bizpanda',
                                    'utm_medium' => 'mixed-versions',
                                    'utm_campaign' => 'suggestions',
                                    'utm_term' => implode(',', BizPanda::getPluginNames( true ) )
                                ), $url );   
                            }
                        }  
                        ?>
                    
                        <div class="postbox opanda-item opanda-item-<?php echo $suggestion['type'] ?>">
                            <div class="opanda-item-cover"></div>
                            
                            <i class="fa fa-plus-circle opanda-plus-background"></i>
                            
                            <h4 class="opanda-title">
                                <?php echo $suggestion['title'] ?>
                            </h4>
                            <div class="opanda-description">
                                <?php echo $suggestion['description'] ?>
                            </div>
                            <div class="opanda-buttons">
                                <a href='<?php echo $url ?>' class="button button-large" title="<?php _e('Click here to learn more', 'bizpanda') ?>">
                                    <i class="fa fa-external-link"></i><span>Learn More</span>
                                </a>
                            </div>
                        </div>
                    
                    <?php } ?>

                    <img class="opanda-arrow" src='<?php echo OPANDA_BIZPANDA_URL . '/assets/admin/img/new-item-arrow.png' ?>' />
                    
                </div>
            </div>
            
            <?php } ?>
        </div>
        <?php
    }
}

FactoryPages321::register($bizpanda, 'OPanda_NewPandaItemPage');