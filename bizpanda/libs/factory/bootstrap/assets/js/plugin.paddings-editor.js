( function( $ ){
    
    var PaddingsEditor = function (element) {
        this.$element = $(element);
        this.$rectangle = this.$element.find(".factory-rectangle");
        this.$center = this.$element.find(".factory-side-center");
        this.$bar = this.$element.find(".factory-bar");
        this.$sides = this.$element.find(".factory-side");
        this.$result = this.$element.find(".factory-result");
        
        this.units = this.$element.data('units');
        this.rangeStart = this.$element.data('range-start');
        this.rangeEnd = this.$element.data('range-end');              
        this.step = this.$element.data('step');  
        
        this._createCanvas();
        this._createSlider(); 
        this._initMouse();
        
        this.selectSide('center');
    };
    
    /**
     * Creates canvas for drawing control.
     */
    PaddingsEditor.prototype._createCanvas = function() {
        var self = this;
        
        this._recalculateSizes();
        this.$canvas = $("<canvas width='" + self.elementWidth + "' height='" + self.elementHeight + "'>")   
                       .appendTo(this.$rectangle);
               
        this.canvasContext = this.$canvas.get(0).getContext('2d');
        
        this._redraw();
    };   
    
    /**
     * Creates slider.
     */
    PaddingsEditor.prototype._createSlider = function() {
        var self = this;
        
        this.$bar.noUiSlider({
            start: parseInt( self.$center.data('value') ),
            range: {'min': self.rangeStart, 'max': self.rangeEnd },
            step: self.step
        });
        
        this.$bar.on('slide', function(){
            var value = parseInt( self.$bar.val() );
            var valueWithUnits = value + self.units;
            
            if ( self.activeSide === 'center' ) {
                self.$sides.data('value', value);
                self.$sides.find(".factory-visible-value").text( valueWithUnits );
            } else {
                self.$activeSide.data('value', value);
                self.$activeSide.find(".factory-visible-value").text( valueWithUnits );
            }
        });
        
        this.$bar.on('set', function(){
            self.$result.val( self.getValue() );
            self.$result.trigger('change');
        }); 
    };   
    
    /**
     * Returns a current value.
     */
    PaddingsEditor.prototype.getValue = function() {
        var topValue = this.$element.find(".factory-side-top").data('value');
        var rightValue = this.$element.find(".factory-side-right").data('value');
        var bottomValue = this.$element.find(".factory-side-bottom").data('value');
        var leftValue = this.$element.find(".factory-side-left").data('value');
        return topValue + this.units + " " + rightValue + this.units + " " + bottomValue + this.units + " " + leftValue + this.units;
    }
    
    /**
     * Selects a given side by its name.
     */
    PaddingsEditor.prototype.selectSide = function( side ) { 
        if ( this.activeSide === side ) return;
        
        this.activeSide = side;
        this.$activeSide = this.$element.find(".factory-side-" + this.activeSide);
        
        this.$element.find(".factory-side.factory-active").removeClass("factory-active");
        this.$activeSide.addClass("factory-active");
        
        this._redraw();
        this.$bar.val( this.$activeSide.data('value') );
    }
    
    PaddingsEditor.prototype._initMouse = function() {
        var self = this;
        
        this._recalculateSizes();

        this.$rectangle.on('mousemove.paddings-editor', function(e){
            self.hoveredSide = self._getCurrentSide(e.pageX, e.pageY);
            
            if ( self.hoveredSide === self.lastHoveredSide ) return;
            self.lastHoveredSide = self.hoveredSide; 
            
            self._redraw();
        });
        
        this.$rectangle.on('mouseleave.paddings-editor', function(e){
            self.hoveredSide = self.lastHoveredSide = null;
            self._redraw();
        });
        
        this.$rectangle.on('click.paddings-editor', function(e){
            var side = self._getCurrentSide(e.pageX, e.pageY);
            self.selectSide( side );
        });  
    };
    
    /**
     * Returns a current side by mouse pointer position.
     */
    PaddingsEditor.prototype._getCurrentSide = function(pageX, pageY) {   
        var offset = this.$element.offset();
        
        var offsetX = pageX - offset.left;
        var offsetY = pageY - offset.top;    
        
        var dX = offsetX - this.centerX;
        var dY = offsetY - this.centerY;
        var dL = Math.sqrt ( Math.pow(dX,2) + Math.pow(dY,2) );
        if ( dL <= this.centerR ) return "center";

        // for line #1 (from bottom-left corner to top-right conner)
        var a1 = this.elementHeight;
        var b1 = this.elementWidth;
        var c1 = -this.elementWidth * this.elementHeight;
        
        var resultY1 = - ( c1 + a1 * offsetX  ) / b1;
        
        // for line #2 (from bottom-left corner to top-right conner)
        var a2 = -this.elementHeight;
        var b2 = this.elementWidth;
        var c2 = 0;
    
        var resultY2 = - ( c2 + a2 * offsetX  ) / b2;
        
        if ( resultY1 < offsetY && resultY2 > offsetY ) return "right";
        if ( resultY1 > offsetY && resultY2 < offsetY ) return "left"; 
        if ( resultY1 > offsetY && resultY2 > offsetY ) return "top";
        if ( resultY1 < offsetY && resultY2 < offsetY ) return "bottom";   
        return null;
    }
    
    /**
     * Redraws the canvas.
     */
    PaddingsEditor.prototype._redraw = function() {
        this.canvasContext.clearRect ( 0, 0, this.elementWidth, this.elementHeight ); 
         
        this._highlightSide('top', '#a6b6b6'); 
        this._highlightSide('bottom', '#a6b6b6');
        this._highlightSide('left', '#aec0c0');
        this._highlightSide('right', '#aec0c0');
        
        this._highlightSide(this.hoveredSide, '#c0cece');
        if ( this.activeSide ) this._highlightSide(this.activeSide, '#829595');
    }
    
    /**
     * Hightlights hovered area.
     */
    PaddingsEditor.prototype._highlightSide = function( side, style ) {
        
        if ( !side ) return;
        if ( side === 'center' ) return;
        
        this.canvasContext.fillStyle = style;   
        this.canvasContext.beginPath();
            
        if ( 'top' === side ) {

            this.canvasContext.moveTo(0, 0);
            this.canvasContext.lineTo(this.centerX, this.centerY);
            this.canvasContext.lineTo(this.elementWidth, 0);
            
        } else if ( 'bottom' === side ) {
            
            this.canvasContext.moveTo(0, this.elementHeight);
            this.canvasContext.lineTo(this.centerX, this.centerY);
            this.canvasContext.lineTo(this.elementWidth, this.elementHeight);
            
        } else if ( 'left' === side ) {

            this.canvasContext.moveTo(0, 0);
            this.canvasContext.lineTo(this.centerX, this.centerY);
            this.canvasContext.lineTo(0, this.elementHeight);
            
        } else if ( 'right' === side ) {

            this.canvasContext.moveTo(this.elementWidth, 0);
            this.canvasContext.lineTo(this.centerX, this.centerY);
            this.canvasContext.lineTo(this.elementWidth, this.elementHeight);
        }
        
        this.canvasContext.closePath();
        this.canvasContext.fill();
    };
    
    PaddingsEditor.prototype._recalculateSizes = function() {
        
        this.elementOffset = this.$element.offset();
        this.elementWidth = this.$rectangle.innerWidth();
        this.elementHeight = this.$rectangle.innerHeight(); 
        this.centerSize = this.$center.innerWidth();
        
        this.centerR = this.centerSize / 2;
        this.centerX = this.elementWidth / 2;
        this.centerY = this.elementHeight / 2;    
    }

    // INTEGER CONTROL DEFINITION
    // ================================
    
    $.fn.factoryBootstrap329_paddingsEditor = function (option) {
        
        // call an method
        if ( typeof option === "string" ) {
            var data = $(this).data('factory.paddings-editor');
            if ( !data ) return null;
            return data[option]();
        }
        
        // creating an object
        else {
            return this.each(function () {
                var $this = $(this);
                var data  = $this.data('factory.paddings-editor');
                if (!data) $this.data('factory.paddings-editor', (data = new PaddingsEditor(this)));
            });
        }
    };

    $.fn.factoryBootstrap329_paddingsEditor.Constructor = PaddingsEditor;
    
    // AUTO CREATING
    // ================================
    
    $(function(){
        $(".factory-bootstrap-329 .factory-paddings-editor").factoryBootstrap329_paddingsEditor();
    });
    
}( jQuery ) );