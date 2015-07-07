if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.lockerEditor ) window.bizpanda.lockerEditor = {};

(function($){
    
    window.bizpanda.lockerEditor = {
        
        init: function() {  
            this.item = $('#opanda_item').val();
            
            this.basicOptions.init( this, this.item );        

            this.trackInputChanges();
            this.recreatePreview();
                this.initStyleRollerButton();
            

        }, 

        /**
         * Inits a button which offers to buy the StyleRoller Add-on.
         */
        initStyleRollerButton: function() {
            if ( window.window.onp_sl_styleroller || !window.onp_sl_show_styleroller_offer ) return;
            var $button = $("<a target='_blank' class='btn btn-default' id='onp-sl-styleroller-btn' href='" + window.onp_sl_styleroller_offer_url + "'><i class='fa fa-flask'></i>" + window.onp_sl_styleroller_offer_text + "</a>");
            $("#opanda_style").after($button);
        },

        /**
         * Starts to track user input to refresh the preview.
         */
        trackInputChanges: function() {
            var self = this;
            
            var tabs = [
                "#OPanda_BasicOptionsMetaBox",
                "#OPanda_SocialOptionsMetaBox",
                "#OPanda_AdvancedOptionsMetaBox",
                "#OPanda_ConnectOptionsMetaBox",
                '#OPanda_SubscriptionOptionsMetaBox'
            ];
            
            for(var index in tabs) {
                
                $(tabs[index])
                   .find("input, select, textarea")
                   .bind('change keyup', function(){ self.refreshPreview(); });
            }
        },
        
        /**
         * Binds the change event of the WP editor.
         */
        bindWpEditorChange: function( ed ) {
            var self = this;
            
            var changed = function() {
                tinyMCE.activeEditor.save();
                self.refreshPreview();
            };
            
            if ( tinymce.majorVersion <= 3 ) {
                ed.onChange.add(function(){ changed(); });
            } else {
                ed.on("change", function(){ changed(); });
            }
        },
        
        /**
         * Refreshes the preview after short delay.
         */
        refreshPreview: function( force ) {
            var self = this;
            
            if ( this.timerOn && !force ) {
                this.timerAgain = true;
                return;
            }

            this.timerOn = true;
            setTimeout(function(){

                if (self.timerAgain) {
                    self.timerAgain = false;
                    self.refreshPreview( true );
                } else {
                    self.timerAgain = false;
                    self.timerOn = false;
                    self.recreatePreview();
                }

            }, 500);
        },
        
        /**
         * Recreates the preview, submmits forms to the preview frame.
         */
        recreatePreview: function() {          
            var url = $("#lock-preview-wrap").data('url');            
            var options = this.getPreviewOptions();   
            
            $.bizpanda.hooks.run('opanda-refresh-preview');
            window.bizpanda.preview.refresh( url, 'preview', options, 'onp_sl_update_preview_height' );
        },
        
        /**
         * Gets options for the preview to submit into the frame.
         */
        getPreviewOptions: function() {
            
            var options = this.getCommonOptions();
            var options = $.bizpanda.filters.run('opanda-preview-options', [options]);
            
            if ( window.bizpanda.lockerEditor.filterOptions ) {
                options = window.bizpanda.lockerEditor.filterOptions( options );
            }
            
            $(document).trigger('onp-sl-filter-preview-options', [options]);
            return options;
        },
        
        getCommonOptions: function() {

            var timer = parseInt( $("#opanda_timer").val() );

            var options = {
                
                text: {
                    header: $("#opanda_header").val(),
                    message: $("#opanda_message").val()   
                },
                
                theme: 'secrets',
                
                overlap: {
                    mode: $("#opanda_overlap").val(),
                    position: $("#opanda_overlap_position").val()
                },
                effects: { 
                    highlight: $("#opanda_highlight").is(':checked')
                },
                
                locker: {
                    timer: ( !timer || timer === 0 ) ? null : timer,		
                    close: $("#opanda_close").is(':checked'),
                    mobile: $("#opanda_mobile").is(':checked')
                },
                
                proxy: window.opanda_proxy_url
            };

            if (!options.text.header && options.text.message) {
                options.text = options.text.message;
            }

            options['theme'] = $("#opanda_style").val();

            if ( window.bizpanda.previewGoogleFonts ) {
                
                var theme = options['theme'];
                options['theme'] = {
                    'name': theme,
                    'fonts': window.bizpanda.previewGoogleFonts
                };
            }
            
            return options;
        },

        // --------------------------------------
        // Basic Metabox
        // -------------------------------------- 
        
        basicOptions: {
            
            init: function( editor ){
                this.editor = editor;
                
                this.initThemeSelector();
                this.initOverlapModeButtons();
            },
            
            initThemeSelector: function() {
                
                var showThemePreview = function(){
                    
                    var $item = $("#opanda_style").find("option:selected");
                    var preview = $item.data('preview');
                    var previewHeight = $item.data('previewheight');                    
                    console.log ( previewHeight );
                    var $wrap = $("#lock-preview-wrap");
                    
                    if ( preview ) {       
                        $wrap.find("iframe").hide();
                        $wrap.css('height', previewHeight ? previewHeight + 'px' : '300px');
                        $wrap.css('background', 'url("' + preview + '") center center no-repeat');
                    } else {
                        $wrap.find("iframe").show();
                        $wrap.css('height', 'auto');
                        $wrap.css('background', 'none');
                    }
                };
                
                showThemePreview();
                
                $.bizpanda.hooks.add('opanda-refresh-preview', function(){
                    showThemePreview();
                });
            },
            
            initOverlapModeButtons: function() {
                var $overlapControl = $("#OPanda_BasicOptionsMetaBox .factory-control-overlap .factory-buttons-group");
                var $positionControl = $("#OPanda_BasicOptionsMetaBox .factory-control-overlap_position");
                var $position = $("#opanda_overlap_position");            

                $overlapControl.after( $("<div id='opanda_overlap_position_wrap'></div>").append( $position ) );

                var checkPositionControlVisability = function( ){
                    var value = $("#opanda_overlap").val();

                    if ( value === 'full' ) {
                        $("#opanda_overlap_position_wrap").css("display", "none");
                    } else {
                        $("#opanda_overlap_position_wrap").css("display", "inline-block");   
                    }
                };
                
                var toggleAjaxOption = function() {
                    var value = $("#opanda_overlap").val();
                    
                    if ( value === 'full' ) {
                        $("#opanda-ajax-disabled").hide();
                    } else {
                        $("#opanda-ajax-disabled").fadeIn();
                    }
                };
                
                checkPositionControlVisability();
                toggleAjaxOption();
                
                $("#opanda_overlap").change(function(){
                    checkPositionControlVisability()
                    toggleAjaxOption();
                });
            }
        }
    };
    
    $(function(){
        window.bizpanda.lockerEditor.init();
    });
    
})(jQuery)

function opanda_editor_callback(e) {
    if ( e.type == 'keyup') {
        tinyMCE.activeEditor.save();
        window.bizpanda.lockerEditor.refreshPreview();
    }
    return true;
}


