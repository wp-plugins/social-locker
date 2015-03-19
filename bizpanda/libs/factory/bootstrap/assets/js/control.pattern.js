/**
 * Pattern Control
 * 
 * @author Alex Kovalev <alex@byonepress.com>
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013-2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 3.1.0
 */
;( function( $ ){
    
    $.widget( "factoryBootstrap329.patternControl", {

        _create: function() {
            
            this.$element = this.element;
            this.$preview = this.$element.find(".factory-preview");

            this.$patternResult = this.$element.find(".factory-pattern-result");
            this.$colorResult = this.$element.find(".factory-color-result");

            this.$patterns = this.$element.find(".factory-patterns-panel");   
            this.$patternItems = this.$element.find(".factory-patterns-item");  

            this.$btnUpload = this.$element.find(".factory-upload-btn");

            this.$btnChangeColor = this.$element.find(".factory-change-color-btn");
            this.$colorPanel = this.$element.find(".factory-color-panel");
            this.$colorContol = this.$element.find(".factory-color");

            this._initMainPanel();
            this._initColorPickerPanel();
            this._initPatternsPanel();
        },
        
        /**
         * Inits the Main Panel with the dropdown and buttons.
         * 
         * @since 3.1.0
         * @return void
         */
        _initMainPanel: function( ) {
            var self = this;
            
            // a click on a preview opens the patterns panel

            this.$preview.on('click', function(){
                
                self.togglePatternsPanel();
                return false;
            });

            // the button showing the panel to a pattern change color

            this.$btnChangeColor.on('click', function(){
                
                self.toggleColorPanel();
                return false;
            });   
        },
        
        /**
         * Toggles the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        togglePatternsPanel: function() {
            
            if ( this.$element.hasClass('factory-patterns-panel-active') ) this.hidePatternsPanel();
            else this.showPatternsPanel();
        },
        
        /**
         * Hides the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        hidePatternsPanel: function() {
            
            this.$element.removeClass('factory-patterns-panel-active');
        },
        
        /**
         * Shows the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        showPatternsPanel: function() {
            
            this.$element.addClass('factory-patterns-panel-active');
        },  
        
        /**
         * Returns true if the pattern has the color options set.
         * 
         * @since 3.1.0
         * @returns bool
         */
        hasColor: function() {
            
            return this.$element.hasClass('factory-color-panel-active');
        },
        
        /**
         * Toggles the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        toggleColorPanel: function() {
            
            if ( this.hasColor() ) this.hideColorPanel();
            else this.showColorPanel();
        },
        
        /**
         * Hides the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        hideColorPanel: function() {
            
            this.$element.removeClass('factory-color-panel-active');
            this.$btnChangeColor.removeClass('button-active');
            this.$colorResult.val('');
            this.$colorResult.trigger('change');
            this.$element.trigger('change');
        },
        
        /**
         * Shows the panel with which the user can choose another color for a pattern.
         * 
         * @since 3.1.0
         * @returns void
         */
        showColorPanel: function() {
            
            this.$element.addClass('factory-color-panel-active');
            this.$btnChangeColor.addClass('button-active');
            this.$colorResult.val( this.$colorContol.factoryBootstrap329_colorControl('getValue') );
            this.$colorResult.trigger('change');
            this.$element.trigger('change');
        },
        
        /**
         * Inits the Color Picker panel.
         * 
         * @since 3.1.0
         * @return void
         */
        _initColorPickerPanel: function() {
            var self = this;
            
            this.$colorContol.bind('updated.color.factory', function( e, color ){                        
                self.$colorResult.val( color );
                return false;
            });
        },
        
        /**
         * Inits the Patterns Panel.
         * 
         * @since 3.1.0
         * @return void
         */
        _initPatternsPanel: function() {
            var self = this;
            
            // the upload button
            
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

            this.$btnUpload.on('click', function(){ 

                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){                                    
                    if ( _custom_media ) {                                    
                        self.$preview.css({background:'url('+attachment.url+') repeat', border:'0', fontSize:'0'});
                        self.$patternResult.attr('value', attachment.url).trigger('change');                          
                    } else {
                        return _orig_send_attachment.apply( this, [props, attachment] );
                    };                            
                }    

                wp.media.editor.open( self.$btnUpload );                    
                $('.add_media').on('click', function(){
                    _custom_media = false;
                });

                return false;
            }); 
            
            // selecting a pattern
            
            this.$patternItems.on('click', function(){

                if( $(this).data('pattern') ) {
                    var puthImage = $(this).data('pattern');
                    self.$preview.removeClass('factory-empty');   
                    self.$preview.css({ background: 'url('+puthImage+') repeat' });
                    self.$patternResult.attr('value', puthImage).trigger('change');
                }
            }); 
        }
    });
    
    $(function(){
        $.widget.bridge( "factoryBootstrap329_patternControl", $.factoryBootstrap329.patternControl );
        $(".factory-bootstrap-329 .factory-pattern").factoryBootstrap329_patternControl({});
    });
    
}( jQuery ) );