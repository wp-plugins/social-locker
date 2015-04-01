/**
 * Font Control
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013-2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 3.1.0
 */
;( function( $ ){
    
    $.widget( "factoryBootstrap329.fontControl", {

        _create: function() {
            
            this.$element = this.element;

            this.$family = this.$element.find(".factory-family-wrap select");
            this.$googleFontData = this.$element.find(".factory-google-font-data");           
            
            this.fontSelectorName = this.$family.attr('name');
            
            this.googleFontsOptions = {
                frameName: this.element.data('google-fonts-frame-name')
            };
            
            if ( !this.googleFontsOptions.frameName )
                this.googleFontsOptions.frameName = 'preview';

            this._initUI();
            this._bindEvents();
        },
        
        _initUI: function() {
            this.$family.chosen();
        },
        
        _bindEvents: function() {
            var self = this;
            
            this.$family.change(function( data ){
                var $option = self._getCurrentOption();
                var isGoogleFont = $option.data('google-font');
                
                if ( isGoogleFont ) {
                    
                    var family = $option.data('family');
                    var variants = $option.data('variants').split(',');
                    var subsets = $option.data('subsets').split(',');
                    
                    self._loadGoogleFont( family, variants, subsets );
                } else {
                    self.$googleFontData.val("");
                }
            });  
        },
        
        _getCurrentOption: function() {
            var value = this.$family.val();
            return this._getOptionByValue( value );
        },
        
        _getOptionByValue: function( value ) {
            var $option = this.$family.find("option[value='" + value + "']");
            return $option;
        },
        
        _loadGoogleFont: function( family, variants, subsets ) {
            
            // these filters allow extrenal apps to manage which variants 
            // and subsets have to be laoded and which should be skipped
            
            var variants = this._filterGoogleFontVariantsBeforeLoading( family, variants, subsets );
            var subsets = this._filterGoogleFontSubsetsBeforeLoading( family, variants, subsets );
            
            this.$googleFontData.val(this._encode64(JSON.stringify({
                name: family,
                styles: variants,
                subsets: subsets
            })));
            
            // the target is a place (current window or an iframe)
            // where the font should be loaded
            
            var $target = this._getTargetToLoadGoogleFont();
            
            // the id is used to prevent loading multiple
            // fonts for a single font control
            
            var linkId = this.fontSelectorName + "-font-loader";
            
            // removes the previous link with the same id
            
            var $link = $target.find('#' + linkId);
            if ( $target.find('#' + linkId).length > 0 ) {
                $link.remove();
            }
            
            // builds an URL for loading the font

            var url = 'http://fonts.googleapis.com/css';
            
            if ( variants && variants.length ) family = family + ":" + variants.join(",");
            url = url + '?family=' + family;
            
            if ( subsets && subsets.length ) url = url + '&subset=' + subsets.join(",");

            $('<link id="' + linkId + '" rel="stylesheet" type="text/css" href="' + url + '" >').appendTo( $target );
        },
        
        /**
         * Calls external filters to modify the list of the google font variants to be loaded.
         * @since 3.2.8
         */
        _filterGoogleFontVariantsBeforeLoading: function( family, variants, subsets ) {
            var self = this;
            $(document).trigger('factory-filter-google-font-variants', [variants, self.$element, family, subsets]);
            return variants;
        },
        
        /**
         * Calls external filters to modify the list of the google font subsets to be loaded.
         * @since 3.2.8
         */
        _filterGoogleFontSubsetsBeforeLoading: function( family, variants, subsets ) {
            var self = this;
            $(document).trigger('factory-filter-google-font-subsets', [subsets, self.$element, variants, subsets]);
            return subsets;  
        },
        
        /**
         * Returns a target element where the google font link element should be appended.
         * @since 3.2.8
         */
        _getTargetToLoadGoogleFont: function() {
            
            var frameName = this.googleFontsOptions.frameName;
            var $target = $('head');
            
            if ( frameName ) {
                
                var $frame = $('iframe[name="' + frameName + '"]');
                if ( $frame.length === 0 ) return console.error('The preview container not found.');
                
                $target = $frame.contents().find('head');
            }
            
            return $target;
        },
         
        _base64KeyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

        _encode64: function(input) {
            input = escape(input);
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;

            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                   enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                   enc4 = 64;
                }

                output = output +
                  this._base64KeyStr.charAt(enc1) +
                  this._base64KeyStr.charAt(enc2) +
                  this._base64KeyStr.charAt(enc3) +
                  this._base64KeyStr.charAt(enc4);

                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
                
            } while (i < input.length);

            return output;
        }
    });
    
    $(function(){
        $.widget.bridge( "factoryBootstrap329_fontControl", $.factoryBootstrap329.fontControl );
        $(".factory-bootstrap-329 .factory-font").factoryBootstrap329_fontControl({});
    });
    
}( jQuery ) );