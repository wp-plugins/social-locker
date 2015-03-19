/**
*jQuery gradient generator 
@author Matt Crinklaw-Vogt (tantaman)
*/
(function( $ ) {
        
	if (!$.event.special.destroyed) {
		$.event.special.destroyed = {
		    remove: function(o) {
		    	if (o.handler) {                               
		    		o.handler();
		    	}
		    }
		}
	}
        
        
        //Сортирует в порядке убывания
	function ctrlPtComparator(l,r) {
		return l.position - r.position;
	}
        //Преобразует и инициализирует объекты вне области видимости
	function bind(fn, ctx) {
		if (typeof fn.bind === "function") {   
                    return fn.bind(ctx);
		} else {
                    return function() {                            
                        fn.apply(ctx, arguments);
                    }
		}
	}
        
        var agent = window.navigator.userAgent;
	var browserPrefix = ["-webkit-", "-moz-", "-ms-"];
	var x,y = 0;
            
	function GradientSelection($el, opts) {    
            var self = this;

            this.$el = $el;
            
            this.$presets = this.$el.find('.gradientPicker-pallets');
            this.$presetsItems = this.$presets.find('.factory-preset-gradient');
            
            this.$preview = this.$el.find(".gradientPicker-preview");
            this.$pointsContainer = this.$el.find(".factory-points");
                
            this.$colorPickerContainer = this.$el.find(".factory-color-picker-container");
            this.$colorPicker = this.$el.find(".factory-color-picker");
            this.$colorHex = this.$el.find(".factory-color-hex");
            this.$opacitySliderContainer = this.$el.find(".factory-slider-container");             
            this.$opacitySlider = this.$el.find(".factory-bar");            
             
            this.$el.css("position", "relative");
            this.opts = opts;
            
            this._createPresetGradients();
            this._createPreview();
            this._createColorPicker();
            this._createDirectionDropdown();

            this.controlPoints = [];

            opts.controlPoints = opts.controlPoints || [];
            for (var i = 0; i < opts.controlPoints.length; ++i) {                      
                var ctrlPt = this.createCtrlPt(opts.controlPoints[i]);                        
                this.controlPoints.push(ctrlPt);
            }

            // if the gradient is not specified by default 
            // then we're using the first preset gradient

            if ( this.controlPoints.length === 0 ) {
                this.controlPoints.push( this.createCtrlPt( this.$presetsItems.data('primary') + " 0% 1" ) );
                this.controlPoints.push( this.createCtrlPt( this.$presetsItems.data('secondary') + " 100% 1" ) );
            }

            // hides a color picker on losing the focus
            $(document).on('click.gp.factory', function(){
                self.hideColorPicker();
            });
            
            // doesn't hide on clicks inside the picker container 
            this.$colorPickerContainer.on('click.gp.factory', function(e){
                e.stopPropagation();
            });
            
            this.$pointsContainer.on('click.gp.factory',function(e){
                e.stopPropagation();
                self.placePoint(e);
            });

            this.updatePreview();
	}

	GradientSelection.prototype = {    

            /* ----------------------------------------------------------------------------- */
            /* Markup & Events
            /* ----------------------------------------------------------------------------- */  

            /**
             * Creates a panel with preset gradients.
             * 
             * @returns void
             */
            _createPresetGradients: function() {
                var self = this;
                
                // painting the preset gradients
                this.$presetsItems.each(function(){
                    var $item = $(this);
                    
                    var $secondaryColorWrap = $('<span class="factory-secondary-color-wrap"></span>').appendTo( $item );
                    var $secondaryColor = $('<span class="factory-secondary-color"></span>').appendTo($secondaryColorWrap);
                    
                    $item.css("background", $item.data('primary'));
                    $secondaryColor.css("background", $item.data('secondary'));             
                });

                // set gradient on clicl
                this.$presetsItems.click(function(){
                    var primaryColor = $(this).data('primary');
                    var secondaryColor = $(this).data('secondary'); 
                    self.setGradient([primaryColor + " 0% 1", secondaryColor + " 100% 1"]);
                });
            },
            
            /**
             * Creates the gradient preview.
             * @returns {undefined}
             */
            _createPreview: function() {
                
		var canvas = this.$preview[0];
                
		canvas.width = canvas.clientWidth === 0  ? 210 : canvas.clientWidth;
		canvas.height = canvas.clientHeight === 0  ? 30 : canvas.clientHeight;
                
		this.g2d = canvas.getContext("2d");
            },
            
            /**
             * Creates a color picker for points.
             * 
             * @return void
             */
            _createColorPicker: function() {
                var self = this;
                
                this.$colorPicker.factoryBootstrap329_iris({
                    width: 217,
                    palettes: ['#16a086', '#27ae61', '#2a80b9', '#8f44ad', '#2d3e50', '#f49c14', '#c1392b', '#bec3c7'],
                    hide: true,                                        
                    change: function(event, ui) {                            
                        self.currentPoint.updateColor( ui.color.toString() );
                        if ( !self._colorLock ) self.$colorHex.val( ui.color.toString() );
                    }
                }); 
                
                self.$colorHex.on('change paste keyup', function(){
                    self._colorLock = true;
                    self.$colorPicker.factoryBootstrap329_iris('color', self.$colorHex.val());
                    self._cancelColorLock();
                });
               
                this.$opacitySlider.noUiSlider({
                    start: 100,
                    range: {'min': 0, 'max': 100 },
                    step: 1
                });
 
                this.$opacitySlider.on('slide', function(){
                    var value = parseInt( self.$opacitySlider.val() );
                    var valueWithUnits = value + "%";

                    self.$opacitySliderContainer.find(".factory-visible-value").text(valueWithUnits);
                    self.currentPoint.updateOpacity( value / 100 ); 
                });

                this.$opacitySlider.on('set', function(){
                    self.$result.val( self.getValue() );
                    self.$result.trigger('change');
                }); 
            },
            
            /**
             * The code that allows to a dead loop when editing the hex value directly.
             */
            
            _repeatColorLock: false,
            _colorLock: false,
            _colorLockTimer: false,
            
            _cancelColorLock: function( force ) {
                var self = this;
                
                if ( self._colorLockTimer && !force ) {
                    this._repeatColorLock = true;
                    return false;
                }
                
                this._colorLockTimer = setTimeout(function(){ 
                    
                    if ( self._repeatColorLock ) {
                        self._repeatColorLock = false;
                        self._cancelColorLock( true );
                        return false;
                    }

                    self._colorLock = false;
                    self._colorLockTimer = false;
                    self._repeatColorLock = false;
                    
                }, 500);
            },
            
            /**
             * Creates the gradient direction dropdown.
             * 
             * @returns void
             */
            _createDirectionDropdown: function() {
                var self = this;

                var resVertical = factory && factory.res && factory.res.resVertical || 'vertical';
                var resHorizontal = factory && factory.res && factory.res.resHorizontal || 'horizontal';      

                this.$directionDropdown = 
                        $('<select class="gradientPicker-filldirection">'+
                            '<option value="vertical"'+ (self.opts.fillDirection === 'top' ? ' selected' : '') +'>' + resVertical + '</option>'+
                            '<option value="horizontal"'+ (self.opts.fillDirection === 'left' ? ' selected' : '') +'>' + resHorizontal + '</option>'+                          
                        '</select>');

		this.$el.append( this.$directionDropdown );  
                this.$directionDropdown.chosen();
                
                this.$directionDropdown.change(function(){
                    self.setGradientDirection( $(this).val() );
                });
            },
            
            /* ----------------------------------------------------------------------------- */
            /* Actions
            /* ----------------------------------------------------------------------------- */      
            
            /**
             * Shows a color picker for the given point.
             * 
             * @param ControlPoint point
             * @returns void
             */
            showColorPicker: function( point ) {
                this.currentPoint = point;
                
                $(".factory-current-point").removeClass('factory-current-point');
                point.$el.addClass('factory-current-point');
                
                this.$colorPickerContainer.show();
                this.$colorPicker.factoryBootstrap329_iris( 'show' );
                this.$colorPicker.factoryBootstrap329_iris( 'option', 'color', point.color );
                
                this.$opacitySlider.val( point.opacity * 100 );
                this.$opacitySlider.trigger('slide');
            },
            
            hideColorPicker: function() {
                $(".factory-current-point").removeClass('factory-current-point');
                this.currentPoint = false;
                this.$colorPickerContainer.hide();
            },
            
            isColorPickerShown: function() {
                return this.currentPoint;
            },

            docClicked: function() {
                    //this.ctrlPtConfig.hide();
                    $('.gradientPicker-iris-wrap').hide();
                    $('.gradientPicker-ctrlPt').removeClass('open');
            },
            
            createCtrlPt: function(ctrlPtSetup) {                        
                return new ControlPoint(this, ctrlPtSetup, this.opts.orientation)
            },

            /**
             * Upadtes given options.
             * 
             * @param array opts
             * @param bool recreatPoints
             * @returns void
             */
            updateOptions: function( opts, recreatPoints ) {
                    $.extend(this.opts, opts); 

                    if( recreatPoints ) {
                        this.controlPoints = [];
                        this.$pointsContainer.html('');                       
                        for (var i = 0; i < this.opts.controlPoints.length; ++i) {
                                var ctrlPt = this.createCtrlPt(this.opts.controlPoints[i]);
                                this.controlPoints.push(ctrlPt);
                        }
                    }

                    this.updatePreview();

                    switch ( this.opts.fillDirection ) {
                        case 'top':
                               fillDirectCheck = 'horizontal';
                           break;
                        case 'left':
                               fillDirectCheck = 'vertical';
                           break; 
                        default:
                                fillDirectCheck = false;
                           break;         
                    }
                    if( fillDirectCheck )                        
                        $('.gradientPicker-filldirection', this.$el).find('option[value="' + fillDirectCheck + '"]').prop('selected', true);


            },

            /**
             * Refreshes the gradient preview.
             * 
             * @returns void
             */
            updatePreview: function() {
                
                var result = [];                       
                this.controlPoints.sort(ctrlPtComparator); 

                this.g2d.clearRect ( 0, 0, this.g2d.canvas.width , this.g2d.canvas.height );

                if (this.opts.orientation === "horizontal") {                                 
                        var grad = this.g2d.createLinearGradient(0, 0, this.g2d.canvas.width, 0);

                        for (var i = 0; i < this.controlPoints.length; ++i) {
                            var pt = this.controlPoints[i];  
                            
                            grad.addColorStop(pt.position, "rgba(" + hexToRgb(pt.color).r + "," + hexToRgb(pt.color).g + "," + hexToRgb(pt.color).b + ", "+pt.opacity+")");
                            result.push({
                                    position: pt.position,
                                    color: pt.color,
                                    opacity: pt.opacity
                            }); 
                        }
                } else {

                }

                this.g2d.fillStyle = grad;
                this.g2d.fillRect(0, 0, this.g2d.canvas.width, this.g2d.canvas.height);

                if (this.opts.generateStyles)
                        var styles = this._generatePreviewStyles();

                this.opts.change(result, styles);
            },

            removeControlPoint: function(ctrlPt) {
                var cpidx = this.controlPoints.indexOf(ctrlPt);

                if (cpidx != -1) {
                        this.controlPoints.splice(cpidx, 1);
                        ctrlPt.$el.remove();
                }
            },

            /**
             * Adds a new point.
             */
            placePoint: function(e) {      
                e.stopPropagation();

                var offset = $(e.target).offset();
                var x = e.pageX - offset.left;
                var y = e.pageY - offset.top;

                var imgData = this.g2d.getImageData(x,y,1,1);                       
                var colorStr = "rgb(" + imgData.data[0] + "," + imgData.data[1] + "," + imgData.data[2] + ")";
                var opacity = ( 1 / 255 ) * imgData.data[3];

                var cp = this.createCtrlPt({
                        position: x / this.g2d.canvas.width,
                        color: rgb2hex(colorStr),
                        opacity: opacity
                });            
                
                this.controlPoints.push(cp);
                this.controlPoints.sort(ctrlPtComparator);
                
                this.showColorPicker( cp );
            },

            setGradient: function( controlPoints ) {
                this.updateOptions({ controlPoints: controlPoints }, true);
                this.updatePreview();
            },

            setGradientDirection: function( direction ){
                switch ( direction ) {
                    case 'horizontal':
                        this.updateOptions({type: "linear", fillDirection: "top"}, false);
                    break;
                    case 'vertical':
                        this.updateOptions({type: "linear", fillDirection: "left"}, false);
                    break;                       
                }   
            },

            _generatePreviewStyles: function() {

                    var gradientOption = {};                     
                    gradientOption['filldirection'] = this.opts.fillDirection;
                    gradientOption['color_points'] = [];

                    for (var i = 0; i < this.controlPoints.length; ++i) {
                            var pt = this.controlPoints[i];
                            gradientOption['color_points'].push( pt.color + " " + ((pt.position*100)|0) + "% " + pt.opacity );
                    }

                    return JSON.stringify(gradientOption);
            },
            
            _genPalletsBackground: function(primaryColor, secondaryColor) {
               cssRules = 'linear-gradient(90deg, '+primaryColor+' 0%, '+secondaryColor+' 100%)';

               if (agent.indexOf('WebKit') >= 0)
                     crossBrowserRule = browserPrefix[0] + cssRules;
                else if (agent.indexOf('Mozilla') >= 0)
                     crossBrowserRule = browserPrefix[1] + cssRules;
                else if (agent.indexOf('Microsoft') >= 0)
                     crossBrowserRule = browserPrefix[2] + cssRules;
                else
                     crossBrowserRule = cssRules;

                return crossBrowserRule;
            }
	};

        /**
         * Gradient Control Point
         */
	function ControlPoint(parent, initialState, orientation) {
            var self = this;
            
            this.parent = parent;
            this.$container = parent.$pointsContainer;
            
            this.$el = $('<span class="factory-point"></span>');
            this.$pointColor = $('<span class="factory-point-color"></span>').appendTo( this.$el );    
            
            this.$container.append( this.$el );

            if (typeof initialState === "string") {
                initialState = initialState.split(" ");
                this.position = parseFloat(initialState[1])/100;
                this.color = initialState[0];
                this.opacity = initialState[2];
            } else {
                this.position = initialState.position;
                this.color = initialState.color;
                this.opacity = initialState.opacity;
            }
            
            this.outerWidth = this.$el.outerWidth();

            this.$pointColor.css({
                "background-color": self.color,
                "opacity": self.opacity
            });

            if (orientation === "horizontal") {
                var pxLeft = (self.$container.width() - this.$el.outerWidth()) * (this.position);
                this.$el.css("left", pxLeft);
            } else {
                var pxTop = (self.$container.height() - this.$el.outerHeight()) * (this.position);
                this.$el.css("top", pxTop);
            }

            this.drag = bind(this.drag, this);
            this.stop = bind(this.stop, this);

            this.$el.disableSelection().css('webkit-user-select','none').draggable({
                axis: (orientation === "horizontal") ? "x" : "y",
                drag: this.drag,
                stop: this.stop,
                containment: self.$container,
                cancel: null
            });

            // shows the locker picker on click
            this.$el.on('click.gp.factory', function(e){  
                if ( self.parent.currentPoint === self ) self.parent.hideColorPicker( self );
                else self.parent.showColorPicker( self );
                e.stopPropagation();                       
            });
	}

	ControlPoint.prototype = {
              
            updateColor: function( color ) {
                this.color = color;
                this.$pointColor.css( 'background-color', color );  
                
                this.parent.updatePreview();
            },
            
            updateOpacity: function( opacity ) {
                this.opacity = opacity;
                this.$pointColor.css( 'opacity', opacity ); 
                
                this.parent.updatePreview();
            },
            
            drag: function(e, ui) { 
     
                var stopPointPosition = eval( this.$el.parent().offset().top + this.$el.parent().height() + 20 ); 

                if( e.pageY > stopPointPosition ) {                                  
                     this.remove();                                                              
                } 

                // convert position to a %
                var left = ui.position.left;                         
                this.position = (left / (this.$container.width() - this.outerWidth));

                this.parent.updatePreview();
            },

            stop: function() {
                this.parent.updatePreview();
            },

            remove: function() {  
                this.parent.removeControlPoint(this);
                this.parent.hideColorPicker();
                this.parent.updatePreview();
            }
	};
        
	var methods = {
		init: function(opts) {
                        //orientation - Позиция пикера 
                        //type - linear, radial
                        //fillDirection - направление градиента
			opts = $.extend({
				controlPoints: ["#FFF 0% 1", "#000 100% 1"],
				orientation: "horizontal",
				type: "linear",
				fillDirection: "left",
				generateStyles: true,
				change: function() {}
			}, opts);

			this.each(function() {
				var $this = $(this);
				var gradSel = new GradientSelection($this, opts);
				$this.data("gradientPicker-sel", gradSel);
			});
		},

		update: function(opts) {
			this.each(function() {
				var $this = $(this);
				var gradSel = $this.data("gradientPicker-sel");                                
				if (gradSel != null) {
					gradSel.updateOptions(opts, true);
				}
			});
		}
	};

	$.fn.gradientPicker = function(method, opts) {            
		if (typeof method === "string" && method !== "init") {
			methods[method].call(this, opts);
		} else {
			opts = method;
			methods.init.call(this, opts);
		}
	};
})( jQuery );