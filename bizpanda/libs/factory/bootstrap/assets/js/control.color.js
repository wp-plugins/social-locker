/**
 * Factory Botstrap Color Control
 * 
 * @uses plugin.iris.js
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013-2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 3.1.0
 */

;( function( $ ){
    
    $.widget( "factoryBootstrap329.colorControl", {

        _create: function() {
            
            this.$element = this.element;
            this.$picker = this.$element.find('.factory-color-hex');
            this.$preview = this.$element.find('.factory-preview');
            this.$background = this.$element.find('.factory-background');

            this._init();
        },
        
        _init: function( event ) {
            var self = this;

            var irisOptions = {
                width: 216,
                palettes: ['#16a086', '#27ae61', '#2a80b9', '#8f44ad', '#2d3e50', '#f49c14', '#c1392b', '#bec3c7'],
                hide: true,
                change: function(event, ui) { 
                    self.$background.css({ background: ui.color.toString() });
                    
                    self.$element.trigger('change.color.factory', [ ui.color.toString() ]);
                    self.$element.trigger('updated.color.factory', [ ui.color.toString() ]);
                }
            };
            
            var picketTarget = this.$element.data('picker-target');
            if ( picketTarget ) irisOptions.target = $(picketTarget);

            this.$picker.factoryBootstrap329_iris(irisOptions); 
            this.$picker.off('focus');

            $(document).on("click.color.factory", function(){
               self.$picker.factoryBootstrap329_iris("hide");  
            });

            this.$picker.add(this.$background).on("click.color.factory", function(e){
               e.stopPropagation();
               self.$picker.factoryBootstrap329_iris("show");  
            });  
        },
        
        togglePicker: function() {
            if( this.$element.hasClass('factory-picker-active') ) this.hidePicker();
            else this.showPicker();
        },

        hidePicker: function() {
            this.$element.removeClass('factory-picker-active');
            this.$picker.factoryBootstrap329_iris( 'hide' );
        }, 

        showPicker: function() {
            this.$element.addClass('factory-picker-active');
            this.$picker.factoryBootstrap329_iris( 'show' );
        },
        
        getValue: function() {
            return this.$picker.val();
        },
        
        setValue: function( value, trigger ) {
            this.$picker.val(value);
            if ( trigger ) self.$picker.trigger('change');
        }
    });
    
    $(function(){
        $.widget.bridge( "factoryBootstrap329_colorControl", $.factoryBootstrap329.colorControl );
        $(".factory-bootstrap-329 .factory-color").factoryBootstrap329_colorControl({});
    });
    
}( jQuery ) );