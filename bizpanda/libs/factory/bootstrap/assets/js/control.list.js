( function( $ ){
    
    var list = function (element) {
        var self = this;
        
        this.$element = $(element);
        this.way = this.$element.data('way');
        this.name = this.$element.data('name');
        
        // curently the control supports only checklists
        if ( 'checklist' !== this.way ) return;

        this.getAjaxData = function() {
            var ajaxDataID = self.$element.data('ajax-data-id');
            return window[ajaxDataID];
        }

        this.loadData = function() {
            
            var ajaxData = self.getAjaxData();
            
            var req = $.ajax({
                url: ajaxData.url,
                data: ajaxData.data,
                dataType: 'json',
                
                success: function( response  ){
                    if ( response.error ) return self.showError( response.error  );
                    self.fill( response.items );
                },
                error: function() {
                    self.showError('Unexpected error occurred during the ajax request.');
                },
                complete: function() {
                    self.removeLoader();
                }
            });
        };
        
        this.fill = function( items ) {
            this.clearList();
            
            var ajaxData = self.getAjaxData();
            
            if ( !items || !items.length ) {
                
                this.$element.addClass('factory-empty');
                this.$element.append("<li>" + ajaxData.emptyList + "</li>");
                
            } else {
                
                this.$element.removeClass('factory-empty');
                
                for( var index in items ) {
                    var item = items[index];
                    self.addListItem( item );
                }
            }
        };
        
        this.clearList = function() {
            this.$element.html("");
        };
        
        this.addListItem = function( item ) {
            
            var $li = $('<li>');
            
            var $label = $('<label>')
                    .attr('for', 'factory-checklist-' + self.name + '-' + item.value)
                    .appendTo($li);
            
            var $checkboxSpan = $('<span>')
                    .appendTo($label);
            
            var $checkbox = $('<input />')
                    .attr('type', 'checkbox') 
                    .attr('name', self.name + "[]")
                    .val(item.value)
                    .attr('id', 'factory-checklist-' + self.name + '-' + item.value)
                    .appendTo($checkboxSpan);
            

            var $title = $('<span>' + item.title + '</span>')
                    .appendTo($label);
            
            
            var ajaxData = self.getAjaxData();
            if ( ajaxData.selected.length && $.inArray( item.value, ajaxData.selected ) >= 0 ) {
                $checkbox.attr('checked', 'checked');
            }
            
            this.$element.append($li);
        };
        
        this.showError = function( text ) {
            
            this.$element.html("")
                .append($("<i class='fa fa-exclamation-triangle'></i>"))
                .append( text );
        
            this.$element.addClass('factory-list-error');
        };
        
        this.removeLoader = function() {
            this.$element.removeClass('factory-hidden');

            var ajaxData = self.getAjaxData();
            $( ajaxData.loader ).remove();
        }
        
        var ajax = this.$element.data('ajax');
        if ( ajax ) this.loadData();
    };
    
    $.fn.factoryBootstrap329_listControl = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.list.control');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.list.control');
                if (!data) $this.data('factory.list.control', (data = new list(this)));
            });
        }
    };

    $.fn.factoryBootstrap329_listControl.Constructor = list;

    $(function(){
        $(".factory-bootstrap-329 .factory-list").factoryBootstrap329_listControl();
    });
    
}( jQuery ) );