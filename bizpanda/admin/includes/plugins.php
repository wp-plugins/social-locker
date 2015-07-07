<?php

class OPanda_Plugins {
    
    public static function getAll() {
        $items = array();

        $items[] = array(
            'name' => 'optinpanda',
            'type' => 'free',
            'title' => __('Opt-In Panda', 'bizpanda'),
            'description' => __('<p>Get more email subscribers the most organic way without tiresome popups.</p><p>Opt-In Panda locks a portion of content on a webpage behind an attractive opt-in form.</p>', 'opanda'),
            'url' => 'https://wordpress.org/plugins/opt-in-panda/',
            'tags' => array('social', 'subscribers'),
            'pluginName' => 'optinpanda'
        );
        
        $items[] = array(
            'name' => 'optinpanda',
            'type' => 'premium',
            'title' => __('Opt-In Panda', 'bizpanda'),
            'description' => __('<p>Get more email subscribers the most organic way without tiresome popups.</p><p>Also extends the Sign-In Locker by adding the subscription features.</p>', 'bizpanda'),
            'url' => 'http://api.byonepress.com/public/1.0/get/?product=optinpanda',
            'tags' => array('social', 'subscribers'),
            'pluginName' => 'optinpanda'
        );

        $items[] = array(
            'name' => 'sociallocker',
            'type' => 'free',
            'title' => __('Social Locker (Plugin)', 'bizpanda'),
            'description' => __('<p>Helps to attract social traffic and improve spreading your content in social networks.</p>', 'bizpanda'),
            'url' => 'https://wordpress.org/plugins/social-locker/',
            'tags' => array('social', 'subscribers', 'sociallocker-ads'),
            'pluginName' => 'sociallocker-next'
        );
        
        $items[] = array(
            'name' => 'sociallocker',
            'type' => 'premium',
            'title' => __('Social Locker', 'bizpanda'),
            'description' => __('<p>Helps to attract social traffic and improve spreading your content in social networks.</p><p>Also extends the Sign-In Locker by adding social actions you can set up to be performed.</p>', 'bizpanda'),
            'upgradeToPremium' => __('<p>A premium version of the plugin Social Locker.</p><p>7 Social Buttons, 5 Beautiful Themes, Blurring Effect, Countdown Timer, Close Cross and more!</p>', 'bizpanda'),
            'url' => 'http://api.byonepress.com/public/1.0/get/?product=sociallocker-next',
            'tags' => array('social', 'subscribers', 'sociallocker-ads'),
            'pluginName' => 'sociallocker-next'
        ); 
        
        $items[] = array(
            'name' => 'mashshare',
            'type' => 'free',
            'title' => __('Mashshare', 'bizpanda'),
            'description' => __('<p>Make your site\'s share count skyrocket with this supercharged Share Buttons for WordPress!</p>', 'bizpanda'),
            'url' => 'https://mashshare.net/?ref=77&utm_source=sociallocker&utm_medium=plugin&utm_campaign=sociallocker',
            'tags' => array('sociallocker-ads'),
            'pluginName' => 'mashshare'
        );        
        
        return $items;
    }

    public static function getPremium() {
        
        $all = self::getAll();
        $premium = array();
        
        foreach( $all as $item ) {
            if ( $item['type'] !== 'premium' ) continue;
            $premium[] = $item;
        }
        
        return $premium;
    }
    
    public static function getFree() {
        
        $all = self::getAll();
        $free = array();
        
        foreach( $all as $item ) {
            if ( $item['type'] !== 'free' ) continue;
            $free[] = $item;
        }
        
        return $free;
    }
    
    public static function getSuggestions() {
        
        $added = array();
        $existingTags = array();
        
        $suggestions = array();
        
        // suggests premium version of free plugins
        
        $plugins = BizPanda::getInstalledPlugins();
        $hasPremium = false;
        
        foreach( $plugins as $plugin ) {
            
            if ( 'premium' === $plugin['type'] ) $hasPremium = true;
            
            $pluginInfo = self::getPluginInfo($plugin['name'], $plugin['type']);
            if ( !empty( $pluginInfo ) && isset( $pluginInfo['tags'] ) ) {
                $existingTags = array_merge( $existingTags, $pluginInfo['tags'] );
            }
            
            if ( 'free' !== $plugin['type'] ) continue;
            $pluginInfo = self::getPluginInfo($plugin['name'], 'premium');
            if ( empty( $pluginInfo ) ) continue;
            
            if ( isset( $pluginInfo['upgradeToPremium'] ) ) {
                $pluginInfo['description'] = $pluginInfo['upgradeToPremium'];
            }
            
            $suggestions[] = $pluginInfo;
            $added[] = $plugin['name'];
        }

        // adds installed plugins
        
        foreach( $plugins as $plugin ) {
            if ( in_array( $plugin['name'], $added ) ) continue;
            $added[] = $plugin['name'];
        } 

        // suggests other extending plugins 
        
        $all = self::getAll();

        foreach( $all as $item ) {
            
            if ( $hasPremium && 'premium' !== $item['type'] ) continue;
            
            if ( in_array( $item['name'], $added ) ) continue;
            if ( !isset( $item['tags'] ) ) continue;

            $intersect = array_intersect( $existingTags, $item['tags'] );
            if ( empty( $intersect ) ) continue;
            
            $suggestions[] = $item;
            $added[] = $item['name'];
        }

        $suggestions = apply_filters( 'opanda_plugins_suggestions', $suggestions );
        return $suggestions;
    }

    public static function getPluginInfo( $pluginName, $type = null ) {
        $all = self::getAll();
        
        foreach( $all as $item ) {
            if ( $item['name'] !== $pluginName  ) continue;
            if ( !empty( $type ) && $item['type'] !== $type  ) continue;
            return $item;
        }
        
        return false;
    }
    
    public static function getFreeInfo( $pluginName ) {
        return self::getPluginInfo( $pluginName, 'free' );
    }
    
    public static function getPremiumInfo( $pluginName ) {
        return self::getPluginInfo( $pluginName, 'premium' );
    }
    
    public static function getUrl( $pluginName, $type = null, $campaing = null ) {
        $pluginInfo = self::getPluginInfo( $pluginName, $type );
        if ( empty( $pluginInfo ) ) return $pluginInfo;
        
        $url = $pluginInfo['url'];
        if ( empty( $campaing ) ) return $url;
        
        if ( false === strpos( $url, 'utm_source') ) {

            if ( BizPanda::isSinglePlugin() ) {

                $plugin = BizPanda::getPlugin();

                $args = array(
                    'utm_source'    => 'plugin-' . $plugin->options['name'],
                    'utm_medium'    => ( $plugin->license && isset( $plugin->license->data['Category'] ) ) 
                                                ? ( $plugin->license->data['Category'] . '-version' )
                                                : 'unknown-version',
                    'utm_campaign'  => $campaing,
                    'tracker'       => isset( $plugin->options['tracker'] ) ? $plugin->options['tracker'] : null
                );

                $url = add_query_arg( $args, $url );   

            } else {

                $url = add_query_arg( array(
                    'utm_source' => 'plugin-bizpanda',
                    'utm_medium' => 'mixed-versions',
                    'utm_campaign' =>  $campaing,
                    'utm_term' => implode(',', BizPanda::getPluginNames( true ) )
                ), $url );   
            }
        }
        
        return $url;
    }  

    public static function getPremiumUrl( $pluginName, $campaing = null ) {
        return self::getUrl( $pluginName, 'premium', $campaing );
    }

    public static function getFreeUrl( $pluginName, $campaing = null ) {
        return self::getUrl( $pluginName, 'free', $campaing );
    }   
}