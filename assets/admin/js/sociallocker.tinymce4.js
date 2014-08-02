( function($) {
    tinymce.PluginManager.add( 'sociallocker', function( editor, url ) {
        var menuCreated = false;
        
        editor.addButton( 'sociallocker', {
            title: 'Social Locker',
            type: 'menubutton',
            icon: 'icon onp-sl-shortcode-icon',
            
            /*
             * After rendeing contol, starts to load manu items (locker shortcodes).
             */
            onpostrender: function(e) {
                if ( menuCreated ) return;
                menuCreated = true;
                
                var self = this;

                $.ajax(ajaxurl, {
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'get_sociallocker_lockers'
                    },
                    success: function(data, textStatus, jqXHR) {

                        var menu = [];

                        $.each(data, function(index, item){

                            var itemType = item.defaultType;
                            var itemValue = item.id;
                            
                            menu.push({
                                text: item.title,
                                value: itemValue,
                                onclick: function() {

                                    if ( !itemValue ) {
                                        editor.selection.setContent('[sociallocker]' + editor.selection.getContent() + '[/sociallocker]');  
                                    } else {

                                        switch(itemType)
                                        {
                                            case "block":
                                                editor.selection.setContent('[sociallocker]' + editor.selection.getContent() + '[/sociallocker]');
                                                break;

                                            default:
                                                editor.selection.setContent('[sociallocker id="' + itemValue + '"]' +  editor.selection.getContent() + '[/sociallocker]');
                                                break;
                                        }			
                                    }
                                }
                            });
                        });
                        
                        self.settings.menu = menu;
                    }
                });
            }
        });
    });
} )(jQuery);