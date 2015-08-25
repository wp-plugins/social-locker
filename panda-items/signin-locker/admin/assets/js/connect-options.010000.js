if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.connectOptions ) window.bizpanda.connectOptions = {};

(function($){
    
    window.bizpanda.connectOptions = {
        
        init: function( item ) {
            var self = this;
            this.item = $('#opanda_item').val();

            this.$control = $(".opanda-connect-buttons");
            this.$buttons = this.$control.find(".opanda-button");
            this.$actions = this.$control.find(".opanda-action");

            this.$result = $("#opanda_connect_buttons");

            $(window).resize(function(){ self.adjustHeights(); });
            self.adjustHeights();
            self.hideEmptyDisabledActions();

            this.initButtons();
            this.setupEvents();
            
            $.bizpanda.filters.add('opanda-preview-options', function( options ){
                var extraOptions = self.getConnectOptions();
                return $.extend(true, options, extraOptions);
            });
        },

        getConnectOptions: function() {

            var gerOrder = function( fieldId ) {
                var actions = $(fieldId).val();
                return actions ? actions.split(",") : []; 
            };

            var order = gerOrder("#opanda_connect_buttons");

            var emailIndex = $.inArray( 'email', order );
            if ( emailIndex > -1 ) { order.splice(emailIndex, 1); } 

            var groups = ( emailIndex > -1 )
                ? ['connect-buttons', 'subscription']
                : ['connect-buttons'];

            var optinMode = $('#opanda_subscribe_mode').val();

            var options = {

                groups: {
                    order: groups
                },

                terms: window.opanda_terms,
                privacyPolicy: window.opanda_privacy_policy,

                connectButtons: {

                    order: order,

                    facebook: {
                        appId: window.opanda_facebook_app_id,
                        actions: gerOrder('#opanda_facebook_actions'),
                    },
                    twitter: {
                        actions: gerOrder('#opanda_twitter_actions'),
                        follow: {
                            user: $("#opanda_twitter_follow_user").val(),
                            notifications: $("#opanda_twitter_follow_notifications").is(':checked') 
                        },
                        tweet: {
                            message: $("#opanda_twitter_tweet_message").val()
                        }
                    },
                    google: {
                        clientId: window.opanda_google_client_id,
                        actions: gerOrder('#opanda_google_actions'),

                        youtubeSubscribe: {
                            channelId: $("#opanda_google_youtube_channel_id").val()
                        }
                    },
                    linkedin: {
                        actions: gerOrder('#opanda_linkedin_actions'),
                        apiKey: window.opanda_linkedin_api_key,

                        follow: {
                            company: $("#opanda_linkedin_follow_company").val()
                        }
                    }
                },

                subscription: {

                    text: {
                        message: $("#opanda_subscribe_before_form").val()
                    },

                    form: {
                        buttonText: $("#opanda_subscribe_button_text").val(),
                        noSpamText: $("#opanda_subscribe_after_button").val(),
                        
                        type: $("#opanda_subscribe_name").is(':checked') ? 'name-email-form' : 'email-form'
                    }
                },

                subscribeActionOptions: {                        
                    campaignId: $("#opanda_subscribe_list").length ? $("#opanda_subscribe_list").val() : null,
                    service: window.opanda_subscription_service_name,
                    doubleOptin: $.inArray( optinMode, ['quick-double-optin', 'double-optin'] > -1),
                    confirm: $.inArray( optinMode, ['double-optin'] > -1)
                }
            };

            return options;
        },

        adjustHeights: function() {

            var maxHeight = 0;
            var $buttons = this.$buttons.find(".opanda-actions:not(.opanda-actions-disabled)").each(function(){
                var height = $(this).css('height', 'auto').height();
                if ( height > maxHeight ) maxHeight = height;
            });

            $buttons.height(maxHeight);
        },

        hideEmptyDisabledActions: function() {

            $(".opanda-actions-disabled").each(function(){
                if ( $(this).find(".opanda-action").length > 0 ) return;
                $(this).hide();
            });
        },

        initButtons: function() {
            var self = this;

            var stringResult = this.$result.val();
            if (!stringResult) stringResult = null;

            var buttons = stringResult.split(',');
            for( var index in buttons ) {
                var buttonName = buttons[index];
                this.activateButton( buttonName, true );
            }

            this.$buttons.each(function(){
                self.initButtonActions( $(this).data('name') )
            });

            this.initActionSaveEmail();
            this.updateResult();
        },

        initActionSaveEmail: function () {


            $("input[data-action='lead']").change(function(){
                $("#opanda_catch_leads").val( $(this).is(":checked") ? "1" : "0" );
            });

            if ( $("#opanda_catch_leads").val() == "1" ) {
                $("input[data-action='lead']").attr('checked', 'checked');
            } else {
                $("input[data-action='lead']").removeAttr('checked');
            }
        },

        initButtonActions: function( buttonName ) {

            var stringResult = $("#opanda_" + buttonName + "_actions").val();
            if (!stringResult) stringResult = null;

            if ( stringResult ) {
                var actions = stringResult.split(',');
                for( var index in actions ) {
                    var actionName = actions[index];
                    this.activateButtonAction( buttonName, actionName, true );
                }
            }
        },

        setupEvents: function() {
            var self = this;

            this.$buttons.find(".opanda-button-title input").change(function(){
                self.toogleButton( $(this).val() );
            });

            this.$buttons.find(".opanda-action input").change(function(){

                var common = $(this).data('common') ? true : false;  
                var button = $(this).data('button');
                var action = $(this).data('action');

                if ( $(this).is(':checked') ) {
                    self.showOptions( common, button, action );
                }

                var $input = self.getOptionsLink( common, button, action ).find("input");

                if ( $(this).is(':checked') ) {
                    $input.attr('checked', 'checked');
                } else {
                    $input.removeAttr('checked', 'checked');
                }

                self.updateResult();
                return false;
            });    

            this.$buttons.find(".opanda-action .opanda-action-link").click(function(){

                var common = $(this).data('common') ? true : false;  
                var button = $(this).data('button');
                var action = $(this).data('action');

                self.toogleOptions( common, button, action );
                return false;
            });

            this.$buttons.find(".opanda-action .opanda-action-link").hover(function(){

                var common = $(this).data('common') ? true : false;  
                var button = $(this).data('button');
                var action = $(this).data('action');

                var $link = self.getOptionsLink( common, button, action );
                $link.addClass('opanda-hover');

            }, function(){

                var common = $(this).data('common') ? true : false;  
                var button = $(this).data('button');
                var action = $(this).data('action');

                var $link = self.getOptionsLink( common, button, action );
                $link.removeClass('opanda-hover');
            });    
        },

        /**
         * Gets the button $object.
         */
        getButton: function( name ) {
            return this.$control.find(".opanda-button-" + name);
        },

        /**
         * Activates or deactivates the button.
         */
        toogleButton: function( name ) {

            var $button = this.getButton( name );
            if ( $button.hasClass('opanda-on') ) this.deactivateButton( name );
            else this.activateButton( name );
        },

        /**
         * Activates the connect button.
         */
        activateButton: function( name, setup ) {

            var $button = this.getButton( name );
            if ( $button.is(".opanda-has-error") ) return;
            $button.removeClass('opanda-off').addClass('opanda-on');

            $button.find(".opanda-actions .opanda-action:not(.opanda-action-disabled) input").removeAttr('disabled');
            $button.find(".opanda-button-title input").attr('checked', 'checked');

            if ( !setup ) this.updateResult();
        },

        /**
         * Deactivates the button.
         */
        deactivateButton: function( name, setup ) {

            var $button = this.getButton( name );
            $button.removeClass('opanda-on').addClass('opanda-off');

            $button.find(".opanda-actions input").attr('disabled', 'disabled');
            $button.find(".opanda-button-title input").removeAttr('checked');

            if ( !setup ) this.updateResult();
        },

        /**
         * Activates the button action.
         */
        activateButtonAction: function( buttonName, actionName, setup) {

            var $button = this.getButton( buttonName );

            var $action = $button.find('.opanda-action-' + actionName);
            if ( $action.is('.opanda-action-disabled') ) return;

            $action.find('input').attr('checked', 'checked');

            if ( !setup ) this.updateActionsResult( buttonName, actionName );
        },

        /**
         * Deactivates the button action.
         */
        deactivateButtonAction: function( buttonName, actionName, setup) {

            var $button = this.getButton( buttonName );
            $button.find('.opanda-action-' + actionName + ' inpput').removeAttr('checked');

            if ( !setup ) this.updateActionsResult( buttonName, actionName );
        },

        /**
         * Gets the options $object.
         */
        getOptions: function( common, button, action ) {

            if ( common ) {
                return $( "#opanda-" + action + "-options" );
            } else {
                return $( "#opanda-" + button + "-" + action + "-options" );
            }
        },

        /**
         * Gets the options link $object.
         */
        getOptionsLink: function( common, button, action ) {

            if ( common ) {
                return this.$control.find(".opanda-action-" + action);
            } else {
                return this.$control.find(".opanda-button-" + button + " .opanda-action-" + action);
            }
        },      

        /**
         * Shows or hides the options.
         */
        toogleOptions: function( common, button, action ) {

            var $options = this.getOptions( common, button, action );
            if ( !$options.is(":visible") ) this.showOptions( common, button, action );
            else this.hideOptions( common, button, action );
        },

        /**
         * Shows the action options.
         */
        showOptions: function( common, button, action ) {
            $(".opanda-connect-buttons-options").addClass('opanda-off');
            this.$actions.removeClass('opanda-on'); 

            var $options = this.getOptions( common, button, action );
            $options.hide().removeClass('opanda-off').fadeIn(300);

            var $link = this.getOptionsLink( common, button, action );
            $link.addClass('opanda-on');
        },

        /**
         * Hides the action options.
         */
        hideOptions: function( common, button, action ) {
            $(".opanda-connect-buttons-options").addClass('opanda-off');
            this.$actions.removeClass('opanda-on'); 
        },

        /**
         * Updates the hidden field where the available buttons are saved.
         */
        updateResult: function() {
            var buttons = [];

            $(".opanda-connect-buttons .opanda-button.opanda-on").each(function(){
                buttons.push( $(this).data('name') );
            });

            this.$result.val( buttons.join(',') );

            for( var i in buttons ) {
                this.updateActionsResult( buttons[i] );
            }
        },

        /**
         * Updates the hidden field where the button actions are saved.
         */
        updateActionsResult: function( buttonName ) {
            var actions = [];

            $(".opanda-connect-buttons .opanda-button-" + buttonName + " .opanda-action input:checked").each(function(){
                actions.push( $(this).data('action') );
            });

            $("#opanda_" + buttonName + "_actions").val( actions.join(',') );
        }
    };
    
    $(function(){
        window.bizpanda.connectOptions.init();
    });
    
})(jQuery)

