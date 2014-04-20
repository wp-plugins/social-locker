( function( $ ){
    
    // COLOR CONTROL CLASS DEFINITION
    // ================================
    
    var ColorControl = function (el) {
        this.$element = $(el);

        this.$picker = this.$element.find('.factory-color-hex');
        this.$preview = this.$element.find('.factory-preview');
        this.$background = this.$element.find('.factory-background');

        this.init();
    };
    
    ColorControl.prototype.init = function() {
        var self = this;

        var irisOptions = {
            width: 216,
            palettes: ['#16a086', '#27ae61', '#2a80b9', '#8f44ad', '#2d3e50', '#f49c14', '#c1392b', '#bec3c7'],
            hide: true,
            change: function(event, ui) {  
                self.$background.css({ background: ui.color.toString() });
                self.$picker.trigger('keyup');
            }
        };
        
        var picketTarget = this.$element.data('picker-target');
        if ( picketTarget ) irisOptions.target = $(picketTarget);
        
        this.$picker.factoryBootstrap308_iris(irisOptions); 
        this.$picker.off('focus');
        
        $(document).on("click.color.factory", function(){
           self.$picker.factoryBootstrap308_iris("hide");  
        });
        
        this.$picker.add(this.$background).on("click.color.factory", function(e){
           e.stopPropagation();
           self.$picker.factoryBootstrap308_iris("show");  
        });  
    };

    ColorControl.prototype.togglePicker = function() {

         if( this.$element.hasClass('factory-picker-active') ) {            
             this.hidePicker();
         } else {
             this.showPicker();
         }
    };
    
    ColorControl.prototype.hidePicker = function() {
        this.$element.removeClass('factory-picker-active');
        this.$picker.factoryBootstrap308_iris( 'hide' );
    }; 
    
    ColorControl.prototype.showPicker = function() {
        this.$element.addClass('factory-picker-active');
        this.$picker.factoryBootstrap308_iris( 'show' );
    }; 
    
    // COLOR CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap308_colorControl = function (option) {
        return this.each(function () {
            var $this = $(this);
            var data  = $this.data('factory.color-control')
            if (!data) $this.data('factory.color-control', (data = new ColorControl(this)));
           // if (typeof option === 'string') data[option].call(data);
        });
    };

    $.fn.factoryBootstrap308_colorControl.Constructor = ColorControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-308 .factory-color").factoryBootstrap308_colorControl();
    });
    
}( jQuery ) );