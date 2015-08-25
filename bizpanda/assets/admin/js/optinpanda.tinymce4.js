( function($) {
    tinymce.PluginManager.add( 'optinpanda', function( editor, url ) {
        var menuCreated = false;
        
        var menu = [];
        
        editor.addButton( 'optinpanda', {
            title: bizpanda_shortcode_title,
            type: 'menubutton',
            icon: 'icon onp-sl-shortcode-icon',
            menu: menu,
            
            /*
             * After rendeing contol, starts to load manu items (locker shortcodes).
             */
            onpostrender: function(e) {
                if ( menuCreated ) return;
                menuCreated = true;
                
                var self = this;

                var req = $.ajax(ajaxurl, {
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'get_opanda_lockers'
                    },
                    success: function(data, textStatus, jqXHR) {

                        $.each(data, function(index, item){

                            var shortcodeStart = '[' + item.shortcode + ']';
                            if ( item.id && !item.isDefault ) {
                                shortcodeStart = '[' + item.shortcode + ' id=' + item.id + ']';
                            }
                            
                            var shortcodeEnd = '[/' + item.shortcode + ']'; 
                            
                            menu.push({
                                text: item.title,
                                value: item.id,
                                onclick: function() {
                                    editor.selection.setContent(shortcodeStart + editor.selection.getContent() + shortcodeEnd);
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