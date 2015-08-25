if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.socialOptions ) window.bizpanda.socialOptions = {};

(function($){
    
    window.bizpanda.socialOptions = {
        
        init: function() {
            var self = this;
            this.item = $('#opanda_item').val();
            
            this.initSocialTabs();
            this.lockPremiumFeatures();
            
            $.bizpanda.filters.add('opanda-preview-options', function( options ){
                var extraOptions = self.getSocialOptions();
                return $.extend(true, options, extraOptions);
            });
        },

        /**
         * Inits social tabs.
        */
        initSocialTabs: function() {
            var self = this;
            var socialTabWrap = $(".factory-align-vertical .nav-tabs");
            var socialTabItem = $(".factory-align-vertical .nav-tabs li");

            $(".factory-align-vertical .nav-tabs li").click(function(){
                $(".opanda-overlay-tumbler-hint").hide().remove();                    
            });

            // current order

            var currentString = $("#opanda_buttons_order").val();
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
                });
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

            socialTabWrap.find('li').each(function(){
                var tabId = $(this).data('tab-id');
                var item = $(this);
                var checkbox = $("#opanda_" + tabId + "_available");              

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

            // hides/shows the option "Message To Share" of the Facebook Share button

            $("#opanda_facebook_share_dialog").change(function(){
                var checked = $(this).is(":checked");
                if ( checked ) {
                    $("#factory-form-group-message-to-share").hide();
                } else {
                    $("#factory-form-group-message-to-share").fadeIn();
                }
            }).change();                
        },

        updateButtonOrder: function(value) {

            if (!value) {

                var socialTabWrap = $(".factory-align-vertical .nav-tabs");

                var resultArray = [];
                socialTabWrap.find('li:not(.sortable-placeholder):not(.factory-disabled)').each(function(){
                    resultArray.push( $(this).data('tab-id') );
                });
                var result = resultArray.join(',');

                $("#opanda_buttons_order").val(result).change();
            }
        },

        getSocialOptions: function() {
            var buttons = $("#opanda_buttons_order").val();

            var options = {

                groups: {
                    order: ['social-buttons']
                },

                socialButtons: {

                    counters: ( $("#opanda_show_counters").length === 1 ) 
                                ? $("#opanda_show_counters").is(':checked') 
                                : true,

                    order: buttons ? buttons.split(",") : buttons,

                    facebook: {
                        appId: $("#lock-preview-wrap").data('facebook-appid'),
                        lang: $("#lock-preview-wrap").data('lang'),
                        version: $("#lock-preview-wrap").data('facebook-version'),   
                        like: {
                            url: $("#opanda_facebook_like_url").val(),
                            title: $("#opanda_facebook_like_title").val()
                        },
                        share: {
                            shareDialog: $("#opanda_facebook_share_dialog").is(':checked'),
                            url: $("#opanda_facebook_share_url").val(),
                            title: $("#opanda_facebook_share_title").val(),
                            name: $("#opanda_facebook_share_message_name").val(),
                            caption: $("#opanda_facebook_share_message_caption").val(),
                            description: $("#opanda_facebook_share_message_description").val(),
                            image: $("#opanda_facebook_share_message_image").val(),
                            counter: $("#opanda_facebook_share_counter_url").val()
                        }
                    }, 
                    twitter: {
                        lang: $("#lock-preview-wrap").data('short-lang'),
                        tweet: { 
                            url: $("#opanda_twitter_tweet_url").val(),
                            text: $("#opanda_twitter_tweet_text").val(),
                            title: $("#opanda_twitter_tweet_title").val(),
                            counturl: $("#opanda_twitter_tweet_counturl").val(),
                            via: $("#opanda_twitter_tweet_via").val()              
                        },
                        follow: {
                            url: $("#opanda_twitter_follow_url").val(),
                            title: $("#opanda_twitter_follow_title").val(),
                            hideScreenName: $("#opanda_twitter_follow_hide_name").is(':checked')
                        }
                    },          
                    google: {
                        lang: $("#lock-preview-wrap").data('short-lang'),
                        plus: {
                            url: $("#opanda_google_plus_url").val(),
                            title: $("#opanda_google_plus_title").val()
                        },   
                        share: {
                            url: $("#opanda_google_share_url").val(),
                            title: $("#opanda_google_share_title").val()
                        }
                    },
                    youtube: {
                        subscribe: {
                            clientId: window.opanda_google_client_id,
                            channelId: $("#opanda_google_youtube_channel_id").val(),                               
                            title: $("#opanda_google_youtube_title").val()
                        }
                    },
                    linkedin: {
                        share: {
                            url: $("#opanda_linkedin_share_url").val(),
                            title: $("#opanda_linkedin_share_title").val()
                        }
                    }
                }
            };
            console.log( options );
            return options;
        },
        
        lockPremiumFeatures: function() {

            if ( $.inArray( this.item, ['social-locker', 'email-locker', 'signin-locker'] ) === -1 ) return;
            
            $(".factory-tab-item.opanda-not-available").each( function(){ 
                
                var $overlay = $("<div class='opanda-overlay'></div>");
                var $note = $overlay.find(".opanda-premium-note");
                
                $(this).append( $overlay );
                $(this).append( $note );
            });
            
            return;
        }
    };
    
    $(function(){
        window.bizpanda.socialOptions.init();
    });
    
})(jQuery)

