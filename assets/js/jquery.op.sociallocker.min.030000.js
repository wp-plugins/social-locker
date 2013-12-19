/**
 * Preset resources for Social Locker
 * for jQuery: http://onepress-media.com/plugin/social-locker-for-jquery/get
 * for Wordpress: http://onepress-media.com/plugin/social-locker-for-wordpress/get
 *
 * Copyright 2013, OnePress, http://onepress-media.com/portfolio
 * Help Desk: http://support.onepress-media.com/
 */

(function ($) {

    /**
    * Text resources.
    */

    if (!$.onepress) $.onepress = {};
    if (!$.onepress.sociallocker) $.onepress.sociallocker = {};
    if (!$.onepress.sociallocker.lang) $.onepress.sociallocker.lang = {};

    $.onepress.sociallocker.lang = {

        defaultHeader:      "This content is locked!",
        defaultMessage:     "Please support us, use one of the buttons below to unlock the content.",
        orWait:             'or wait',
        seconds:            's',   
        close:              'Close',
        
        // default button labels
        facebook_like:      'like us',
        facebook_share:     'share',
        twitter_tweet:      'tweet',  
        twitter_follow:     'follow us on twitter',  
        google_plus:        '+1 us',  
        google_share:       'share',
        linkedin_share:     'share'
    };
    
    /**
    * Available buttons
    */
   
    if (!$.onepress.sociallocker.buttons) $.onepress.sociallocker.buttons = {};
    $.onepress.sociallocker.buttons = [
        "facebook-like",
        "facebook-share",
        "google-plus",
        "google-share",
        "twitter-tweet",
        "twitter-follow",
        "linkedin-share",    
        "#"
    ]

    /**
    * Presets options for styles.
    * You can add some options that will be applied when a specified theme is used.
    */

    if (!$.onepress.sociallocker.presets) $.onepress.sociallocker.presets = {};
    
    /* starter theme */

    $.onepress.sociallocker.presets['starter'] = {
        
        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: false
        }
    };
    
    /* secrets theme */
    
    $.onepress.sociallocker.presets['secrets'] = {

        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: true
        },
        
        triggers: {
            overlayRender: function(options, networkName, buttonName, isTouch){
                var overlay = isTouch ? $("<a></a>") : $("<div></div>");
                var title = options.title || $.onepress.sociallocker.lang[networkName + "_" + buttonName];
                
                overlay.addClass("onp-sociallocker-button-overlay") 
                      .append(
                       $("<div class='onp-sociallocker-overlay-front'></div>")
                            .append($("<div class='onp-sociallocker-overlay-icon'></div>"))
                            .append($("<div class='onp-sociallocker-overlay-text'>" + title + "</div>"))
                       )
                      .append($("<div class='onp-sociallocker-overlay-header'></div>"))
                      .append($("<div class='onp-sociallocker-overlay-back'></div>"));
                
                return overlay;
            }
        }
    };
    
    /* dandyish theme */
    
    $.onepress.sociallocker.presets['dandyish'] = {

        buttons: {
            layout: 'vertical',
            counter: true,
            unsupported: ['twitter-follow']
        },
        effects: {
            flip: false
        }
    };
    
    /* glass theme */
    
    $.onepress.sociallocker.presets['glass'] = {
        _iPhoneBug: false,
        
        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: false
        }
    };

})(jQuery);;;

(function(a){if(!a.onepress){a.onepress={};}if(!a.onepress.tools){a.onepress.tools={};}a.onepress.tools.cookie=a.onepress.tools.cookie||function(e,k,f){if(arguments.length>1&&(!/Object/.test(Object.prototype.toString.call(k))||k===null||k===undefined)){f=a.extend({},f);if(k===null||k===undefined){f.expires=-1;}if(typeof f.expires==="number"){var b=f.expires,j=f.expires=new Date();j.setDate(j.getDate()+b);}k=String(k);return(document.cookie=[encodeURIComponent(e),"=",f.raw?k:encodeURIComponent(k),f.expires?"; expires="+f.expires.toUTCString():"",f.path?"; path="+f.path:"",f.domain?"; domain="+f.domain:"",f.secure?"; secure":""].join(""));}f=k||{};var c=f.raw?function(i){return i;}:decodeURIComponent;var h=document.cookie.split("; ");for(var d=0,g;g=h[d]&&h[d].split("=");d++){if(c(g[0])===e){return c(g[1]||"");}}return null;};a.onepress.tools.hash=a.onepress.tools.hash||function(e){var c=0;if(e.length==0){return c;}for(var d=0;d<e.length;d++){var b=e.charCodeAt(d);c=((c<<5)-c)+b;c=c&c;}c=c.toString(16);c=c.replace("-","0");return c;};a.onepress.tools.has3d=a.onepress.tools.has3d||function(){var b=document.createElement("p"),c,e={WebkitTransform:"-webkit-transform",OTransform:"-o-transform",MSTransform:"-ms-transform",MozTransform:"-moz-transform",Transform:"transform"};document.body.insertBefore(b,null);for(var d in e){if(b.style[d]!==undefined){b.style[d]="translate3d(1px,1px,1px)";c=window.getComputedStyle(b).getPropertyValue(e[d]);}}document.body.removeChild(b);return(c!==undefined&&c.length>0&&c!=="none");};a.onepress.isTouch=a.onepress.isTouch||function(){return !!("ontouchstart" in window)||!!("onmsgesturechange" in window);};a.onepress.widget=function(c,d){var b={createWidget:function(e,f){var g=a.extend(true,{},d);g.element=a(e);g.options=a.extend(true,g.options,f);if(g._init){g._init();}if(g._create){g._create();}a.data(e,"plugin_"+c,g);},callMethod:function(f,e){f[e]&&f[e]();}};a.fn[c]=function(){var e=arguments;var f=arguments.length;this.each(function(){var g=a.data(this,"plugin_"+c);if(!g&&f<=1){b.createWidget(this,f?e[0]:false);}else{if(f==1){b.callMethod(g,e[0]);}}});};};})(jQuery);

/*
 * URL.js
 *
 * Copyright 2011 Eric Ferraiuolo
 * https://github.com/ericf/urljs
 */
