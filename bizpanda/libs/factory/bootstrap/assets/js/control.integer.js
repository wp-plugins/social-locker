( function( $ ){
    
    // INTEGER CONTROL CLASS DEFINITION
    // ================================
    
    var IntegerControl = function (el) {
        this.$element = $(el);

        if ( this.$element.hasClass('factory-has-slider') ) {
            this.createSlider();
        }
    };
    
    IntegerControl.prototype.createSlider = function() {
        var self = this;
        
        var $container = this.$element.find(".factory-slider-container");
        this.$bar = $container.find(".factory-bar");

        this.$result = $container.find(".factory-result");
        this.$visibleValue = $container.find(".factory-visible-value");

        this.units = $container.data('units');
        this.rangeStart = $container.data('range-start');
        this.rangeEnd = $container.data('range-end');              
        this.step = $container.data('step');  
        if ( !this.step ) this.step = 1;

        var value = this.$result.val();
        
        var setValue = function( value ) {
            self.setValue( value );
        }
        
        this.$bar.noUiSlider({
            start: parseInt( value ),
            range: {min: self.rangeStart, max: self.rangeEnd},
            connect: "lower",
            step: self.step
        });
        
        this.$bar.on("slide set", function(){
            self.setValue( parseInt( self.$bar.val() ) );
        });
    };
    
    IntegerControl.prototype.setValue = function ( value, force) {
        this.$result.val( value ); 

        if ( this.$visibleValue.length > 0 ) {
            if ( this.units ) this.$visibleValue.text(value + this.units);
            else this.$visibleValue.text(value);
        }

        if ( force ) {
            this.$bar.noUiSlider({ start: value }, true);
        }

        this.$result.trigger('keyup');
        this.$element.trigger('change');
    };  
    
    IntegerControl.prototype.getValue = function () {
        return this.$result.val();
    };
    
    IntegerControl.prototype.api = function () {
        return this;
    };

    // INTEGER CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap329_integerControl = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.integer-control');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.integer-control');
                if (!data) $this.data('factory.integer-control', (data = new IntegerControl(this)));
            });
        }
    };

    $.fn.factoryBootstrap329_integerControl.Constructor = IntegerControl;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-329 .factory-integer").factoryBootstrap329_integerControl();
    });
    
}( jQuery ) );