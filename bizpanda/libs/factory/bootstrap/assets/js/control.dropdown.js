( function( $ ){
    
    // DROPDOWN CONTROL CLASS DEFINITION
    // ================================
    
    var DropdownControl = function (element) {
        var self = this;
        
        this.$element = $(element);
        this.way = this.$element.data('way');
        this.name = this.$element.data('name') || this.$element.attr('name');

        if ( 'buttons' === this.way ) {

            this.$result = this.$element.find(".factory-result");
            this.$hints = this.$element.find(".factory-hints");        
            this.$buttons = this.$element.find(".btn");  

            this.$buttons.click(function(){
                var value = $(this).data('value');

                self.$buttons.removeClass('active');
                $(this).addClass('active');

                self.$hints.find(".factory-hint").hide();
                self.$hints.find(".factory-hint-" + value).fadeIn();

                self.$result.val(value);
                self.$result.trigger('change');
                return false;
            });
            
        } else if ( 'ddslick' === this.way ) {

            var data = window['factory_' + this.name + "_data"];
            var $ddslick = this.$element.find(".factory-ddslick");
            
            var width = this.$element.data("width") || 300;
            var imagePosition = this.$element.data("align") || 'right';

            $(data).each(function(){
                if ( !this.imageHoverSrc ) return true;
                $('<img/>')[0].src = this.imageHoverSrc;
            });

            $ddslick.ddslick({
                data: data,
                width: width,
                imagePosition: imagePosition,
                selectText: "- select -",
                onSelected: function (data) {
                    
                    if ( data.selectedData.imageHoverSrc )
                        self.$element.find(".dd-selected-image").attr('src', data.selectedData.imageHoverSrc);

                    var $result = self.$element.find(".factory-result").val( data.selectedData.value  );
                    $result.change();
                }
            });

        } else {

            // hints
            
            this.$hints = this.$element.next();
            
            if ( this.$hints.hasClass('factory-hints') ) {
                this.$element.change(function(){
                    self.updateHints();
                    return false;
                });

                this.updateHints = function() {
                    var value = self.$element.val();
                    self.$hints.find(".factory-hint").hide();
                    self.$hints.find(".factory-hint-" + value).show();
                };

                self.updateHints();
            }
  
            // ajax
            
            this.getAjaxData = function() {
                var ajaxDataID = self.$element.data('ajax-data-id');
                return window[ajaxDataID];
            };
            
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
                    error: function( response ) {
                        
                        if ( console && console.log ) {
                            console.log( response.responseText );
                        }
                        
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

                    this.$element.append("<option>" + ajaxData.emptyList + "</li>");

                } else {

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

                var $option = $('<option />')
                        .attr('value', item.value)
                        .text(item.title)
                        .appendTo( this.$element );

                var ajaxData = self.getAjaxData();
   
                if ( ajaxData.selected && ajaxData.selected == item.value ) {
                    $option.attr('selected', 'selected');
                }
            };

            this.showError = function( text ) {
                this.clearList();
                
                var $error = $("<div class='factory-control-error'></div>")
                    .append($("<i class='fa fa-exclamation-triangle'></i>"))
                    .append( text );
            
                var ajaxData = self.getAjaxData();
                
                this.$element.append("<option>" + ajaxData.emptyList + "</li>");
                this.$element.after($error);

                this.$element.addClass('factory-has-error');
            };

            this.removeLoader = function() {
                this.$element.removeClass('factory-hidden');

                var ajaxData = self.getAjaxData();
                $( ajaxData.loader ).remove();
            }

            var ajax = this.$element.data('ajax');
            if ( ajax ) this.loadData();
        }
    };
    
    // DROPDOWN CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap329_dropdownControl = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.dropdown.control');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.dropdown.control');
                if (!data) $this.data('factory.dropdown.control', (data = new DropdownControl(this)));
            });
        }
    };

    $.fn.factoryBootstrap329_dropdownControl.Constructor = DropdownControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-329 .factory-dropdown").factoryBootstrap329_dropdownControl();
    });
    
}( jQuery ) );