var URL=function(){var a=this;if(!(a&&a.hasOwnProperty&&(a instanceof URL))){a=new URL();}return a._init.apply(a,arguments);};(function(){var a="absolute",v="relative",m="http",n="https",d=":",y="//",c="@",e=".",x="/",f="..",g="../",u="?",i="=",b="&",k="#",h="",C="type",w="scheme",G="userInfo",l="host",s="port",r="path",t="query",j="fragment",F=/^(?:(https?:\/\/|\/\/)|(\/|\?|#)|[^;:@=\.\s])/i,D=/^(?:(https?):\/\/|\/\/)(?:([^:@\s]+:?[^:@\s]+?)@)?((?:[^;:@=\/\?\.\s]+\.)+[A-Za-z0-9\-]{2,})(?::(\d+))?(?=\/|\?|#|$)([^\?#]+)?(?:\?([^#]+))?(?:#(.+))?/i,E=/^([^\?#]+)?(?:\?([^#]+))?(?:#(.+))?/i,q="object",z="string",B=/^\s+|\s+$/g,A,o,p;A=String.prototype.trim?function(H){return(H&&H.trim?H.trim():H);}:function(I){try{return I.replace(B,h);}catch(H){return I;}};o=function(H){return(H&&typeof H===q);};p=function(H){return typeof H===z;};URL.ABSOLUTE=a;URL.RELATIVE=v;URL.normalize=function(H){return new URL(H).toString();};URL.resolve=function(H,I){return new URL(H).resolve(I).toString();};URL.prototype={_init:function(H){this.constructor=URL;H=p(H)?H:H instanceof URL?H.toString():null;this._original=H;this._url={};this._isValid=this._parse(H);return this;},toString:function(){var M=this._url,N=[],L=M[C],K=M[w],I=M[r],J=M[t],H=M[j];if(L===a){N.push(K?(K+d+y):y,this.authority());if(I&&I.indexOf(x)!==0){I=x+I;}}N.push(I,J?(u+this.queryString()):h,H?(k+H):h);return N.join(h);},original:function(){return this._original;},isValid:function(){return this._isValid;},isAbsolute:function(){return this._url[C]===a;},isRelative:function(){return this._url[C]===v;},isHostRelative:function(){var H=this._url[r];return(this.isRelative()&&H&&H.indexOf(x)===0);},type:function(){return this._url[C];},scheme:function(H){return(arguments.length?this._set(w,H):this._url[w]);},userInfo:function(H){return(arguments.length?this._set(G,H):this._url[G]);},host:function(H){return(arguments.length?this._set(l,H):this._url[l]);},domain:function(){var H=this._url[l];return(H?H.split(e).slice(-2).join(e):undefined);},port:function(H){return(arguments.length?this._set(s,H):this._url[s]);},authority:function(){var J=this._url,K=J[G],H=J[l],I=J[s];return[K?(K+c):h,H,I?(d+I):h,].join(h);},path:function(H){return(arguments.length?this._set(r,H):this._url[r]);},query:function(H){return(arguments.length?this._set(t,H):this._url[t]);},queryString:function(K){if(arguments.length){return this._set(t,this._parseQuery(K));}K=h;var J=this._url[t],H,I;if(J){for(H=0,I=J.length;H<I;H++){K+=J[H].join(i);if(H<I-1){K+=b;}}}return K;},fragment:function(H){return(arguments.length?this._set(j,H):this._url[j]);},resolve:function(J){J=(J instanceof URL)?J:new URL(J);var I,H;if(!(this.isValid()&&J.isValid())){return this;}if(J.isAbsolute()){return(this.isAbsolute()?J.scheme()?J:new URL(J).scheme(this.scheme()):J);}I=new URL(this.isAbsolute()?this:null);if(J.path()){if(J.isHostRelative()||!this.path()){H=J.path();}else{H=this.path().substring(0,this.path().lastIndexOf(x)+1)+J.path();}I.path(this._normalizePath(H)).query(J.query()).fragment(J.fragment());}else{if(J.query()){I.query(J.query()).fragment(J.fragment());}else{if(J.fragment()){I.fragment(J.fragment());}}}return I;},reduce:function(I){I=(I instanceof URL)?I:new URL(I);var H=this.resolve(I);if(this.isAbsolute()&&H.isAbsolute()){if(H.scheme()===this.scheme()&&H.authority()===this.authority()){H.scheme(null).userInfo(null).host(null).port(null);}}return H;},_parse:function(J,I){J=A(J);if(!(p(J)&&J.length>0)){return false;}var K,H;if(!I){I=J.match(F);I=I?I[1]?a:I[2]?v:null:null;}switch(I){case a:K=J.match(D);if(K){H={};H[C]=a;H[w]=K[1]?K[1].toLowerCase():undefined;H[G]=K[2];H[l]=K[3].toLowerCase();H[s]=K[4]?parseInt(K[4],10):undefined;H[r]=K[5]||x;H[t]=this._parseQuery(K[6]);H[j]=K[7];}break;case v:K=J.match(E);if(K){H={};H[C]=v;H[r]=K[1];H[t]=this._parseQuery(K[2]);H[j]=K[3];}break;default:return(this._parse(J,a)||this._parse(J,v));break;}if(H){this._url=H;return true;}else{return false;}},_parseQuery:function(M){if(!p(M)){return;}M=A(M);var J=[],L=M.split(b),K,H,I;for(H=0,I=L.length;H<I;H++){if(L[H]){K=L[H].split(i);J.push(K[1]?K:[K[0]]);}}return J;},_set:function(H,I){this._url[H]=I;if(I&&(H===w||H===G||H===l||H===s)){this._url[C]=a;}if(!I&&H===l){this._url[C]=v;}this._isValid=this._parse(this.toString());return this;},_normalizePath:function(K){var M,L,N,J,H,I;if(K.indexOf(g)>-1){M=K.split(x);N=[];for(H=0,I=M.length;H<I;H++){L=M[H];if(L===f){N.pop();}else{if(L){N.push(L);}}}J=N.join(x);if(K[0]===x){J=x+J;}if(K[K.length-1]===x&&J.length>1){J+=x;}}else{J=K;}return J;}};}());
            
/*
 * jQuery Migrate - v1.2.1 - 2013-05-08
 * https://github.com/jquery/jquery-migrate
 * Copyright 2005, 2013 jQuery Foundation, Inc. and other contributors; Licensed MIT
 */
if(!jQuery.uaMatch){jQuery.uaMatch=function(b){b=b.toLowerCase();var a=/(chrome)[ \/]([\w.]+)/.exec(b)||/(webkit)[ \/]([\w.]+)/.exec(b)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(b)||/(msie) ([\w.]+)/.exec(b)||b.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(b)||[];return{browser:a[1]||"",version:a[2]||"0"};};}if(!jQuery.browser){matched=jQuery.uaMatch(navigator.userAgent);browser={};if(matched.browser){browser[matched.browser]=true;browser.version=matched.version;}if(browser.chrome){browser.webkit=true;}else{if(browser.webkit){browser.safari=true;}}jQuery.browser=browser;}(function(a){if(a.fn.sociallocker_facebook_like){return;}a.onepress.widget("sociallocker_facebook_like",{options:{},_loggedIntoFacebook:false,_defaults:{url:null,appId:0,lang:"en_US",layout:"standart",width:"auto",verbToDisplay:"like",colorScheme:"light",font:"tahoma",ref:null,count:"standart",unlock:null},_create:function(){var b=this;this._prepareOptions();this._setupEvents();this.element.data("onepress-facebookButton",this);this._createButton();a.onepress.connector.connect("facebook",this.options,function(c){c.render(b.element);});},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options);this.url=URL.normalize((!this.options.url)?window.location.href:this.options.url);},_setupEvents:function(){var b=this;a(document).bind("fb-like",function(c,d){if(b.options.unlock&&b.url==URL.normalize(d)){b.options.unlock(d,b);}});},_createButton:function(){var b=this;this.button=a("<div class='fake-fb-like'></div>");this.wrap=a("<div class='onp-social-button onp-facebook-button'></div>").appendTo(this.element).append(this.button);if(this.options.count=="none"){this.wrap.addClass("onp-facebook-like-count-none");this.wrap.addClass("onp-facebook-like-"+this.options.lang);}this.button.data("facebook-widget",this);this.button.attr("data-show-faces",false);this.button.attr("data-send",false);if(this.options.url){this.button.attr("data-href",this.options.url);}if(this.options.font){this.button.attr("data-font",this.options.font);}if(this.options.colorScheme){this.button.attr("data-colorscheme",this.options.colorScheme);}if(this.options.ref){this.button.attr("data-ref",this.options.ref);}if(this.options.width){this.button.attr("data-width",this.options.width);}if(this.options.layout){this.button.attr("data-layout",this.options.layout);}if(this.options.verbToDisplay){this.button.attr("data-action",this.options.verbToDisplay);}},getHtmlToRender:function(){return this.wrap;}});})(jQuery);(function(a){if(a.fn.sociallocker_facebook_button){return;}a.onepress.widget("sociallocker_facebook_share",{options:{},_defaults:{url:null,counter:null,appId:0,layout:"standard",count:"standart",name:null,caption:null,description:null,image:null,unlock:null},_create:function(){var b=this;this._prepareOptions();this.element.data("onepress-facebookButton",this);this._createButton();a.onepress.connector.connect("facebook",this.options,function(c){c.render(b.element);});},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options);this.url=URL.normalize((!this.options.url)?window.location.href:this.options.url);},_createButton:function(){var b=0;var c=this;this.button=a("<div class='onp-facebook-share onp-facebook-layout-"+this.options.layout+"'></div>");this.button.append(a("<a href='#' class='onp-facebook-share-icon'></a>"));this.button.append(a("<div class='onp-facebook-share-count'>"+b+"</div>"));this.button.data("facebook-widget",this);this.wrap=a("<div class='onp-social-button onp-facebook-button'></div>").appendTo(this.element).append(this.button);if(this.options.count=="none"){this.button.addClass("onp-facebook-share-count-none");}this.button.click(function(){FB.ui({method:"feed",name:c.options.name,link:c.url,picture:c.options.image,caption:c.options.caption,description:c.options.description},function(d){if(d&&d.post_id){c.button.find(".onp-facebook-share-count").text(c.getCountLabel(b++));c.options.unlock&&c.options.unlock(c.url,c);}});return false;});a.onepress.connector.connect("facebook",this.options,function(d){var e=c.options.counter?c.options.counter:c.url;window.FB.api({method:"fql.query",query:"SELECT share_count FROM link_stat WHERE url = '"+encodeURIComponent(e)+"'"},function(f){if(f.error_code){return;}b=c.getCountLabel(f[0].share_count||b);c.button.find(".onp-facebook-share-count").text(b);});});},getCountLabel:function(b){if(b>=1000000){return Math.floor(b/1000000)+"m";}if(b>=1000){return Math.floor(b/1000)+"k";}return b;},getHtmlToRender:function(){return this.wrap;}});})(jQuery);(function(a){if(a.fn.sociallocker_google_button){return;}a.onepress.widget("sociallocker_google_button",{options:{},_defaults:{url:null,type:null,lang:"en-US",size:null,annotation:null,width:null,align:"left",expandTo:"",recommendations:true,unlock:null},_create:function(){var b=this;this._prepareOptions();this._setupEvents();this.element.data("onepress-googleButton",this);this._createButton();a.onepress.connector.connect("google",this.options,function(c){c.render(b.element);});},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options);this.url=URL.normalize((!this.options.url)?window.location:this.options.url);},_setupEvents:function(){var b=this;if(this.options.type=="plus"){a(document).bind("gl-plus",function(c,d){a(".gc-bubbleDefault").hide();if(b.options.unlock&&(b.url==URL.normalize(d))){b.options.unlock(d,b);}});}if(this.options.type=="share"){a(document).bind("gl-share",function(c,d){a(".gc-bubbleDefault").hide();if(b.options.unlock&&(b.url==URL.normalize(d))){b.options.unlock(d,b);}});}},_createButton:function(){this.button=(this.options.type=="plus")?a("<div class='fake-g-plusone'></div>"):a("<div class='fake-g-share'></div>");this.wrap=a("<div class='onp-social-button onp-google-button'></div>").appendTo(this.element).append(this.button);this.button.data("google-widget",this);if(this.options.url){this.button.attr("data-href",this.options.url);}if(this.options.size){this.button.attr("data-size",this.options.size);}if(this.options.annotation){this.button.attr("data-annotation",this.options.annotation);}if(this.options.align){this.button.attr("data-align",this.options.align);}if(this.options.expandTo){this.button.attr("data-expandTo",this.options.expandTo);}if(this.options.recommendations){this.button.attr("data-recommendations",this.options.recommendations);}},getHtmlToRender:function(){return this.wrap;}});a.fn.sociallocker_google_plus=function(b){b.type="plus";return a(this).sociallocker_google_button(b);};a.fn.sociallocker_google_share=function(b){b.type="share";return a(this).sociallocker_google_button(b);};})(jQuery);(function(a){if(a.fn.sociallocker_twitter_button){return;}a.onepress.widget("sociallocker_twitter_button",{options:{},_defaults:{url:null,type:null,text:null,via:null,showScreenName:false,related:null,count:"horizontal",showCount:true,lang:"en",counturl:null,size:"large",unlock:null},_create:function(){var b=this;this._prepareOptions();this._setupEvents();this.element.data("onepress-twitterButton",this);this._createButton();a.onepress.connector.connect("twitter",this.options,function(c){c.render(b.element);});},_prepareOptions:function(){var c=a.extend({},this._defaults);for(var b in this._defaults){if(this.element.data(b)!==undefined){c[b]=this.element.data(b);}}this.options=a.extend(c,this.options);if(!this.options.url&&a("link[rel='canonical']").length>0){this.options.url=a("link[rel='canonical']").attr("href");}this.url=URL.normalize(this.options.url||window.location.href);},_setupEvents:function(){var b=this;a(document).bind("tw-tweet",function(d,f,c){if(b.options.type!="tweet"){return;}var g=URL.normalize(a(f).parent().attr("data-url-to-compare"));if(b.url==g&&b.options.unlock){b.options.unlock&&b.options.unlock(g,b);}});a(document).bind("tw-follow",function(d,f,c){if(b.options.type!="follow"){return;}var g=URL.normalize(a(f).parent().attr("data-url-to-compare"));if(b.url==g&&b.options.unlock){b.options.unlock&&b.options.unlock(g,b);}});},_createButton:function(){var b;if(this.options.type=="follow"){b="Follow Me";if(!this.options.url){b="[Error] Setup an URL of your Twitter account.";}}else{b="Tweet";}this.button=a("<a href='https://twitter.com/share'>"+b+"</a>");this.button.data("twitter-widget",this);this.wrap=a("<div class='onp-social-button onp-twitter-button'></div>").appendTo(this.element).append(this.button);if(this.options.type=="tweet"){this.wrap.addClass("onp-twitter-tweet");this.button.addClass("twitter-share-button");}if(this.options.type=="follow"){this.wrap.addClass("onp-twitter-follow");this.button.addClass("twitter-follow-button");}if(this.options.type=="follow"){this.button.attr("href",this.url);}else{this.button.attr("data-url",this.url);}this.button.attr("data-show-count",this.options.showCount);if(this.options.via){this.button.attr("data-via",this.options.via);}if(this.options.text){this.button.attr("data-text",this.options.text);}if(this.options.related){this.button.attr("data-related",this.options.related);}if(this.options.count){this.button.attr("data-count",this.options.count);}if(this.options.showCount){this.button.attr("data-show-count",this.options.showCount);}if(this.options.lang){this.button.attr("data-lang",this.options.lang);}if(this.options.counturl){this.button.attr("data-counturl",this.options.counturl);}if(this.options.hashtags){this.button.attr("data-hashtags",this.options.hashtags);}if(this.options.alignment){this.button.attr("data-alignment",this.options.alignment);}if(this.options.size){this.button.attr("data-size",this.options.size);}if(this.options.dnt){this.button.attr("data-dnt",this.options.dnt);}if(this.options.showScreenName){this.button.attr("data-show-screen-name",this.options.showScreenName);}this.wrap.attr("data-url-to-compare",this.url);},getHtmlToRender:function(){return this.button;}});a.fn.sociallocker_twitter_tweet=function(b){b.type="tweet";return a(this).sociallocker_twitter_button(b);};a.fn.sociallocker_twitter_follow=function(b){b.type="follow";return a(this).sociallocker_twitter_button(b);};})(jQuery);(function(a){if(a.fn.sociallocker_linkedin_share){return;}a.onepress.widget("sociallocker_linkedin_share",{options:{},_defaults:{url:null,counter:"right",unlock:null},_create:function(){var b=this;this._prepareOptions();this._setupEvents();this._createButton();this.element.data("linkedin-widget",this);a.onepress.connector.connect("linkedin",this.options,function(c){c.render(b.element);});},_prepareOptions:function(){var c=a.extend({},this._defaults);for(var b in this._defaults){if(this.element.data(b)!==undefined){c[b]=this.element.data(b);}}this.options=a.extend(c,this.options);this.url=URL.normalize(this.options.url||window.location.href);},_setupEvents:function(){var b=this;a(document).bind("ln-share",function(c,d){if(b.url==URL.normalize(d)&&b.options.unlock){b.options.unlock&&b.options.unlock(d,b);}});},_createButton:function(){this.button=a('<script type="IN/Share"></script>');this.wrap=a("<div class='onp-social-button onp-linkedin-button'></div>").appendTo(this.element).append(this.button);if(this.options.counter){this.button.attr("data-counter",this.options.counter);}if(this.options.url){this.button.attr("data-url",this.url);}},getHtmlToRender:function(){return this.button;}});})(jQuery);(function(a){if(!a.onepress){a.onepress={};}if(!a.onepress.providers){a.onepress.providers={};}a.onepress.providers.clientStoreStateProvider=function(c,b,e,d){this.networkName=c;this.buttonName=b;this.name=c+"-"+b;this.demo=d.demo;this.useCookies=d.locker.useCookies;this.cookiesLifetime=d.locker.cookiesLifetime;this.url=e;this.identity="page_"+a.onepress.tools.hash(this.url)+"_hash_"+c+"-"+b;this.isUnlocked=function(){if(this.demo){return false;}return(this._getValue())?true:false;};this.isLocked=function(){return !this.isUnlocked();};this.getState=function(f){if(this.demo){return f(false);}f(this.isUnlocked());};this.setState=function(f){if(this.demo){return true;}return f=="unlocked"?this._setValue():this._removeValue();};this._setValue=function(){var f=this;return localStorage&&!this.useCookies?localStorage.setItem(this.identity,true):a.onepress.tools.cookie(this.identity,true,{expires:f.cookiesLifetime,path:"/"});};this._getValue=function(){if(localStorage&&!this.useCookies){var f=localStorage.getItem(this.identity);if(f){return f;}f=a.onepress.tools.cookie(this.identity);if(f){this._setValue();}return f;}return a.onepress.tools.cookie(this.identity);};this._removeValue=function(){if(localStorage){localStorage.removeItem(this.identity);}a.onepress.tools.cookie(this.identity,null);};};})(jQuery);(function(a){if(!a.onepress){a.onepress={};}a.onepress.connector=a.onepress.connector||{sdk:[{name:"facebook",url:"//connect.facebook.net/{lang}/all.js",scriptId:"facebook-jssdk",hasParams:true,isRender:true,isLoaded:function(){return(typeof(window.FB)==="object");},pre:function(){a("#fb-root").length==0&&a("<div id='fb-root'></div>").appendTo(a("body"));var b=(this.options&&this.options.lang)||"en_US";this.url=this.url.replace("{lang}",b);},createEvents:function(b){var e=this;var c=function(){window.FB.init({appId:(e.options&&e.options.appId)||null,status:true,cookie:true,xfbml:true});window.FB.Event.subscribe("edge.create",function(f){a(document).trigger("fb-like",[f]);});window.FB.init=function(){};a(document).trigger(e.name+"-init");};if(b){c();return;}if(window.fbAsyncInit){var d=window.fbAsyncInit;}window.fbAsyncInit=function(){c();d&&d();window.fbAsyncInit=function(){};};},render:function(d){var c=d.data("onepress-facebookButton");if(!c){return;}var b=c.getHtmlToRender();b.find(".fake-fb-like").addClass("fb-like");window.FB.XFBML.parse(b[0]);}},{name:"twitter",url:"//platform.twitter.com/widgets.js",scriptId:"twitter-wjs",hasParams:false,isRender:true,pre:function(){var b=(a("link[rel='canonical']").length>0)?a("link[rel='canonical']").attr("href"):null;a(".twitter-share-button").each(function(e,f){var c=a(f);var d=a(f).parent();if(d.attr("data-url-to-compare")){return;}var g=c.attr("data-url");if(!g&&b){g=b;}g=(!g)?window.location:g;c.parent().attr("data-url-to-compare",g);});},isLoaded:function(){return(typeof(window.__twttrlr)!=="undefined");},createEvents:function(b){var d=this;var c=function(){window.twttr.events.bind("tweet",function(e){a(document).trigger("tw-tweet",[e.target,e.data]);});window.twttr.events.bind("follow",function(e){a(document).trigger("tw-follow",[e.target,e.data]);});a(document).trigger(d.name+"-init");};if(b){c();return;}if(!window.twttr){window.twttr={};}if(!window.twttr.ready){window.twttr=a.extend(window.twttr,{_e:[],ready:function(e){this._e.push(e);}});}twttr.ready(function(e){c();});},render:function(f){var c=f.data("onepress-twitterButton");if(!c){return;}var b=c.getHtmlToRender().parent();var d=5;var e=function(){if(b.find("iframe").length>0){return;}if(window.twttr.widgets&&window.twttr.widgets.load){window.twttr.widgets.load(b[0]);f.trigger("tw-render");}else{if(d<=0){return;}d--;setTimeout(function(){e();},1000);}};e();}},{name:"google",url:"//apis.google.com/js/plusone.js",scriptId:"google-jssdk",hasParams:true,isRender:true,pre:function(){var b=(this.options&&this.options.lang)||"en";window.___gcfg=window.___gcfg||{lang:b};window.onepressPlusOneCallback=function(c){if(c.state=="on"){a(document).trigger("gl-plus",[c.href]);}};window.onepressGoogleShareCallback=function(c){a(document).trigger("gl-share",[c.id]);};},isLoaded:function(){return(typeof(window.gapi)==="object");},render:function(d){var b=d.data("onepress-googleButton");if(!b){return;}var c=this;setTimeout(function(){var e=b.getHtmlToRender();var f=e.find(".fake-g-plusone");if(f.length>0){c._addCallbackToControl(e);f.addClass("g-plusone");window.gapi.plusone.go(e[0]);return;}var g=e.find(".fake-g-share");if(g.length>0){g.attr("data-onendinteraction","onepressGoogleShareCallback");g.addClass("g-plus").attr("data-action","share");window.gapi.plus.go(e[0]);return;}},100);},_addCallbackToControl:function(b){var c=(!b.is(".g-plusone"))?b.find(".fake-g-plusone"):b;var d=c.attr("data-callback");if(d&&d!="onepressPlusOneCallback"){var e="__plusone_"+d;window[e]=function(f){window[d](f);window.onepressPlusOneCallback(f);};c.attr("data-callback",e);}else{c.attr("data-callback","onepressPlusOneCallback");}}},{name:"linkedin",url:"//platform.linkedin.com/in.js",scriptId:"linkedin-jssdk",hasParams:false,isRender:true,pre:function(){window.onepressLinkedInShareCallback=function(b){a(document).trigger("ln-share",[b]);};},isLoaded:function(){return(typeof(window.IN)==="object");},render:function(c){var b=c.data("linkedin-widget");if(!b){return;}setTimeout(function(){var d=b.getHtmlToRender();d.attr("data-onsuccess","onepressLinkedInShareCallback");IN.init();},100);}}],_ready:{},_connected:{},getSDK:function(c){for(var b in this.sdk){if(this.sdk[b].name==c){return this.sdk[b];}}return null;},isConnected:function(c){if(a("#"+c.scriptId).length>0){return true;}var b=false;a("script").each(function(){var d=a(this).attr("src");if(!d){return true;}b=d.indexOf(c.url)!==-1;if(b){a(this).attr("id",c.scriptId);return false;}});return b;},getLoadingScript:function(d){var b=a("#"+d.scriptId);var c=a("script[src='*"+d.url+"']");return(b.length>0)?b:c;},isLoaded:function(b){return this.isConnected(b)&&b.isLoaded&&b.isLoaded();},connect:function(f,g,b){var j=this,i=this.getSDK(f);if(!i){console&&console.log("Invalide SDK name: "+f);return;}i.options=g;if(b){this._ready[f]?b(i):a(document).bind(f+"-init",function(){b(i);});}if(this._connected[f]){return;}if(!i.createEvents){i.createEvents=function(k){var m=this;var l=function(){a(document).trigger(m.name+"-init");};if(k){l();return;}a(document).bind(m.name+"-script-loaded",function(){l();});};}if(i.pre){i.pre();}var d=this.isLoaded(i);var c=this.isConnected(i);a(document).bind(f+"-init",function(){j._ready[f]=true;});i.createEvents(d);if(!c){var h=function(){var l=document.createElement("script");l.type="text/javascript";l.id=i.scriptId;l.src=i.url;var k=document.getElementsByTagName("body")[0];k.appendChild(l);};i.isRender?h():a(function(){a(function(){h();});});}if(!d){var e=this.getLoadingScript(i)[0];if(e){e.onreadystatechange=e.onload=function(){var k=e.readyState;if((!k||/loaded|complete/.test(k))){a(document).trigger(i.name+"-script-loaded");}};}}this._connected[f]=true;}};})(jQuery);(function(a){if(a.fn.sociallocker){return;}a.onepress.widget("sociallocker",{options:{},_isLocked:false,_defaults:{_iPhoneBug:false,url:null,text:{header:a.onepress.sociallocker.lang.defaultHeader,message:a.onepress.sociallocker.lang.defaultMessage},theme:"starter",demo:false,buttons:{layout:"horizontal",order:["twitter-tweet","facebook-like","google-plus"],size:"auto",counter:true},locker:{close:false,timer:0,mobile:true,useCookies:false,cookiesLifetime:3560},content:null,events:{lock:null,unlock:null,ready:null,unlockByCross:null,unlockByTimer:null},effects:{flip:false,highlight:true},facebook:{url:null,appId:null,lang:"en_US",colorScheme:"light",font:"tahoma",ref:null,like:{title:a.onepress.sociallocker.lang.facebook_like},share:{title:a.onepress.sociallocker.lang.facebook_share}},twitter:{url:null,via:null,text:null,related:null,lang:"en",counturl:null,tweet:{title:a.onepress.sociallocker.lang.twitter_tweet},follow:{title:a.onepress.sociallocker.lang.twitter_follow}},google:{url:null,lang:"en-US",annotation:null,recommendations:true,plus:{title:a.onepress.sociallocker.lang.google_plus},share:{title:a.onepress.sociallocker.lang.google_share}},linkedin:{url:null,counter:"right",share:{title:a.onepress.sociallocker.lang.linkedin_share}}},_create:function(){var c=this;this._processOptions();if(a.browser.msie&&parseInt(a.browser.version,10)===7){this._unlock("ie7");return;}if(!this.options.locker.mobile&&this._isMobile()){this._unlock("mobile");return;}if((/iPhone/i).test(navigator.userAgent)&&this.options._iPhoneBug){var d=a.inArray("twitter-tweet",this.options.buttons.order);if(d>=0){this.options.buttons.order.splice(d,1);}}if(a.browser.opera||a.browser.msie||this._isTabletOrMobile()){var b=a.inArray("google-share",this.options.buttons.order);if(b>=0){this.options.buttons.order.splice(b,1);}}if(this.options.buttons.order.length==0){this._unlock("nobuttons");return;}this._controller=this._createProviderController();this._controller.getState(function(e){e?c._unlock("provider"):c._lock();c.options.events.ready&&c.options.events.ready(e);});},_createProviderController:function(){var g=this;this._providers={};var i=0;for(var f in this.options.buttons.order){var h=this.options.buttons.order[f];if(typeof(h)!="string"){continue;}if(!this._isValidButton(h)){this._setError("The button '"+h+"' not found.");return;}if(h=="#"){continue;}var e=h.split("-");var d=e[0];var b=e[1];var c=a.extend({},this.options[d]);if(this.options[d][b]){c=a.extend(c,this.options[d][b]);}var j=c.url||this.options.url||window.location.href;if(!this._providers[h]){this._providers[h]=new a.onepress.providers.clientStoreStateProvider(d,b,j,g.options);i++;}}return{getState:function(k){var l=i;var o=false;for(var m in g._providers){var n=g._providers[m];n.getState(function(p){l--;o=o||p;if(l==0){k(o,n);}});}}};},_processOptions:function(){var c=this.options.theme||this._defaults.theme;var b=a.extend(true,{},this._defaults);if(a.onepress.sociallocker.presets[c]){b=a.extend(true,{},b,a.onepress.sociallocker.presets[c]);if(a.onepress.sociallocker.presets[c].buttons&&a.onepress.sociallocker.presets[c].buttons.order){b.buttons.order=a.onepress.sociallocker.presets[c].buttons.order;}}b=a.extend(true,b,this.options);if(this.options.buttons&&this.options.buttons.order){b.buttons.order=this.options.buttons.order;}b.effects.flip=b.effects.flip||(b.style=="onp-sociallocker-secrets");if(b.buttons.layout=="vertical"){b.facebook.like.layout="box_count";b.facebook.share.layout="vertical";b.twitter.count="vertical";b.twitter.size="medium";b.google.plus.size="tall";b.google.share.annotation="vertical-bubble";b.linkedin.share.counter="top";b.buttons.counter=true;}if(b.buttons.layout=="horizontal"){b.facebook.layout="button_count";b.twitter.count="horizontal";b.twitter.size="medium";b.google.size="medium";b.google.annotation="bubble";b.linkedin.share.counter="right";if(!b.buttons.counter){b.twitter.count="none";b.twitter.showCount=false;b.google.annotation="none";b.facebook.count="none";b.linkedin.share.counter="none";}}if(typeof b.text!=="object"){b.text={message:b.text};}if(b.text.header){b.text.header=(typeof b.text.header==="function"&&b.text.header(this))||(typeof b.text.header==="string"&&a("<div>"+b.text.header+"</div>"))||(typeof b.text.header==="object"&&b.text.header.clone());}if(b.text.message){b.text.message=(typeof b.text.message==="function"&&b.text.message(this))||(typeof b.text.message==="string"&&a("<div>"+b.text.message+"</div>"))||(typeof b.text.message==="object"&&b.text.message.clone());}b.locker.timer=parseInt(b.locker.timer);if(b.locker.timer==0){b.locker.timer=null;}this.options=b;this.style="onp-sociallocker-"+c;},_isMobile:function(){if((/webOS|iPhone|iPod|BlackBerry/i).test(navigator.userAgent)){return true;}if((/Android/i).test(navigator.userAgent)&&(/Mobile/i).test(navigator.userAgent)){return true;}return false;},_isTabletOrMobile:function(){if((/webOS|iPhone|iPad|Android|iPod|BlackBerry/i).test(navigator.userAgent)){return true;}return false;},_isValidButton:function(c){for(var b in a.onepress.sociallocker.buttons){if(a.onepress.sociallocker.buttons[b]==c){return true;}}return false;},_setError:function(b){this._error=true;this._errorText=b;this.locker&&this.locker.hide();this.element.html("<strong>[Error]: "+b+"</strong>");this.element.show().addClass("onp-sociallocker-error");},_createMarkup:function(){var e=this;this.element.addClass("onp-sociallocker-content");var c=(jQuery.browser.mozilla&&"mozilla")||(jQuery.browser.opera&&"opera")||(jQuery.browser.webkit&&"webkit")||"msie";this.locker=a("<div class='onp-sociallocker onp-sociallocker-"+c+"' style='display: none;'></div>");this.outerWrap=a("<div class='onp-sociallocker-outer-wrap'></div>").appendTo(this.locker);this.innerWrap=a("<div class='onp-sociallocker-inner-wrap'></div>").appendTo(this.outerWrap);if(this.options.buttons.size=="fixed"){this.locker.addClass("onp-sociallocker-buttons-fixed");}this.locker.addClass(this.style);if(!this.options.buttons.counter){this.locker.addClass("onp-sociallocker-no-counters");}a.onepress.isTouch()?this.locker.addClass("onp-sociallocker-touch"):this.locker.addClass("onp-sociallocker-no-touch");var d=a("<div class='onp-sociallocker-text'></div>");if(this.options.text.header){d.append(this.options.text.header.addClass("onp-sociallocker-strong").clone());}if(this.options.text.message){d.append(this.options.text.message.clone());}this.innerWrap.append(d.addClass());d.prepend((a("<div class='onp-sociallocker-before-text'></div>")));d.append((a("<div class='onp-sociallocker-after-text'></div>")));this._createButtonMarkup();this.options.bottomText&&this.innerWrap.append(this.options.bottomText.addClass("onp-sociallocker-bottom-text"));this.options.locker.close&&this._createClosingCross();this.options.locker.timer&&this._createTimer();var b=(this.element.parent().is("a"))?this.element.parent():this.element;this.locker.insertAfter(b);this._markupIsCreated=true;},_createButtonMarkup:function(){var m=this;this.buttonsWrap=a("<div class='onp-sociallocker-buttons'></div>").appendTo(this.innerWrap);var p=50;for(var h in this.options.buttons.order){var n=this.options.buttons.order[h];if(typeof(n)!="string"){continue;}if(n=="#"){this.buttonsWrap.append("<div class='onp-button-separator'></div>");continue;}if(this.options.buttons.unsupported&&jQuery.inArray(n,this.options.buttons.unsupported)>=0){var o='The button "'+n+'" is not supported by this theme.';var b=a("<div class='onp-sociallocker-button onp-sociallocker-button-unsupported'></div>");var i=a("<div class='onp-sociallocker-button-inner-wrap'>"+o+"</div>").appendTo(b);this.buttonsWrap.append(b);continue;}var l=n.split("-");var j=l[0];var d=l[1];var c="sociallocker_"+j+"_"+d;var e=a.extend({},this.options[j]);if(this.options[j][d]){e=a.extend(e,this.options[j][d]);}e.url=e.url||this.options.url;e._provider=this._providers[n];e.unlock=function(){m._unlock("button",this._provider);};var b=a("<div class='onp-sociallocker-button onp-sociallocker-button-"+n+"'></div>");b.addClass("onp-sociallocker-button-"+j);b.data("name",n);this.buttonsWrap.append(b);var i=a("<div class='onp-sociallocker-button-inner-wrap'></div>").appendTo(b);i[c](e);var f=this.options.effects.flip;var g=a.onepress.tools.has3d();(f&&g&&b.addClass("onp-sociallocker-flip"))||b.addClass("onp-sociallocker-no-flip");if(!f){continue;}if(a.onepress.isTouch()){var k=(this.options.triggers&&this.options.triggers.overlayRender)?this.options.triggers.overlayRender(e,j,d,true):a("<a class='onp-sociallocker-button-overlay' href='#'></a>");k.prependTo(i);if(g){k.click(function(){var q=a(this).parents(".onp-sociallocker-button");if(q.hasClass("onp-sociallocker-flip-hover")){q.removeClass("onp-sociallocker-flip-hover");}else{a(".onp-sociallocker-flip-hover").removeClass("onp-sociallocker-flip-hover");q.addClass("onp-sociallocker-flip-hover");}return false;});}else{k.click(function(){var q=a(this);q.stop().animate({opacity:0},200,function(){q.hide();});return false;});}}else{var k=(this.options.triggers&&this.options.triggers.overlayRender)?this.options.triggers.overlayRender(e,j,d,false):a("<div class='onp-sociallocker-button-overlay' href='#'></div>");k.prependTo(i);if(!g){b.hover(function(){var q=a(this).find(".onp-sociallocker-button-overlay");q.stop().animate({opacity:0},200,function(){q.hide();});},function(){var q=a(this).find(".onp-sociallocker-button-overlay").show();q.stop().animate({opacity:1},200);});}}if(k){k.css("z-index",p);k.find(".onp-sociallocker-overlay-front").css("z-index",p);k.find(".onp-sociallocker-overlay-back").css("z-index",p-1);k.find(".onp-sociallocker-overlay-header").css("z-index",p-1);}p=p-5;}},_makeSimilar:function(c,e,b){var d=this;c.css({width:e.outerWidth(false),height:e.outerHeight(false)});if(!b){a(window).resize(function(){d._makeSimilar(c,e,true);});}},_createClosingCross:function(){var b=this;a("<div class='onp-sociallocker-cross' title='"+a.onepress.sociallocker.lang.close+"' />").prependTo(this.locker).click(function(){if(!b.close||!b.close(b)){b._unlock("cross",true);}});},_createTimer:function(){this.timer=a("<span class='onp-sociallocker-timer'></span>");var c=a.onepress.sociallocker.lang.orWait;var b=a.onepress.sociallocker.lang.seconds;this.timerLabel=a("<span class='onp-sociallocker-timer-label'>"+c+" </span>").appendTo(this.timer);this.timerCounter=a("<span class='onp-sociallocker-timer-counter'>"+this.options.locker.timer+b+"</span>").appendTo(this.timer);this.timer.appendTo(this.locker);this.counter=this.options.locker.timer;this._kickTimer();},_kickTimer:function(){var b=this;setTimeout(function(){if(!b._isLocked){return;}b.counter--;if(b.counter<=0){b._unlock("timer");}else{b.timerCounter.text(b.counter+a.onepress.sociallocker.lang.seconds);if(a.browser.opera){var c=b.timerCounter.clone();c.insertAfter(b.timerCounter);b.timerCounter.remove();b.timerCounter=c;}b._kickTimer();}},1000);},_lock:function(c,b){if(this._isLocked||this._stoppedByWatchdog){return;}if(!this._markupIsCreated){this._createMarkup();}if(c=="button"){b.setState("locked");}this.element.hide();this.isInline?this.locker.css("display","inline-block"):this.locker.fadeIn(1000);this._isLocked=true;if(this.options.events.lock){this.options.events.lock(c,b&&b.name);}},_unlock:function(d,c){var b=this;if(!this._isLocked){this._showContent(true);return false;}if(d=="button"){c.setState("unlocked");}this._showContent(true);this._isLocked=false;if(d=="timer"&&this.options.events.unlockByTimer){return this.options.events.unlockByTimer();}if(d=="close"&&this.options.events.unlockByClose){return this.options.events.unlockByClose();}if(this.options.events.unlock){this.options.events.unlock(d,c&&c.name);}},lock:function(){this._lock("user");},unlock:function(){this._unlock("user");},_showContent:function(h){var g=this;var f=function(){if(g.locker){g.locker.hide();}if(!h){g.element.show();return;}g.element.fadeIn(1000,function(){g.options.effects.highlight&&g.element.effect&&g.element.effect("highlight",{color:"#fffbcc"},800);});};if(!this.options.content){f();}else{if(typeof this.options.content==="string"){this.element.html(this.options.content);f();}else{if(typeof this.options.content==="object"&&!this.options.content.url){this.element.append(this.options.content.clone().show());f();}else{if(typeof this.options.content==="object"&&this.options.content.url){var b=a.extend(true,{},this.options.content);var e=b.success;var c=b.complete;var d=b.error;b.success=function(i,k,j){!e?g.element.html(i):e(g,i,k,j);f();};b.error=function(j,k,i){g._setError("An error is triggered during the ajax request! Text: "+k+" "+i);d&&d(j,k,i);};b.complete=function(i,j){c&&c(i,j);};a.ajax(b);}else{f();}}}}}});a.fn.socialLock=function(b){b=a.extend({},b);a(this).sociallocker(b);};})(jQuery);

