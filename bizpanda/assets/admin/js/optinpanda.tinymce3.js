(function() {  
    tinymce.create('tinymce.plugins.optinpanda', {
        
        plugin_url: null,
        editor: null,
		
        init : function(ed, url) {  
            this.plugin_url = url;
            this.editor = ed;
        },
        
        createControl : function(n, cm) {
            
            var self = this;

            if ( n === 'optinpanda' )
            {	
                var c = cm.createSplitButton('optinpanda', {
                    title : 'Split button',
                    image : self.plugin_url + '/../img/opanda-shortcode-icon.png',
                    onclick : function() {
                        self.insertShortCode(null, 'connectlocker', true);
                    }
                });
				
                jQuery(document).ready(function($) {

                    $.ajax(ajaxurl, {
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'get_opanda_lockers'
                        },
                        success: function(data, textStatus, jqXHR) {

                            c.onRenderMenu.add(function(c, m) {

                                m.add({title : 'Lockers', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                                var menuItems = [];

                                $.each(data, function(index, item){

                                    var mItem = m.add({title : item.title, onclick : function(e, a) {

                                        for( index in menuItems ) {
                                            menuItems[index].setSelected(false);
                                        }

                                        mItem.setSelected(true);
                                        self.insertShortCode(item.id, item.shortcode, item.isDefault);
                                    }});
                                
                                    menuItems.push(mItem);
                                });
                            });
                        }
                    });
                });

                return c;
            }
            
            return null;
        },  
		
        insertShortCode: function(id, shortcode, isDefault) {
            var self = this;

            var shortcodeStart = '[' + shortcode + ']';
            if ( id && !isDefault ) {
                shortcodeStart = '[' + shortcode + ' id=' + id + ']';
            }

            var shortcodeEnd = '[/' + shortcode + ']'; 
                      
            self.editor.selection.setContent( shortcodeStart + editor.selection.getContent() + shortcodeEnd );
        }
    });
    
    tinymce.PluginManager.add('optinpanda', tinymce.plugins.optinpanda);  
})();