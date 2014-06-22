if ( !window.onpsl ) window.onpsl = {};
if ( !window.onpsl.preview ) window.onpsl.lockerEditor = {};

(function($){
    
    window.onpsl.lockerEditor = {
        
        init: function() {  
 
            this.initSocialTabs();
            
            this.manualLocking.init();
            this.bulkLocking.init();
            this.visability.init();

            this.trackInputChanges();
            this.recreatePreview();

            if ( window['sociallocker-next-build'] === 'free' ) {
                this.initTrialBox();
            }      
        }, 

        /**
         * Inits social tabs.
        */
        initSocialTabs: function() {
            var self = this;
            var socialTabWrap = $(".factory-align-vertical .nav-tabs");
            var socialTabItem = $(".factory-align-vertical .nav-tabs li");
            
            // current order
            
            var currentString = $("#sociallocker_buttons_order").val();
            if (currentString) {
                
                var currentSet = currentString.split(',');
                var originalSet = {};

                socialTabItem.each(function(){
                    var tabId = $(this).data('tab-id');
                    originalSet[tabId] = $(this).detach();
                });
                
                for(var index in currentSet) {
                    var currentId = currentSet[index];
                    socialTabWrap.append(originalSet[currentId]);
                    delete originalSet[currentId];
                }
                
                for(var index in originalSet) {
                    socialTabWrap.append(originalSet[index]);
                }
                
                $(function(){
                    $(socialTabWrap.find("li a").get(0)).tab('show');
                })
            }
            
            // make shortable
            $(".factory-align-vertical .nav-tabs").addClass("ui-sortable");
            $(".factory-align-vertical .nav-tabs").sortable({
                placeholder: "sortable-placeholder",
                opacity: 0.7,
                items: "> li",
                update: function(event, ui) {
                   self.updateButtonOrder();
                }
            });  
            
            // 
            
            socialTabWrap.find('li').each(function(){
                var tabId = $(this).data('tab-id');
                var item = $(this);
                var checkbox = $("#sociallocker_" + tabId + "_available");              
                
                checkbox.change(function(){
                    var isAvailable = checkbox.is(':checked'); 
                                       
                    if (!isAvailable) {
                        item.addClass('factory-disabled');
                    } else {
                        item.removeClass('factory-disabled');
                    }
                    
                    self.updateButtonOrder();
                }).change();
            });
            
            
        },
        
        updateButtonOrder: function(value) {
  
            if (!value) {
                
                var socialTabWrap = $(".factory-align-vertical .nav-tabs");
                
                var resultArray = [];
                socialTabWrap.find('li:not(.sortable-placeholder):not(.factory-disabled)').each(function(){
                    resultArray.push( $(this).data('tab-id') );
                });
                var result = resultArray.join(',');

                $("#sociallocker_buttons_order").val(result).change();
            }
        },
        
        /**
         * Starts to track user input to refresh the preview.
         */
        trackInputChanges: function() {
            var self = this;
            
            var tabs = [
                "#OnpSL_BasicOptionsMetaBox",
                "#OnpSL_SocialOptionsMetaBox",
                "#OnpSL_AdvancedOptionsMetaBox"
            ];
            
            for(var index in tabs) {
                
                $(tabs[index])
                   .find("input, select, textarea")
                   .bind('change keyup', function(){ self.refreshPreview(); });
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
            window.onpsl.preview.refresh( url, 'preview', options, 'onp_sl_update_preview_height' );
        },
        
        /**
         * Gets options for the preview to submit into the frame.
         */
        getPreviewOptions: function() {

            var timer = parseInt( $("#sociallocker_timer").val() );
            var buttons = $("#sociallocker_buttons_order").val();

            var options = {
                
                text: {
                    header: $("#sociallocker_header").val(),
                    message: $("#sociallocker_message").val()   
                },
                
                buttons: {
                    counter: ( $("#sociallocker_show_counters").length === 1 ) 
                                ? $("#sociallocker_show_counters").is(':checked') 
                                : true,
                    order: buttons ? buttons.split(",") : buttons
                },
                
                theme: 'secrets',                
                effects: { 
                    highlight: $("#sociallocker_highlight").is(':checked')
                },
                
                locker: {
                    timer: ( !timer || timer === 0 ) ? null : timer,		
                    close: $("#sociallocker_close").is(':checked'),
                    mobile: $("#sociallocker_mobile").is(':checked')
                },
                
                facebook: {
                    appId: $("#lock-preview-wrap").data('facebook-appid'),
                    lang: $("#lock-preview-wrap").data('lang'),
                    like: {
                        url: $("#sociallocker_facebook_like_url").val(),
                        title: $("#sociallocker_facebook_like_title").val()
                    },
                    share: {
                        url: $("#sociallocker_facebook_share_url").val(),
                        title: $("#sociallocker_facebook_share_title").val(),
                        name: $("#sociallocker_facebook_share_message_name").val(),
                        caption: $("#sociallocker_facebook_share_message_caption").val(),
                        description: $("#sociallocker_facebook_share_message_description").val(),
                        image: $("#sociallocker_facebook_share_message_image").val(),
                        counter: $("#sociallocker_facebook_share_counter_url").val()
                    }
                }, 
                twitter: {
                    lang: $("#lock-preview-wrap").data('short-lang'),
                    tweet: { 
                        url: $("#sociallocker_twitter_tweet_url").val(),
                        text: $("#sociallocker_twitter_tweet_text").val(),
                        title: $("#sociallocker_twitter_tweet_title").val(),
                        counturl: $("#sociallocker_twitter_tweet_counturl").val(),
                        via: $("#sociallocker_twitter_tweet_via").val()              
                    },
                    follow: {
                        url: $("#sociallocker_twitter_follow_url").val(),
                        title: $("#sociallocker_twitter_follow_title").val() 
                    }
                },          
                google: {
                    lang: $("#lock-preview-wrap").data('short-lang'),
                    plus: {
                        url: $("#sociallocker_google_plus_url").val(),
                        title: $("#sociallocker_google_plus_title").val()
                    },   
                    share: {
                        url: $("#sociallocker_google_share_url").val(),
                        title: $("#sociallocker_google_share_title").val()
                    }
                },          
                linkedin: {  
                    share: {
                        url: $("#sociallocker_linkedin_share_url").val(),
                        title: $("#sociallocker_linkedin_share_title").val()
                    }
                },              
                vk: {               
                    appId: "4293274",

                    like: {
                       
                        pageTitle: $("#sociallocker_vk_like_message_name").val(),
                        pageDescription: $("#sociallocker_vk_like_message_description").val(),
                        pageUrl: $("#sociallocker_vk_like_url").val(),
                        pageImage: $("#sociallocker_vk_like_message_image").val(),
                        text: $("#sociallocker_vk_like_message_caption").val(),
                        title: $("#sociallocker_vk_like_title").val()
                    },
                
                    subscribe: {  
                    
                        group_id: $("#sociallocker_vk_subscribe_group_id").val(),
                        title: $("#sociallocker_vk_subscribe_title").val()
                    
                    }               
            },            
            ok: {
                
                pageUrl: $("#sociallocker_ok_class_url").val(),
                title: $("#sociallocker_ok_class_title").val()
                
            }
            };
            
            if (!options.text.header && options.text.message) {
                options.text = options.text.message;
            }

            if ( window['sociallocker-next-build'] != 'free' ) {
                options['theme'] = $("#sociallocker_style").val();
            }
            
            if ( window.onpsl.lockerEditor.filterOptions ) {
                options = window.onpsl.lockerEditor.filterOptions( options );
            }
            
            return options;
        },
        
        initTrialBox: function() {
            
            $("#OnpSL_MoreFeaturesMetaBox ul span").qtip({
                content: {
                    text: function() {
			var className = $(this).data('target');
                        return $("#OnpSL_MoreFeaturesMetaBox ." + className).clone();
                    }
                },
                position: {
                        my: 'center right',
                        at: 'center left',
                        adjust: {
                                x: -45
                        }
                },
                style: {
                        classes: 'qtip-bootstrap advanced-function-demo'
                }
            });
        },
        
        /**
         * Methods to manager manual lockiing options.
         */
        manualLocking: {
          
            init: function() {
                
                $(".onp-sl-shortcode").click(function(){
                    $(this).select();
                });
            }
        },
        
        /**
         * Methods to manager bulk lockiing options.
         */
        bulkLocking: {
            
            wrap: $("#OnpSL_BulkLockingMetaBox"),
            
            /**
             * Inits the bulk locking metabox.
             */
            init: function() {
                var self = this;
                
                $("#onp-sl-bulk-locking-way-selector .btn").click(function(){
                    self.selectWay( $(this).data('name') );
                    return false;
                });
                
                $("#onp-sl-save-bulk-locking-btn").click(function(){
                    self.saveOptions();
                    return false;
                });
                
                this.wrap.find(".onp-sl-cancel").click(function(){
                    self.cancel();
                    return false;
                });
                
                if ( this.wrap.find(".onp-sl-has-options-content").is(".onp-sl-css-selector-state") ) {
                    this.disableVisiblityOptions();
                }
                
                $("#onp-sl-bulk-lock-modal").on("keydown keypress keyup", function(e) {
                    if(e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        $("#onp-sl-save-bulk-locking-btn").click();
                        return false;
                    }            
                });
            },
            
            /**
             * Selects a given bulk locking way by its name.
             */
            selectWay: function( name ) {
                $("#onp-sl-bulk-locking-way-selector .active").removeClass("active");
                $(".onp-sl-bulk-locking-options").addClass('hide');
                
                var $this = $("#onp-sl-bulk-locking-way-selector ." + name);
                $this.addClass("active");
                
                var target = $this.data('target');
                $(target).removeClass('hide');
            },
              
            /**
             * Cancels the bulk locking.
             */
            cancel: function() {
                var self = this;
                
                $("#onp-sl-bulk-lock-options").html("");
                this.enableVisiblityOptions();
                
                this.wrap.find(".onp-sl-setup-section").fadeOut(300, function(){
                    
                    self.wrap.find(".onp-sl-setup-section")
                        .removeClass("onp-sl-has-options-state")
                        .addClass("onp-sl-empty-state");
                
                    self.wrap.addClass("onp-sl-changed"); 
                    self.wrap.find(".onp-sl-interrelation-hint").removeClass("onp-sl-has-options-state");                     
                    self.wrap.find(".onp-sl-setup-section").fadeIn(500);
                });
            },
            
            /**
             * Saves selected bulk locking options.
             */        
            saveOptions: function() {  
                var self = this;
                if ( !this.validateOptions() ) return;
                
                var options = {};
                
                options['way'] = $("#onp-sl-bulk-locking-way-selector .active").data("name");
                if ( options['way'] === "skip-lock" ) {
                    options['skip_number'] = parseInt( $("#onp-sl-skip-lock-options .onp-sl-skip-number").val() );
                } else if ( options['way'] === "css-selector" ) {
                    options['css_selector'] = $("#onp-sl-css-selector-options .onp-sl-css-selector").val();
                }
                
                if ( options['way'] === "skip-lock" || options['way'] === "more-tag" ) {
                    
                    var $base = options['way'] === "skip-lock" 
                        ? $("#onp-sl-skip-lock-options")
                        : $("#onp-sl-more-tags-options");
                    
                    var postTypes = [];
                    $base.find(".onp-sl-post-type:checked").each(function(){
                        postTypes.push( $(this).val() );
                    });
                    options['post_types'] = postTypes.join(', ');
                    options['exclude_posts'] = $.trim( $base.find(".onp-sl-exclude-posts").val() );
                    options['exclude_categories'] = $.trim( $base.find(".onp-sl-exclude-categories").val() );
                
                    this.enableVisiblityOptions();
                } else {
                    this.disableVisiblityOptions();
                }

                $("#onp-sl-bulk-lock-modal").factoryBootstrap320_modal("hide");
                
                // generating hidden fields to save on form submitting
                
                $("#onp-sl-bulk-lock-options").html("");  
                for(var optionName in options) {
                    
                    var $h = $("<input type='hidden' />")
                            .attr('name', "onp_sl_" + optionName)
                            .val(options[optionName]);
                    
                    $("#onp-sl-bulk-lock-options").append($h);
                }
                
                // shows selected values
                
                this.wrap.find(".onp-sl-setup-section").fadeOut(300, function(){
                    
                    self.wrap.find(".onp-sl-setup-section")
                        .removeClass("onp-sl-empty-state")
                        .addClass("onp-sl-has-options-state");

                    self.wrap.find(".onp-sl-has-options-content")
                        .removeClass("onp-sl-skip-lock-state")
                        .removeClass("onp-sl-more-tag-state")
                        .removeClass("onp-sl-css-selector-state")
                        .addClass("onp-sl-" + options['way'] + '-state');

                    if ( options['way'] === "skip-lock" ) {

                        self.wrap.find(".onp-sl-skip-lock-content")
                            .removeClass('onp-sl-skip-lock-0-state')
                            .removeClass('onp-sl-skip-lock-1-state')
                            .removeClass('onp-sl-skip-lock-2-state');

                        if ( options['skip_number'] === 0 ) 
                            self.wrap.find(".onp-sl-skip-lock-content").addClass("onp-sl-skip-lock-0-state");
                        else if ( options['skip_number'] === 1 ) 
                            self.wrap.find(".onp-sl-skip-lock-content").addClass("onp-sl-skip-lock-1-state");
                        else if ( options['skip_number'] > 1 ) {
                            var text = window.onpsl.lang.everyPostWillBeLockedEntirelyExceptFirstsParagraphs.replace("%s", options['skip_number']);
                            self.wrap.find(".onp-sl-skip-lock-2-content").text(text);
                            self.wrap.find(".onp-sl-skip-lock-content").addClass("onp-sl-skip-lock-2-state");
                        }
                        
                    } else if ( options['way'] === 'css-selector' ) {
                        self.wrap.find(".onp-sl-css-selector-view").text( options['css_selector'] );
                    }
                    
                    self.wrap.find(".onp-sl-has-options-content")
                        .removeClass("onp-sl-post-types-rule-state")
                        .removeClass("onp-sl-exclude-post-ids-rule-state")
                        .removeClass("onp-sl-exclude-categories-ids-rule-state");
                    
                    if ( options['way'] === "skip-lock" || options['way'] === "more-tag" ) {
                        
                        var $base = options['way'] === "skip-lock" 
                            ? $("#onp-sl-skip-lock-options")
                            : $("#onp-sl-more-tags-options");
                            
                        self.wrap.find(".onp-sl-post-types-rule").text(
                            window.onpsl.lang.appliesToTypes.replace("%s", options['post_types'])
                        );
                        self.wrap.find(".onp-sl-exclude-post-ids-rule").text(
                            window.onpsl.lang.excludesPosts.replace("%s", options['exclude_posts'])
                        );
                        self.wrap.find(".onp-sl-exclude-categories-ids-rule").text(
                            window.onpsl.lang.excludesCategories.replace("%s", options['exclude_categories'])
                        );
                        
                        self.wrap.find(".onp-sl-has-options-content")
                            .addClass("onp-sl-post-types-rule-state");
                    
                        if ( options['exclude_posts'] ) {
                            self.wrap.find(".onp-sl-has-options-content")
                                .addClass("onp-sl-exclude-post-ids-rule-state");
                        }
                        
                        if ( options['exclude_categories'] ) {
                            self.wrap.find(".onp-sl-has-options-content")
                                .addClass("onp-sl-exclude-categories-ids-rule-state");
                        }     
                    } 

                    self.wrap.find(".onp-sl-setup-section").fadeIn(500);
                    self.wrap.addClass("onp-sl-changed");
                    self.wrap.find(".onp-sl-interrelation-hint").addClass("onp-sl-has-options-state");                    
                });

                return false;
            },
                    
            /**
             * Validates the bulk locking options.
             */ 
            validateOptions: function() {
                this.wrap.find(".has-error").removeClass('has-error');

                var way = $("#onp-sl-bulk-locking-way-selector .active").data("name");
                if ( way === "skip-lock" ) {
                    
                    if ( $("#onp-sl-skip-lock-options .alert").length > 0 ) {
                        $("#onp-sl-skip-lock-options .alert").fadeOut(300, function(){
                            $("#onp-sl-skip-lock-options .alert").fadeIn();
                        });
                        return false;
                    }
                    
                    var skipNumber = parseInt( $("#onp-sl-skip-lock-options .onp-sl-skip-number").val() );
                    if ( isNaN( skipNumber ) || skipNumber < 0 ) {
                        $("#onp-sl-skip-lock-options .onp-sl-skip-number-row").addClass('has-error');
                        return false;
                    }
                    
                    if ( $("#onp-sl-skip-lock-options .onp-sl-post-type:checked").length === 0 ) {
                        $("#onp-sl-skip-lock-options .onp-sl-post-types").addClass('has-error');
                        return false;  
                    }
                    
                } else if ( way === 'more-tag' ) {
                    
                    if ( $("#onp-sl-more-tags-options .alert").length > 0 ) {
                        $("#onp-sl-more-tags-options .alert").fadeOut(300, function(){
                            $("#onp-sl-more-tags-options .alert").fadeIn();
                        });
                        return false;
                    }
                    
                    if ( $("#onp-sl-more-tags-options .onp-sl-post-type:checked").length === 0 ) {
                        $("#onp-sl-more-tags-options .onp-sl-post-types").addClass('has-error');
                        return false;  
                    }
                    
                } else if ( way === 'css-selector' ) {
                    var cssSelector = $.trim( $("#onp-sl-css-selector-options .onp-sl-css-selector").val() );
                    if ( !cssSelector ) {
                        $("#onp-sl-css-selector-options .onp-sl-content").addClass('has-error');
                        return false;
                    }
                    return true;
                }
                return true;
            },
                          
            disableVisiblityOptions: function() {
                var $message = this.wrap.find(".onp-sl-visibility-options-disabled").clone();
                $("#OnpSL_VisabilityOptionsMetaBox .form-horizontal").hide().after($message);
                $message.fadeIn();
            },
                    
            enableVisiblityOptions: function() {
                $("#OnpSL_VisabilityOptionsMetaBox .onp-sl-visibility-options-disabled").remove();
                $("#OnpSL_VisabilityOptionsMetaBox .form-horizontal").fadeIn();
            },             
        },
        
        /**
         * Visability options.
         */
        visability: {
    
            init: function() {
                
                $("#sociallocker_lock_delay").change(function(){
                    if ( $(this).is(":checked") ) {
                        $("#onp-sl-lock-delay-options").hide().removeClass('hide');
                        $("#onp-sl-lock-delay-options").fadeIn();
                    } else {
                        $("#onp-sl-lock-delay-options").hide();
                    }
                });
                
                $("#sociallocker_relock").change(function(){
                    if ( $(this).is(":checked") ) {
                        $("#onp-sl-relock-options").hide().removeClass('hide');
                        $("#onp-sl-relock-options").fadeIn();
                    } else {
                        $("#onp-sl-relock-options").hide();
                    }
                });    
            }
        }
    };
    
    $(function(){
        window.onpsl.lockerEditor.init();
    });
    
})(jQuery)

function sociallocker_editor_callback(e) {
    if ( e.type == 'keyup') {
        tinyMCE.activeEditor.save();
        window.onpsl.lockerEditor.refreshPreview();
    }
    return true;
}


