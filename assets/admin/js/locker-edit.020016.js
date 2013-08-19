var lockerEditor = {};

(function($){
    
    lockerEditor = {
        
        init: function() {
            
            this.initSocialTabs();
            
            this.trackInputChanges();
            this.recreatePreview();

            if ( window['sociallocker-next-build'] == 'free' ) {
                this.initTrialBox();
            }
        },
        
        /**
         * Inits social tabs.
         */
        initSocialTabs: function() {
            var self = this;
            var socialTabWrap = $(".pi-vertical-tabs .nav-tabs");
            var socialTabItem = $(".pi-vertical-tabs .nav-tabs li");
            
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
            
            $(".pi-vertical-tabs .nav-tabs").sortable({
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
                        item.addClass('pi-tab-gray');
                    } else {
                        item.removeClass('pi-tab-gray');
                    }
                    
                    self.updateButtonOrder();
                }).change();
            });
            
            
        },
        
        updateButtonOrder: function(value) {
  
            if (!value) {
                
                var socialTabWrap = $(".pi-vertical-tabs .nav-tabs");
                
                var resultArray = [];
                socialTabWrap.find('li:not(.sortable-placeholder):not(.pi-tab-gray)').each(function(){
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
                "#SocialLockerBasicOptionsMetaBox",
                "#SocialLockerSocialOptionsMetaBox",
                "#SocialLockerFunctionOptionsMetaBox"
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

            }, 700);
        },
        
        /**
         * Recreates the preview, submmits forms to the preview frame.
         */
        recreatePreview: function() {

            var previewWrap = $("#lock-preview-wrap")
            var previewOptions = this.getPreviewOptions();

            if ( this.previewForm ) {
                this.previewForm.remove(); 
                this.previewForm = null;
            }

            this.previewForm = $("<form method='post' target='preview'></form>");
            this.previewForm.attr('action', previewWrap.data('url'));
            this.createFormFields(null, this.previewForm, previewOptions);
            this.previewForm.appendTo($("body"));
            this.previewForm.submit();
        },
        
        /**
         * Creates fields for the preview form to submit
         */
        createFormFields: function(base, form, values) {
            
            for( propName in values ) {

                if (typeof(values[propName]) === 'object') {
                    this.createFormFields((base ? (base + "_" + propName) : propName), form, values[propName]);
                } else { 
                    if (values[propName] == null) continue;
                    form.append(
                        jQuery("<input type='hidden' name='" + 
                            (base ? (base + "_" + propName) : propName)  + "' value='" + 
                            values[propName] + "' />")
                    );
                } 
            }
        },
        
        /**
         * Gets options for the preview to submit into the frame.
         */
        getPreviewOptions: function() {

            var timer = parseInt( $("#sociallocker_timer").val() );

            var preview_options = {
                
                text: {
                    header: escape($("#sociallocker_header").val()),
                    message: escape($("#sociallocker_message").val())   
                },
                
                buttons: {
                    order: $("#sociallocker_buttons_order").val()
                },
                
                theme: 'secrets',
                effects: { 
                    highlight: $("#sociallocker_highlight").is(':checked')
                },
                
                locker: {
                    timer: ( !timer || timer == 0 ) ? null : timer,		
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
                        counturl: $("#sociallocker_twitter_tweet_counturl").val()
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
                }
            };

            if ( window['sociallocker-next-build'] != 'free' ) {
                preview_options['theme'] = $("#sociallocker_style").val();
            }
            
            return preview_options;     

        },
        
        initTrialBox: function() {
            
            $("#SociallockerMoreFeatures ul span").qtip({
                content: {
                    text: function() {
			var className = $(this).data('target');
                        return $("#SociallockerMoreFeatures ." + className).clone();
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
        }
    }
    
    $(function(){
        lockerEditor.init();
    });
    
})(jQuery)

function sociallocker_editor_callback(e) {
    if ( e.type == 'keyup') {
        tinyMCE.activeEditor.save();
        lockerEditor.refreshPreview();
    }
    return true;
}


