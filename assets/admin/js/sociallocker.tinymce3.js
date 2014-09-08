(function() {  
    tinymce.create('tinymce.plugins.sociallocker', {  
            plugin_url: null,
            editor: null,
		
        init : function(ed, url) {  

			this.plugin_url = url;
			this.editor = ed;
        },
        createControl : function(n, cm) {  
			var self = this;
			
			if ( n == 'sociallocker' )
			{
				var selectedValue = null;
				var selectedDefaultType = 'block';
				
                var c = cm.createSplitButton('sociallocker', {
                    title : 'Split button',
                    image : self.plugin_url + '/../img/sociallocker.png',
                    onclick : function() {
                        self.insertShortCode(selectedValue, selectedDefaultType);
                    }
                });
				
                jQuery(document).ready(function($) {

                        $.ajax(ajaxurl, {
                                type: 'post',
                                dataType: 'json',
                                data: {
                                        action: 'get_sociallocker_lockers'
                                },
                                success: function(data, textStatus, jqXHR) {

                                        c.onRenderMenu.add(function(c, m) {

                                                m.add({title : 'Lockers', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                                                var menuItems = [];

                                                $.each(data, function(index, item){

                                                        var mItem = m.add({title : item.title, onclick : function(e, a) {

                                                                for(index in menuItems) {
                                                                        menuItems[index].setSelected(false);
                                                                }

                                                                mItem.setSelected(true);

                                                                selectedValue = item.id;
                                                                selectedDefaultType = item.defaultType;

                                                                self.insertShortCode(selectedValue, selectedDefaultType);
                                                        }});
                                                        menuItems.push(mItem);
                                                });
                                        });
                                }
                        });
                });

                // Return the new splitbutton instance
                return c;
			}
			
			return null;
        },  
		
		insertShortCode: function(value, defaultType) {
			var self = this;
			
			if ( !value ) {
				self.editor.selection.setContent('[sociallocker]' + self.editor.selection.getContent() + '[/sociallocker]');  
			} else {
				
				switch(defaultType)
				{
					case "block":
						self.editor.selection.setContent('[sociallocker]' + self.editor.selection.getContent() + '[/sociallocker]');
						break;
	
					default:
						self.editor.selection.setContent('[sociallocker id="' + value + '"]' +  self.editor.selection.getContent() + '[/sociallocker]');
						break;
				}			
			}
		}
    });  
    tinymce.PluginManager.add('sociallocker', tinymce.plugins.sociallocker);  
})();  