(function($){
    if ( !window.onpsl ) window.onpsl = {};
    if ( !window.onpsl.lockerOptions ) window.onpsl.lockerOptions = {};
    
    window.onpsl.lockers = function() {
        
        // shortcodes
        
        $(".onp-sociallocker-call").each(function(){
            var $target = $(this);
            var lockId = $target.data('lock-id');

            var data = window.onpsl.lockerOptions[lockId] 
                ? window.onpsl.lockerOptions[lockId] 
                : $.parseJSON( $target.next().text() );
            
            window.onpsl.createLocker( $target, data, lockId );
        });
        
        // css selectors from bulk locking
        
        if ( window.onpsl.bulkCssSelectors ) {
            for(var index in window.onpsl.bulkCssSelectors) {
                var selector = window.onpsl.bulkCssSelectors[index]['selector'];
                var lockId = window.onpsl.bulkCssSelectors[index]['lockId'];
                
                var limitCounter = 0;
                $(selector).each(function(){
                    
                    limitCounter++;
                    if ( limitCounter > 20 ) return false;
                    
                    var $target = $(this);
                    var data = window.onpsl.lockerOptions[lockId];
                    window.onpsl.createLocker( $target, data, lockId );
                });
            }
            window.onpsl.bulkCssSelectors = [];
        } 
    };
    
    window.onpsl.createLocker = function( $target, data, lockId ) {

        var options = data.options;

        if ( data.ajax ) {
            options.content = {
                url: data.ajaxUrl, type: 'POST', data: {
                    lockerId: data.lockerId,
                    action: 'sociallocker_loader',
                    hash: data.contentHash
                }
            }
        }

        if ( data.postId && data.tracking ) {
            if ( !options.events ) options.events = {};

            options.events.unlock = function(sender, senderName){
                if ( $.inArray(sender, ['cross', 'button', 'timer']) == -1 ) return;

                $.ajax({ url: data.ajaxUrl, type: 'POST', data: {
                    action: 'sociallocker_tracking',
                    targetId: data.postId,
                    sender: sender,
                    senderName: senderName
                    }
                });
            }
        }

        $target.removeClass("onp-sociallocker-call");
        if ( !window.onpsl.lockerOptions[lockId] ) $target.next().remove();

        $target.sociallocker( options );
    };    
    
    // adding support for dynamic themes
    
    var bindFunction = function(){
        if ( !window.onpsl.dynamicThemeSupport ) return;

        if ( window.onpsl.dynamicThemeEvent != '' ) {
            $(document).bind(window.onpsl.dynamicThemeEvent, function(){
                window.onpsl.lockers();
            });
        } else {
            $(document).ajaxComplete(function() {
                window.onpsl.lockers();
            });
        }
    };

    if ( window.onpsl.dynamicThemeSupport ) {
        bindFunction();
    } else {
        $(function(){ bindFunction(); });
    }

})(jQuery);