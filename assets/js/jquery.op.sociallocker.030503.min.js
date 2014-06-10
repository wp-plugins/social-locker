/*!
 * Social Locker - v1.6.0, 2014-06-04 
 * for jQuery: http://onepress-media.com/plugin/social-locker-for-jquery/get 
 * for Wordpress: http://onepress-media.com/plugin/social-locker-for-wordpress/get 
 * 
 * Copyright 2014, OnePress, http://byonepress.com 
 * Help Desk: http://support.onepress-media.com/ 
*/

/*!
 * Preset resources for Social Locker.
 */

(function ($) {

    /**
    * Text resources.
    */

    if (!$.onepress) $.onepress = {};
    if (!$.onepress.sociallocker) $.onepress.sociallocker = {};
    if (!$.onepress.sociallocker.lang) $.onepress.sociallocker.lang = {};
    
    $.onepress.sociallocker.lang = {
        defaultHeader:          "This content is locked!",
        defaultMessage:         "Please support us, use one of the buttons below to unlock the content.",
        orWait:                 'or wait',
        seconds:                's',   
        close:                  'Close',
        error:                  'error',  
        unableToLoadSDK:        'Unable to load SDK script for {0}. Please make sure that nothing blocks loading of social scripts in your browser. Some browser extentions (Avast, PrivDog, AdBlock etc.) can cause this issue. Turn them off and try again.',     
        unableToCreateButton:   'Unable to create the button ({0}). Please make sure that nothing blocks loading of social scripts in your browser. Some browser extentions (Avast, PrivDog, AdBlock etc.) can cause this issue. Turn them off and try again.',     
        
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
        ,"facebook-share"        
        ,"google-plus"
        ,"google-share"
        ,"twitter-tweet"
        ,"twitter-follow"
        ,"linkedin-share" 
        ,"#"
    ];

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
                var overlay = $("<a></a>");
                var title = options.title || $.onepress.sociallocker.lang[networkName + "_" + buttonName];
                
                overlay.addClass("onp-sociallocker-button-overlay") 
                      .append(
                       $("<div class='onp-sociallocker-overlay-front'></div>")
                            .append($("<div class='onp-sociallocker-overlay-icon'></div>"))
                            .append($("<div class='onp-sociallocker-overlay-line'></div>"))               
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
    
    /* secrets theme */
    
    $.onepress.sociallocker.presets['flat'] = {

        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: true
        },
        
        triggers: {
            overlayRender: function(options, networkName, buttonName, isTouch){
                var overlay = $("<a></a>");
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

})(jQuery);;
/*!
 * Helper Tools.
 * Copyright 2013, OnePress, http://byonepress.com
 */
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.tools||(a.onepress.tools={}),a.onepress.tools.cookie=a.onepress.tools.cookie||function(b,c,d){if(arguments.length>1&&(!/Object/.test(Object.prototype.toString.call(c))||null===c||void 0===c)){if(d=a.extend({},d),(null===c||void 0===c)&&(d.expires=-1),"number"==typeof d.expires){var e=d.expires,f=d.expires=new Date;f.setDate(f.getDate()+e)}return c=String(c),document.cookie=[encodeURIComponent(b),"=",d.raw?c:encodeURIComponent(c),d.expires?"; expires="+d.expires.toUTCString():"",d.path?"; path="+d.path:"",d.domain?"; domain="+d.domain:"",d.secure?"; secure":""].join("")}d=c||{};for(var g,h=d.raw?function(a){return a}:decodeURIComponent,i=document.cookie.split("; "),j=0;g=i[j]&&i[j].split("=");j++)if(h(g[0])===b)return h(g[1]||"");return null},a.onepress.tools.hash=a.onepress.tools.hash||function(a){var b=0;if(0==a.length)return b;for(var c=0;c<a.length;c++){var d=a.charCodeAt(c);b=(b<<5)-b+d,b&=b}return b=b.toString(16),b=b.replace("-","0")},a.onepress.tools.has3d=a.onepress.tools.has3d||function(){var a,b=document.createElement("p"),c={WebkitTransform:"-webkit-transform",OTransform:"-o-transform",MSTransform:"-ms-transform",MozTransform:"-moz-transform",Transform:"transform"};document.body.insertBefore(b,null);for(var d in c)void 0!==b.style[d]&&(b.style[d]="translate3d(1px,1px,1px)",a=window.getComputedStyle(b).getPropertyValue(c[d]));return document.body.removeChild(b),void 0!==a&&a.length>0&&"none"!==a},a.onepress.isTouch=a.onepress.isTouch||function(){return!!("ontouchstart"in window)||!!("onmsgesturechange"in window)},a.onepress.widget=function(b,c){var d={createWidget:function(d,e){var f=a.extend(!0,{},c);f.element=a(d),f.options=a.extend(!0,f.options,e),f._init&&f._init(),f._create&&f._create(),a.data(d,"plugin_"+b,f)},callMethod:function(a,b){return a[b]&&a[b]()}};a.fn[b]=function(){var c=arguments,e=arguments.length,f=this;return this.each(function(){var g=a.data(this,"plugin_"+b);!g&&1>=e?d.createWidget(this,e?c[0]:!1):1==e&&(f=d.callMethod(g,c[0]))}),f}},a.onepress.detectBrowser=a.onepress.detectBrowser||function(){function b(){var a=-1;if("Microsoft Internet Explorer"==navigator.appName){var b=navigator.userAgent,c=new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");null!=c.exec(b)&&(a=parseFloat(RegExp.$1))}else if("Netscape"==navigator.appName){var b=navigator.userAgent,c=new RegExp("Trident/.*rv:([0-9]{1,}[.0-9]{0,})");null!=c.exec(b)&&(a=parseFloat(RegExp.$1))}return a}var c=jQuery.uaMatch||function(a){a=a.toLowerCase();var b=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},d=c(navigator.userAgent);a.onepress.browser={},d.browser&&(a.onepress.browser[d.browser]=!0,a.onepress.browser.version=d.version);var e=b();e>0&&(a.onepress.browser.msie=!0,a.onepress.browser.version=e),a.onepress.browser.chrome?a.onepress.browser.webkit=!0:a.onepress.browser.webkit&&(a.onepress.browser.safari=!0)},a.onepress.detectBrowser()}(jQuery);;
/*!
 * URL.js
 * Copyright 2011 Eric Ferraiuolo
 * https://github.com/ericf/urljs
 */
var URL=function(){var a=this;return a&&a.hasOwnProperty&&a instanceof URL||(a=new URL),a._init.apply(a,arguments)};!function(){var a,b,c,d="absolute",e="relative",f=":",g="//",h="@",i=".",j="/",k="..",l="../",m="?",n="=",o="&",p="#",q="",r="type",s="scheme",t="userInfo",u="host",v="port",w="path",x="query",y="fragment",z=/^(?:(https?:\/\/|\/\/)|(\/|\?|#)|[^;:@=\.\s])/i,A=/^(?:(https?):\/\/|\/\/)(?:([^:@\s]+:?[^:@\s]+?)@)?((?:[^;:@=\/\?\.\s]+\.)+[A-Za-z0-9\-]{2,})(?::(\d+))?(?=\/|\?|#|$)([^\?#]+)?(?:\?([^#]+))?(?:#(.+))?/i,B=/^([^\?#]+)?(?:\?([^#]+))?(?:#(.+))?/i,C="object",D="string",E=/^\s+|\s+$/g;a=String.prototype.trim?function(a){return a&&a.trim?a.trim():a}:function(a){try{return a.replace(E,q)}catch(b){return a}},b=function(a){return a&&typeof a===C},c=function(a){return typeof a===D},URL.ABSOLUTE=d,URL.RELATIVE=e,URL.normalize=function(a){return new URL(a).toString()},URL.resolve=function(a,b){return new URL(a).resolve(b).toString()},URL.prototype={_init:function(a){return this.constructor=URL,a=c(a)?a:a instanceof URL?a.toString():null,this._original=a,this._url={},this._isValid=this._parse(a),this},toString:function(){var a=this._url,b=[],c=a[r],e=a[s],h=a[w],i=a[x],k=a[y];return c===d&&(b.push(e?e+f+g:g,this.authority()),h&&0!==h.indexOf(j)&&(h=j+h)),b.push(h,i?m+this.queryString():q,k?p+k:q),b.join(q)},original:function(){return this._original},isValid:function(){return this._isValid},isAbsolute:function(){return this._url[r]===d},isRelative:function(){return this._url[r]===e},isHostRelative:function(){var a=this._url[w];return this.isRelative()&&a&&0===a.indexOf(j)},type:function(){return this._url[r]},scheme:function(a){return arguments.length?this._set(s,a):this._url[s]},userInfo:function(a){return arguments.length?this._set(t,a):this._url[t]},host:function(a){return arguments.length?this._set(u,a):this._url[u]},domain:function(){var a=this._url[u];return a?a.split(i).slice(-2).join(i):void 0},port:function(a){return arguments.length?this._set(v,a):this._url[v]},authority:function(){var a=this._url,b=a[t],c=a[u],d=a[v];return[b?b+h:q,c,d?f+d:q].join(q)},path:function(a){return arguments.length?this._set(w,a):this._url[w]},query:function(a){return arguments.length?this._set(x,a):this._url[x]},queryString:function(a){if(arguments.length)return this._set(x,this._parseQuery(a));a=q;var b,c,d=this._url[x];if(d)for(b=0,c=d.length;c>b;b++)a+=d[b].join(n),c-1>b&&(a+=o);return a},fragment:function(a){return arguments.length?this._set(y,a):this._url[y]},resolve:function(a){a=a instanceof URL?a:new URL(a);var b,c;return this.isValid()&&a.isValid()?a.isAbsolute()?this.isAbsolute()?a.scheme()?a:new URL(a).scheme(this.scheme()):a:(b=new URL(this.isAbsolute()?this:null),a.path()?(c=a.isHostRelative()||!this.path()?a.path():this.path().substring(0,this.path().lastIndexOf(j)+1)+a.path(),b.path(this._normalizePath(c)).query(a.query()).fragment(a.fragment())):a.query()?b.query(a.query()).fragment(a.fragment()):a.fragment()&&b.fragment(a.fragment()),b):this},reduce:function(a){a=a instanceof URL?a:new URL(a);var b=this.resolve(a);return this.isAbsolute()&&b.isAbsolute()&&b.scheme()===this.scheme()&&b.authority()===this.authority()&&b.scheme(null).userInfo(null).host(null).port(null),b},_parse:function(b,f){if(b=a(b),!(c(b)&&b.length>0))return!1;var g,h;switch(f||(f=b.match(z),f=f?f[1]?d:f[2]?e:null:null),f){case d:g=b.match(A),g&&(h={},h[r]=d,h[s]=g[1]?g[1].toLowerCase():void 0,h[t]=g[2],h[u]=g[3].toLowerCase(),h[v]=g[4]?parseInt(g[4],10):void 0,h[w]=g[5]||j,h[x]=this._parseQuery(g[6]),h[y]=g[7]);break;case e:g=b.match(B),g&&(h={},h[r]=e,h[w]=g[1],h[x]=this._parseQuery(g[2]),h[y]=g[3]);break;default:return this._parse(b,d)||this._parse(b,e)}return h?(this._url=h,!0):!1},_parseQuery:function(b){if(c(b)){b=a(b);var d,e,f,g=[],h=b.split(o);for(e=0,f=h.length;f>e;e++)h[e]&&(d=h[e].split(n),g.push(d[1]?d:[d[0]]));return g}},_set:function(a,b){return this._url[a]=b,!b||a!==s&&a!==t&&a!==u&&a!==v||(this._url[r]=d),b||a!==u||(this._url[r]=e),this._isValid=this._parse(this.toString()),this},_normalizePath:function(a){var b,c,d,e,f,g;if(a.indexOf(l)>-1){for(b=a.split(j),d=[],f=0,g=b.length;g>f;f++)c=b[f],c===k?d.pop():c&&d.push(c);e=d.join(j),a[0]===j&&(e=j+e),a[a.length-1]===j&&e.length>1&&(e+=j)}else e=a;return e}}}();;
/*!
 * SDK Connector for social networks.
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.connector=a.onepress.connector||{_ready:{},_connected:{},getSDK:function(b){return a.onepress.sdk[b]?a.onepress.sdk[b]:null},isConnected:function(b){if(a("#"+b.scriptId).length>0)return!0;var c=!1;return a("script").each(function(){var d=a(this).attr("src");return d?(c=-1!==d.indexOf(b.url),c?(a(this).attr("id",b.scriptId),!1):void 0):!0}),c},getLoadingScript:function(b){var c=a("#"+b.scriptId),d=a("script[src='*"+b.url+"']");return c.length>0?c:d},isLoaded:function(a){return this.isConnected(a)&&a.isLoaded&&a.isLoaded()},connect:function(b,c,d){var e=this,f=this.getSDK(b);if(!f)return console&&console.log("Invalide SDK name: "+b),void 0;if(f.options=c,d&&(this._ready[b]?d(f):a(document).bind(b+"-init",function(){d(f)})),!this._connected[b]){f.createEvents||(f.createEvents=function(){var b=f.isLoaded(),c=function(){a(document).trigger(f.name+"-init")};return b?(c(),void 0):(a(document).bind(f.name+"-script-loaded",function(){c()}),void 0)}),f.prepare&&f.prepare();var g=f.isLoaded(),h=this.isConnected(f);if(a(document).bind(b+"-init",function(){e._ready[b]=!0}),f.createEvents(),!h){var i=function(){var a=document.createElement("script");a.type="text/javascript",a.id=f.scriptId,a.src=f.url;var b=f.getScriptBody?f.getScriptBody():null;b&&(a.innerHtml=b);var c=document.getElementsByTagName("body")[0];c.appendChild(a)};i()}if(!g){var j=this.getLoadingScript(f)[0];j&&(j.onreadystatechange=j.onload=function(){var b=j.readyState;(!b||/loaded|complete/.test(b))&&(a(document).trigger(f.name+"-script-loaded"),a(document).unbind(f.name+"-script-loaded"))})}this._connected[b]=!0}}}}(jQuery);;
/*!
 * OnePress Local State Provider
 * Copyright 2013, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.providers||(a.onepress.providers={}),a.onepress.providers.clientStoreStateProvider=function(b,c,d,e){this.networkName=b,this.buttonName=c,this.name=b+"-"+c,this.demo=e.demo,this.useCookies=e.locker.useCookies,this.expires=e.locker.expires,this.scope=e.locker.scope&&"scope_"+e.locker.scope,this.url=d,this.identity="page_"+a.onepress.tools.hash(this.url)+"_hash_"+b+"-"+c,this.isUnlocked=function(){return this.demo?!1:this._getValue(this.identity)||this._getValue(this.scope)?!0:!1},this.isLocked=function(){return!this.isUnlocked()},this.getState=function(a){return this.demo?a(!1):(a(this.isUnlocked()),void 0)},this.setState=function(a){if(this.demo)return!0;try{return"unlocked"===a?this._setValue(this.identity)&&this._setValue(this.scope):this._removeValue(this.identity)&&this._setValue(this.scope)}catch(b){console&&console.log(b)}},this._setValue=function(b){if(!b)return!1;var c=!0,d=1e4;if(this.expires){var e=new Date,f=e.getTime(),g=f+1e3*this.expires;d=Math.ceil(this.expires/86400),c=JSON.stringify({expires:g})}var h=!0;if(localStorage&&!this.useCookies){h=!1;try{localStorage.setItem(b,c)}catch(i){console&&console.log(i),h=!0}}return h&&a.onepress.tools.cookie(b,c,{expires:d,path:"/"}),!0},this._getValue=function(b){if(!b)return!1;var c=localStorage&&!this.useCookies&&localStorage.getItem(b);if(c||(c=a.onepress.tools.cookie(b)),c)try{var d=JSON.parse(c);if(d&&d.expires){var e=new Date;return d.expires>e}return!0}catch(f){return!0}},this._removeValue=function(b){return b?(localStorage&&localStorage.removeItem(b),a.onepress.tools.cookie(b,null),void 0):!1}}}(jQuery);;
/*!
 * Facebook SDK Connector
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.sdk||(a.onepress.sdk={}),a.onepress.sdk.facebook=a.onepress.sdk.facebook||{name:"facebook",url:"//connect.facebook.net/{lang}/all.js",scriptId:"facebook-jssdk",container:"iframe",timeout:1e4,isLoaded:function(){return"object"==typeof window.FB},prepare:function(){0===a("#fb-root").length&&a("<div id='fb-root'></div>").appendTo(a("body"));var b=this.options&&this.options.lang||"en_US";this.url=this.url.replace("{lang}",b)},createEvents:function(){var b=this,c=this.isLoaded(),d=function(){window.FB.init({appId:b.options&&b.options.appId||null,status:!0,cookie:!0,xfbml:!0}),window.FB.Event.subscribe("edge.create",function(b){a(document).trigger("onp-sl-facebook-like",[b])}),window.FB.init=function(){},a(document).trigger(b.name+"-init")};if(c)return d(),void 0;if(window.fbAsyncInit)var e=window.fbAsyncInit;window.fbAsyncInit=function(){d(),e&&e(),window.fbAsyncInit=function(){}}}}}(jQuery);;
/*!
 * Twitter SDK Connector
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.sdk||(a.onepress.sdk={}),a.onepress.sdk.twitter=a.onepress.sdk.twitter||{name:"twitter",url:"//platform.twitter.com/widgets.js",scriptId:"twitter-wjs",container:"iframe",timeout:1e4,isLoaded:function(){return"undefined"!=typeof window.__twttrlr},createEvents:function(){var b=this,c=this.isLoaded(),d=function(){!a.onepress.browser.msie||11!==a.onepress.browser.version&&10!==a.onepress.browser.version||window.twttr.events.bind("click",function(b){a(document).trigger("onp-sl-twitter-tweet",[b]),a(document).trigger("onp-sl-twitter-follow",[b])}),window.twttr.events.bind("tweet",function(b){a(document).trigger("onp-sl-twitter-tweet",[b])}),window.twttr.events.bind("follow",function(b){a(document).trigger("onp-sl-twitter-follow",[b])}),a(document).trigger(b.name+"-init")};return c?(d(),void 0):(window.twttr||(window.twttr={}),window.twttr.ready||(window.twttr=a.extend(window.twttr,{_e:[],ready:function(a){this._e.push(a)}})),twttr.ready(function(){d()}),void 0)}}}(jQuery);;
/*!
 * Google SDK Connector
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.sdk||(a.onepress.sdk={}),a.onepress.sdk.google=a.onepress.sdk.google||{name:"google",url:"//apis.google.com/js/plusone.js",scriptId:"google-jssdk",container:"iframe",timeout:1e4,isLoaded:function(){return"object"==typeof window.gapi},prepare:function(){var b=this.options&&this.options.lang||"en";window.___gcfg=window.___gcfg||{lang:b},window.onepressPlusOneCallback=function(b){"on"===b.state&&a(document).trigger("onp-sl-google-plus",[b.href])},window.onepressGoogleShareCallback=function(b){a(document).trigger("onp-sl-google-share",[b.id])}}}}(jQuery);;
/*!
 * LinkedIn SDK Connector
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.onepress||(a.onepress={}),a.onepress.sdk||(a.onepress.sdk={}),a.onepress.sdk.linkedin=a.onepress.sdk.linkedin||{name:"linkedin",url:"//platform.linkedin.com/in.js",scriptId:"linkedin-jssdk",container:".IN-widget",timeout:1e4,isLoaded:function(){return"object"==typeof window.IN},prepare:function(){window.onepressLinkedInShareCallback=function(b){a(document).trigger("onp-sl-linkedin-share",[b])};var b=window.open;window.open=function(c,d,e){var f=b(c,d,e);return f?f.name?"easyXDM_IN"!==f.name.substring(0,10)?f:(a.onepress.sdk.linkedin._activePopup=f,void 0):f:f}}}}(jQuery);;
/*!
 * Facebook Like Button
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker_facebook_like||a.onepress.widget("sociallocker_facebook_like",{options:{},_loggedIntoFacebook:!1,_defaults:{url:null,appId:0,lang:"en_US",layout:"standart",width:"auto",verbToDisplay:"like",colorScheme:"light",font:"tahoma",ref:null,count:"standart",unlock:null},getUrlToLike:function(){return this.url},_create:function(){var b=this;this._prepareOptions(),this._setupEvents(),this._createButton(),a.onepress.connector.connect("facebook",this.options,function(){b.wrap.find(".fake-fb-like").addClass("fb-like"),b.wrap.find(".fake-fb-share").addClass("fb-share-button"),window.FB.XFBML.parse(b.wrap[0])})},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options),this.url=URL.normalize(this.options.url||window.location.href)},_setupEvents:function(){var b=this;a(document).bind("onp-sl-facebook-like",function(a,c){b.options.unlock&&b.url===URL.normalize(c)&&b.options.unlock(c,b)})},_createButton:function(){this.button=a("<div class='fake-fb-like' data-show-faces='false'></div>"),this.wrap=a("<div class='onp-social-button onp-facebook-button onp-facebook-like-button'></div>").appendTo(this.element).append(this.button),"none"===this.options.count&&this.wrap.addClass("onp-facebook-like-count-none"),this.wrap.addClass("onp-facebook-like-"+this.options.lang),this.button.data("facebook-widget",this),this.button.attr("data-show-faces",!1),this.button.attr("data-send",!1),this.options.url&&this.button.attr("data-href",this.options.url),this.options.font&&this.button.attr("data-font",this.options.font),this.options.colorScheme&&this.button.attr("data-colorscheme",this.options.colorScheme),this.options.ref&&this.button.attr("data-ref",this.options.ref),this.options.width&&this.button.attr("data-width",this.options.width),this.options.layout&&this.button.attr("data-layout",this.options.layout),this.options.verbToDisplay&&this.button.attr("data-action",this.options.verbToDisplay),this.button.data("url-to-verify",this.url)}})}(jQuery);;
/*!
 * Facebook Share Button
 * Copyright 2014, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker_facebook_button||a.onepress.widget("sociallocker_facebook_share",{options:{},_defaults:{url:null,appId:0,layout:"button",count:"standart",lang:"en_US",width:"auto",name:null,caption:null,description:null,image:null,unlock:null},_create:function(){var b=this;this._prepareOptions(),this._setupEvents(),this._createButton(),a.onepress.connector.connect("facebook",this.options,function(){b.wrap.find(".fake-fb-like").addClass("fb-like"),b.wrap.find(".fake-fb-share").addClass("fb-share-button"),window.FB.XFBML.parse(b.wrap[0])})},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options),this.url=URL.normalize(this.options.url||window.location.href)},_setupEvents:function(){var b=this;a(document).bind("onp-sl-facebook-share",function(a,c){b.options.unlock&&b.url===URL.normalize(c)&&b.options.unlock(c,b)})},_createButton:function(){var b=this,b=this;this.button=a("<div class='fake-fb-share' data-show-faces='false'></div>"),this.wrap=a("<div class='onp-social-button onp-facebook-button onp-facebook-share-button'></div>").appendTo(this.element).append(this.button),"none"===this.options.count&&this.wrap.addClass("onp-facebook-share-count-none"),this.wrap.addClass("onp-facebook-share-"+this.options.lang),this.button.data("facebook-widget",this),this.options.url&&this.button.attr("data-href",this.options.url),this.options.width&&this.button.attr("data-width",this.options.width),this.options.layout&&(this.button.attr("data-layout",this.options.layout),this.button.attr("data-type",this.options.layout));var c=a("<div class='onp-facebook-share-button-overlay'></div>").appendTo(this.wrap);c.click(function(){return FB.ui({method:"feed",name:b.options.name,link:b.url,picture:b.options.image,caption:b.options.caption,description:b.options.description},function(c){c&&c.post_id&&a(document).trigger("onp-sl-facebook-share",[b.url])}),!1})}})}(jQuery);;
/*!
 * Twitter Button widget for jQuery
 * Copyright 2013, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker_twitter_button||(a.onepress.widget("sociallocker_twitter_button",{options:{},_defaults:{url:null,type:null,text:null,via:null,showScreenName:!1,related:null,count:"horizontal",showCount:!0,lang:"en",counturl:null,size:"large",unlock:null},_create:function(){var b=this;this._prepareOptions(),this._setupEvents(),this._createButton(),a.onepress.connector.connect("twitter",this.options,function(){var a=b.wrap,c=5,d=function(){if(!(a.find("iframe").length>0))if(window.twttr.widgets&&window.twttr.widgets.load)window.twttr.widgets.load(a[0]),b.element.trigger("tw-render");else{if(0>=c)return;c--,setTimeout(function(){d()},1e3)}};d()})},_prepareOptions:function(){var b=a.extend({},this._defaults);for(var c in this._defaults)void 0!==this.element.data(c)&&(b[c]=this.element.data(c));this.options=a.extend(b,this.options),!this.options.url&&a("link[rel='canonical']").length>0&&(this.options.url=a("link[rel='canonical']").attr("href")),this.url=URL.normalize(this.options.url||window.location.href)},_setupEvents:function(){var b=this;a(document).bind("onp-sl-twitter-tweet",function(c,d){if(d&&d.target&&"tweet"===b.options.type){var e=URL.normalize(a(d.target).parents(".onp-social-button").attr("data-url-to-compare"));b.url===e&&b.options.unlock&&b.options.unlock&&b.options.unlock(e,b)}}),a(document).bind("onp-sl-twitter-follow",function(c,d){if(d&&d.target&&"follow"===b.options.type){var e=URL.normalize(a(d.target).parents(".onp-social-button").attr("data-url-to-compare"));b.url===e&&b.options.unlock&&b.options.unlock&&b.options.unlock(e,b)}})},_createButton:function(){var b="";"follow"===this.options.type&&(this.options.url||(b="[Error] Setup an URL of your Twitter account.")),this.button=a("<a href='https://twitter.com/share'>"+b+"</a>"),this.button.data("twitter-widget",this),this.wrap=a("<div class='onp-social-button onp-twitter-button'></div>").appendTo(this.element),"tweet"===this.options.type&&(this.wrap.addClass("onp-twitter-tweet"),this.button.addClass("twitter-share-button")),"follow"===this.options.type&&(this.wrap.addClass("onp-twitter-follow"),this.button.addClass("twitter-follow-button")),"follow"===this.options.type?this.button.attr("href",this.url):this.button.attr("data-url",this.url),this.button.attr("data-show-count",this.options.showCount),this.options.via&&this.button.attr("data-via",this.options.via),this.options.text&&this.button.attr("data-text",this.options.text),this.options.related&&this.button.attr("data-related",this.options.related),this.options.count&&this.button.attr("data-count",this.options.count),this.options.showCount&&this.button.attr("data-show-count",this.options.showCount),this.options.lang&&this.button.attr("data-lang",this.options.lang),this.options.counturl&&this.button.attr("data-counturl",this.options.counturl),this.options.hashtags&&this.button.attr("data-hashtags",this.options.hashtags),this.options.alignment&&this.button.attr("data-alignment",this.options.alignment),this.options.size&&this.button.attr("data-size",this.options.size),this.options.dnt&&this.button.attr("data-dnt",this.options.dnt),this.options.showScreenName&&this.button.attr("data-show-screen-name",this.options.showScreenName),this.wrap.attr("data-url-to-compare",this.url),this.wrap.append(this.button)}}),a.fn.sociallocker_twitter_tweet=function(b){return b.type="tweet",a(this).sociallocker_twitter_button(b)},a.fn.sociallocker_twitter_follow=function(b){return b.type="follow",a(this).sociallocker_twitter_button(b)})}(jQuery);;
/*!
 * Google Plus One
 * Copyright 2013, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker_google_button||(a.onepress.widget("sociallocker_google_button",{options:{},_defaults:{url:null,type:null,lang:"en-US",size:null,annotation:null,width:null,align:"left",expandTo:"",recommendations:!0,unlock:null},_create:function(){var b=this;this._prepareOptions(),this._setupEvents(),this._createButton(),a.onepress.connector.connect("google",this.options,function(){setTimeout(function(){var a=b.wrap,c=a.find(".fake-g-plusone");if(c.length>0)return b._addCallbackToControl(a),c.addClass("g-plusone"),window.gapi.plusone.go(a[0]),void 0;var d=a.find(".fake-g-share");return d.length>0?(d.attr("data-onendinteraction","onepressGoogleShareCallback"),d.addClass("g-plus").attr("data-action","share"),window.gapi.plus.go(a[0]),void 0):void 0},100)})},_prepareOptions:function(){var b=a.extend({},this._defaults);this.options=a.extend(b,this.options),this.url=URL.normalize(this.options.url||window.location.href)},_setupEvents:function(){var b=this;"plus"===this.options.type&&a(document).bind("onp-sl-google-plus",function(c,d){a(".gc-bubbleDefault").hide(),b.options.unlock&&b.url==URL.normalize(d)&&b.options.unlock(d,b)}),"share"===this.options.type&&a(document).bind("onp-sl-google-share",function(c,d){a(".gc-bubbleDefault").hide(),b.options.unlock&&b.url==URL.normalize(d)&&b.options.unlock(d,b)})},_createButton:function(){this.button="plus"==this.options.type?a("<div class='fake-g-plusone'></div>"):a("<div class='fake-g-share'></div>"),this.wrap=a("<div class='onp-social-button onp-google-button'></div>").appendTo(this.element).append(this.button),this.button.data("google-widget",this),this.options.url&&this.button.attr("data-href",this.options.url),this.options.size&&this.button.attr("data-size",this.options.size),this.options.annotation&&this.button.attr("data-annotation",this.options.annotation),this.options.align&&this.button.attr("data-align",this.options.align),this.options.expandTo&&this.button.attr("data-expandTo",this.options.expandTo),this.options.recommendations&&this.button.attr("data-recommendations",this.options.recommendations)},_addCallbackToControl:function(a){var b=a.is(".g-plusone")?a:a.find(".fake-g-plusone"),c=b.attr("data-callback");if(c&&"onepressPlusOneCallback"!==c){var d="__plusone_"+c;window[d]=function(a){window[c](a),window.onepressPlusOneCallback(a)},b.attr("data-callback",d)}else b.attr("data-callback","onepressPlusOneCallback")}}),a.fn.sociallocker_google_plus=function(b){return b.type="plus",a(this).sociallocker_google_button(b)},a.fn.sociallocker_google_share=function(b){return b.type="share",a(this).sociallocker_google_button(b)})}(jQuery);;
/*!
 * LinkedIn Share Button widget for jQuery
 * Copyright 2013, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker_linkedin_share||a.onepress.widget("sociallocker_linkedin_share",{options:{},_defaults:{url:null,counter:"right",unlock:null},_create:function(){this._prepareOptions(),this._setupEvents(),this._createButton()},_prepareOptions:function(){var b=a.extend({},this._defaults);for(var c in this._defaults)void 0!==this.element.data(c)&&(b[c]=this.element.data(c));this.options=a.extend(b,this.options),this.url=URL.normalize(this.options.url||window.location.href)},_setupEvents:function(){var b=this;this.element.bind("onp-sl-button-created",function(){b.wrap.find(".IN-widget").click(function(){setTimeout(function(){if(a.onepress.sdk.linkedin._activePopup){var c=a.onepress.sdk.linkedin._activePopup;a.onepress.sdk.linkedin._activePopup=!1;var d=setInterval(function(){c&&c.closed===!1||(clearInterval(d),a(document).trigger("onp-sl-linkedin-share",[b.url]))},200)}},200)})}),a(document).bind("onp-sl-linkedin-share",function(a,c){console.log("onp-sl-linkedin-share"),b.url===URL.normalize(c)&&b.options.unlock&&b.options.unlock&&b.options.unlock(c,b)})},_createButton:function(){this.button=a('<script type="IN/Share" data-onsuccess="onepressLinkedInShareCallback" data-success="onepressLinkedInShareCallback" data-onSuccess="onepressLinkedInShareCallback"></script>'),this.options.counter&&this.button.attr("data-counter",this.options.counter),this.options.url&&this.button.attr("data-url",this.url),this.wrap=a("<div class='onp-social-button onp-linkedin-button'></div>").appendTo(this.element).append(this.button),a.onepress.connector.connect("linkedin",this.options,function(){IN.init()})}})}(jQuery);;
/*!
 * Social Locker plugin for jQuery
 * Copyright 2013, OnePress, http://byonepress.com
*/
!function(a){"use strict";a.fn.sociallocker||(a.onepress.widget("sociallocker",{options:{},_isLocked:!1,_defaults:{_iPhoneBug:!1,url:null,text:{header:a.onepress.sociallocker.lang.defaultHeader,message:a.onepress.sociallocker.lang.defaultMessage},theme:"starter",cssClass:null,demo:!1,buttons:{layout:"horizontal",order:["twitter-tweet","facebook-like","google-plus"],counter:!0},googleAnalytics:!1,locker:{close:!1,timer:0,mobile:!0,expires:!1,useCookies:!1,scope:!1},content:null,effects:{flip:!1,highlight:!0},facebook:{url:null,appId:null,lang:"en_US",colorScheme:"light",font:"tahoma",ref:null,like:{title:a.onepress.sociallocker.lang.facebook_like,theConfirmIssue:!1},share:{title:a.onepress.sociallocker.lang.facebook_share}},twitter:{url:null,via:null,text:null,related:null,lang:"en",counturl:null,tweet:{title:a.onepress.sociallocker.lang.twitter_tweet},follow:{title:a.onepress.sociallocker.lang.twitter_follow}},google:{url:null,lang:"en-US",annotation:null,recommendations:!0,plus:{title:a.onepress.sociallocker.lang.google_plus},share:{title:a.onepress.sociallocker.lang.google_share}},linkedin:{url:null,counter:"right",share:{title:a.onepress.sociallocker.lang.linkedin_share}}},getState:function(){return this._isLocked?"locked":"unlocked"},_create:function(){var b=this;if(this.events={lock:function(a,c){b.element.trigger("lock.sociallocker.onp",[a,c])},unlock:function(a,c,d){if(b.element.trigger("unlock.sociallocker.onp",[a,c]),b.options.googleAnalytics&&(window._gaq||window.ga)&&"button"===a){var e=null,f=null;"facebook-like"===c?(f="Facebook Like",e="Got a Like on Facebook"):"facebook-share"===c?(f="Facebook Share",e="Shared on Facebook"):"twitter-tweet"===c?(f="Twitter Tweet",e="Shared on Twitter"):"twitter-follow"===c?(f="Twitter Follow",e="Got a Follower on Twitter"):"google-plus"===c?(f="Google Plus",e="Got +1 on Google"):"google-share"===c?(f="Google Share",e="Shared on Google"):"linkedin-share"===c&&(f="Linkedin Share",e="Shared on Linkedin"),window.ga?window.ga("send","event","Social Locker (Leakages)","Unlocked by Social Button",f):window._gaq.push(["_trackEvent","Social Locker (Leakages)","Unlocked by Social Button",f]),window.ga?window.ga("send","event","Social Locker (Activity)",e,d):window._gaq.push(["_trackEvent","Social Locker (Activity)",e,d])}},unlockByClose:function(){b.element.trigger("unlockByClose.sociallocker.onp",[]),b.options.googleAnalytics&&(window._gaq||window.ga)&&(window.ga?window.ga("send","event","Social Locker (Leakages)","Unlocked by Close Icon",null):window._gaq.push(["_trackEvent","Social Locker (Leakages)","Unlocked by Close Icon",null]))},unlockByTimer:function(){b.element.trigger("unlockByTimer.sociallocker.onp",[]),b.options.googleAnalytics&&(window._gaq||window.ga)&&(window.ga?window.ga("send","event","Social Locker (Leakages)","Unlocked by Timer",null):window._gaq.push(["_trackEvent","Social Locker (Leakages)","Unlocked by Timer",null]))}},this._processOptions(),a.onepress.browser.msie&&7===parseInt(a.onepress.browser.version,10))return this._unlock("ie7"),void 0;if(!this.options.locker.mobile&&this._isMobile())return this._unlock("mobile"),void 0;if(/iPhone/i.test(navigator.userAgent)&&this.options._iPhoneBug){var c=a.inArray("twitter-tweet",this.options.buttons.order);c>=0&&this.options.buttons.order.splice(c,1)}if(a.onepress.browser.opera||a.onepress.browser.msie||this._isTabletOrMobile()){var d=a.inArray("google-share",this.options.buttons.order);d>=0&&this.options.buttons.order.splice(d,1)}return 0===this.options.buttons.order.length?(this._unlock("nobuttons"),void 0):(this._controller=this._createProviderController(),this._controller.getState(function(a){a?b._unlock("provider"):b._lock()}),b.options.locker.scope&&a(document).bind("unlockByScope.sl.onp",function(a,c,d){c!==b.element&&b.options.locker.scope===d&&b._unlock("scope")}),void 0)},_createProviderController:function(){var b=this;this._providers={};var c=0;for(var d in this.options.buttons.order){var e=this.options.buttons.order[d];if("string"==typeof e){if(!this._isValidButton(e))return this._setError("The button '"+e+"' not found."),void 0;if("#"!=e){var f=e.split("-"),g=f[0],h=f[1],i=a.extend({},this.options[g]);this.options[g][h]&&(i=a.extend(i,this.options[g][h]));var j=i.url||this.options.url||window.location.href;this._providers[e]||(this._providers[e]=new a.onepress.providers.clientStoreStateProvider(g,h,j,b.options),c++)}}}return{getState:function(a){var d=c,e=!1;for(var f in b._providers){var g=b._providers[f];g.getState(function(b){d--,e=e||b,0==d&&a(e,g)})}}}},_processOptions:function(){var b=this.options.theme||this._defaults.theme,c=a.extend(!0,{},this._defaults);a.onepress.sociallocker.presets[b]&&(c=a.extend(!0,{},c,a.onepress.sociallocker.presets[b]),a.onepress.sociallocker.presets[b].buttons&&a.onepress.sociallocker.presets[b].buttons.order&&(c.buttons.order=a.onepress.sociallocker.presets[b].buttons.order)),c=a.extend(!0,c,this.options),this.options.buttons&&this.options.buttons.order&&(c.buttons.order=this.options.buttons.order),c.effects.flip=c.effects.flip||"onp-sociallocker-secrets"==c.style,c.buttons.layout||(c.buttons.layout="horizontal"),"vertical"===c.buttons.layout&&(c.facebook.like.layout="box_count",c.facebook.share.layout="box_count",c.twitter.count="vertical",c.twitter.size="medium",c.google.plus.size="tall",c.google.share.annotation="vertical-bubble",c.linkedin.share.counter="top",c.buttons.counter=!0),"horizontal"===c.buttons.layout&&(c.facebook.layout="button_count",c.twitter.count="horizontal",c.twitter.size="medium",c.google.size="medium",c.google.annotation="bubble",c.linkedin.share.counter="right",c.buttons.counter||(c.twitter.count="none",c.twitter.showCount=!1,c.google.annotation="none",c.facebook.count="none",c.linkedin.share.counter="none")),"object"!=typeof c.text&&(c.text={message:c.text}),c.text.header&&(c.text.header="function"==typeof c.text.header&&c.text.header(this)||"string"==typeof c.text.header&&a("<div>"+c.text.header+"</div>")||"object"==typeof c.text.header&&c.text.header.clone()),c.text.message&&(c.text.message="function"==typeof c.text.message&&c.text.message(this)||"string"==typeof c.text.message&&a("<div>"+c.text.message+"</div>")||"object"==typeof c.text.message&&c.text.message.clone()),c.locker.timer=parseInt(c.locker.timer),0==c.locker.timer&&(c.locker.timer=null),this.options=c,this.style="onp-sociallocker-"+b},_isMobile:function(){return/webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)?!0:/Android/i.test(navigator.userAgent)&&/Mobile/i.test(navigator.userAgent)?!0:!1},_isTabletOrMobile:function(){return/webOS|iPhone|iPad|Android|iPod|BlackBerry/i.test(navigator.userAgent)?!0:!1},_isValidButton:function(b){for(var c in a.onepress.sociallocker.buttons)if(a.onepress.sociallocker.buttons[c]==b)return!0;return!1},_setError:function(a){this._error=!0,this._errorText=a,this.locker&&this.locker.hide(),this.element.html("<strong>[Error]: "+a+"</strong>"),this.element.show().addClass("onp-sociallocker-error")},_createMarkup:function(){this.element.addClass("onp-sociallocker-content");var b=a.onepress.browser.mozilla&&"mozilla"||a.onepress.browser.opera&&"opera"||a.onepress.browser.webkit&&"webkit"||"msie";this.locker=a("<div class='onp-sociallocker onp-sociallocker-"+b+"' style='display: none;'></div>"),this.outerWrap=a("<div class='onp-sociallocker-outer-wrap'></div>").appendTo(this.locker),this.innerWrap=a("<div class='onp-sociallocker-inner-wrap'></div>").appendTo(this.outerWrap),"fixed"===this.options.buttons.size&&this.locker.addClass("onp-sociallocker-buttons-fixed"),this.locker.addClass(this.style),this.locker.addClass("onp-sociallocker-"+this.options.buttons.layout),this.options.buttons.counter?this.locker.addClass("onp-sociallocker-has-counters"):this.locker.addClass("onp-sociallocker-no-counters"),a.onepress.isTouch()?this.locker.addClass("onp-sociallocker-touch"):this.locker.addClass("onp-sociallocker-no-touch"),this.options.cssClass&&this.locker.addClass(this.options.cssClass);var c=a("<div class='onp-sociallocker-text'></div>");this.options.text.header&&c.append(this.options.text.header.addClass("onp-sociallocker-strong").clone()),this.options.text.message&&c.append(this.options.text.message.addClass("onp-sociallocker-message").clone()),this.innerWrap.append(c.addClass()),c.prepend(a("<div class='onp-sociallocker-before-text'></div>")),c.append(a("<div class='onp-sociallocker-after-text'></div>")),this._createButtonMarkup(),this.options.bottomText&&this.innerWrap.append(this.options.bottomText.addClass("onp-sociallocker-bottom-text")),this.options.locker.close&&this._createClosingCross(),this.options.locker.timer&&this._createTimer();var d=this.element.parent().is("a")?this.element.parent():this.element;this.locker.insertAfter(d),this._markupIsCreated=!0,a.inArray("facebook-like",this.options.buttons.order)>=0&&this._startTrackIFrameSizes()},_createButtonMarkup:function(){var b=this;this.buttonsWrap=a("<div class='onp-sociallocker-buttons'></div>").appendTo(this.innerWrap);var c=50;a.each(this.options.buttons.order,function(d,e){if("string"!=typeof e)return!0;if("#"===e)return b.buttonsWrap.append("<div class='onp-button-separator'></div>"),!0;if(b.options.buttons.unsupported&&jQuery.inArray(e,b.options.buttons.unsupported)>=0){var f='The button "'+e+'" is not supported by this theme.',g=a("<div class='onp-sociallocker-button onp-sociallocker-button-unsupported'></div>"),h=a("<div class='onp-sociallocker-button-inner-wrap'>"+f+"</div>").appendTo(g);return b.buttonsWrap.append(g),!0}var i=e.split("-"),j=i[0],k=i[1],l="sociallocker_"+j+"_"+k,m=a.extend({},b.options[j]);b.options[j][k]&&(m=a.extend(m,b.options[j][k])),m.url=m.url||b.options.url,m._provider=b._providers[e],m.unlock=function(){b._unlock("button",m._provider)};var g=a("<div class='onp-sociallocker-button onp-sociallocker-button-"+e+" onp-sociallocker-state-loading'></div>");g.addClass("onp-sociallocker-button-"+j),g.data("name",e),b.buttonsWrap.append(g);var h=a("<div class='onp-sociallocker-button-inner-wrap'></div>").appendTo(g);h[l](m);var n=a.onepress.sdk[j],o=!1;setTimeout(function(){o||(g.removeClass("onp-sociallocker-state-loading").addClass("onp-sociallocker-state-error"),b._createErrorMarkup(a.onepress.sociallocker.lang.unableToLoadSDK,j).appendTo(h))},n.timeout),a.onepress.connector.connect(j,m,function(c){o=!0;var d=5e3,f=function(){return 0===g.find(c.container).length&&d>=0?(setTimeout(function(){f()},500),d-=500,void 0):(g.removeClass("onp-sociallocker-state-loading"),0>=d?(g.addClass("onp-sociallocker-state-error"),b._createErrorMarkup(a.onepress.sociallocker.lang.unableToCreateButton,e).appendTo(h),void 0):(h.trigger("onp-sl-button-created"),void 0))};f()});var p=b.options.effects.flip,q=a.onepress.tools.has3d();if(p&&q&&g.addClass("onp-sociallocker-flip")||g.addClass("onp-sociallocker-no-flip"),!p)return!0;var r=b.options.triggers&&b.options.triggers.overlayRender?b.options.triggers.overlayRender(m,j,k,a.onepress.isTouch()):a("<a class='onp-sociallocker-button-overlay' href='#'></a>");r.prependTo(h),q||g.hover(function(){var b=a(this).find(".onp-sociallocker-button-overlay");b.stop().animate({opacity:0},200,function(){b.hide()})},function(){var b=a(this).find(".onp-sociallocker-button-overlay").show();b.stop().animate({opacity:1},200)}),a.onepress.isTouch()&&(q?r.click(function(){var b=a(this).parents(".onp-sociallocker-button");return b.hasClass("onp-sociallocker-flip-hover")?b.removeClass("onp-sociallocker-flip-hover"):(a(".onp-sociallocker-flip-hover").removeClass("onp-sociallocker-flip-hover"),b.addClass("onp-sociallocker-flip-hover")),!1}):r.click(function(){var b=a(this);return b.stop().animate({opacity:0},200,function(){b.hide()}),!1})),r&&(r.css("z-index",c),r.find(".onp-sociallocker-overlay-front").css("z-index",c),r.find(".onp-sociallocker-overlay-back").css("z-index",c-1),r.find(".onp-sociallocker-overlay-header").css("z-index",c-1)),c-=5})},_createErrorMarkup:function(b,c){var d=this,e=a("<div class='onp-sociallocker-error-body'><a href='#' class='onp-sociallocker-error-title'>"+a.onepress.sociallocker.lang.error+"</a><div class='onp-sociallocker-error-text'>"+b.replace("{0}",c)+"</div></div>"),f=e.find(".onp-sociallocker-error-text"),g=e.find(".onp-sociallocker-error-title");return g.click(function(){return d.locker.find(".onp-sociallocker-shown-error").remove(),e.hasClass("onp-sociallocker-active")?e.removeClass("onp-sociallocker-active"):(d.locker.find(".onp-sociallocker-active").removeClass("onp-sociallocker-active"),e.addClass("onp-sociallocker-active"),d.buttonsWrap.after(f.clone().addClass("onp-sociallocker-shown-error"))),!1}),e},_createClosingCross:function(){var b=this;a("<div class='onp-sociallocker-cross' title='"+a.onepress.sociallocker.lang.close+"' />").prependTo(this.locker).click(function(){b.close&&b.close(b)||b._unlock("cross",!0)})},_createTimer:function(){this.timer=a("<span class='onp-sociallocker-timer'></span>");var b=a.onepress.sociallocker.lang.orWait,c=a.onepress.sociallocker.lang.seconds;this.timerLabel=a("<span class='onp-sociallocker-timer-label'>"+b+" </span>").appendTo(this.timer),this.timerCounter=a("<span class='onp-sociallocker-timer-counter'>"+this.options.locker.timer+c+"</span>").appendTo(this.timer),this.timer.appendTo(this.locker),this.counter=this.options.locker.timer,this._kickTimer()},_kickTimer:function(){var b=this;setTimeout(function(){if(b._isLocked)if(b.counter--,b.counter<=0)b._unlock("timer");else{if(b.timerCounter.text(b.counter+a.onepress.sociallocker.lang.seconds),a.onepress.browser.opera){var c=b.timerCounter.clone();c.insertAfter(b.timerCounter),b.timerCounter.remove(),b.timerCounter=c}b._kickTimer()}},1e3)},_startTrackIFrameSizes:function(){if(!this.options.facebook.like.theConfirmIssue){var b=this;this._trackIFrameTimer=null,this.locker.hover(function(){var c=b.locker.find(".onp-facebook-like-button"),d=c.find("iframe");d.length&&(b._trackIFrameTimer=setInterval(function(){var e=parseInt(d[0].style.height);if(e||(e=d.height()),e>200){b._stopTrackIFrameSizes();var f=c.find(".fake-fb-like").data("url-to-verify");a(document).trigger("onp-sl-facebook-like",[f])}},500))},function(){b._stopTrackIFrameSizes()})}},_stopTrackIFrameSizes:function(){this._trackIFrameTimer&&clearInterval(this._trackIFrameTimer)},_lock:function(a,b){this._isLocked||this._stoppedByWatchdog||(this._markupIsCreated||this._createMarkup(),"button"===a&&b.setState("locked"),this.element.hide(),this.isInline?this.locker.css("display","inline-block"):this.locker.fadeIn(1e3),this._isLocked=!0,this.events.lock&&this.events.lock(a,b&&b.name))},_unlock:function(b,c){var d=this;return this._isLocked?("button"===b&&(c.setState("unlocked"),d.options.locker.scope&&a(document).trigger("unlockByScope.sl.onp",[d.element,d.options.locker.scope])),this._showContent(!0),"scope"!==b&&(this._isLocked=!1,"timer"===b&&this.events.unlockByTimer&&this.events.unlockByTimer(),"cross"===b&&this.events.unlockByClose&&this.events.unlockByClose(),this.events.unlock&&this.events.unlock(b,c&&c.name,c&&c.url)),void 0):(this._showContent("button"!==b),!1)},lock:function(){this._lock("user")},unlock:function(){this._unlock("user")},_showContent:function(b){var c=this;this._stopTrackIFrameSizes();var d=function(){return c.locker&&c.locker.hide(),b?(c.element.fadeIn(1e3,function(){c.options.effects.highlight&&c.element.effect&&c.element.effect("highlight",{color:"#fffbcc"},800)}),void 0):(c.element.show(),void 0)};if(this.options.content)if("string"==typeof this.options.content)this.element.html(this.options.content),d();else if("object"!=typeof this.options.content||this.options.content.url)if("object"==typeof this.options.content&&this.options.content.url){var e=a.extend(!0,{},this.options.content),f=e.success,g=e.complete,h=e.error;e.success=function(a,b,e){f?f(c,a,b,e):c.element.html(a),d()},e.error=function(a,b,d){c._setError("An error is triggered during the ajax request! Text: "+b+" "+d),h&&h(a,b,d)},e.complete=function(a,b){g&&g(a,b)},a.ajax(e)}else d();else this.element.append(this.options.content.clone().show()),d();else d()}}),a.fn.socialLock=function(b){b=a.extend({},b),a(this).sociallocker(b)})}(jQuery);

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
            $target.bind('unlock.sociallocker.onp', function(e, sender, senderName){
                if ( $.inArray(sender, ['cross', 'button', 'timer']) === -1 ) return;

                $.ajax({ url: data.ajaxUrl, type: 'POST', data: {
                    action: 'sociallocker_tracking',
                    targetId: data.postId,
                    sender: sender,
                    senderName: senderName
                    }
                });
            });
        }

        $target.removeClass("onp-sociallocker-call");
        if ( !window.onpsl.lockerOptions[lockId] ) $target.next().remove();

        $target.sociallocker( options );
    };    
    
    // adding support for dynamic themes
    
    var bindFunction = function(){
        $(document).ajaxComplete(function() {
            window.onpsl.lockers();
        });
            
        if ( !window.onpsl.dynamicThemeSupport ) return;

        if ( window.onpsl.dynamicThemeEvent != '' ) {
            $(document).bind(window.onpsl.dynamicThemeEvent, function(){
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

/*!
 * Creater Script  
 * Copyright 2014, OnePress, http://byonepress.com
*/
(function($){ 
    $(function(){ window.onpsl.lockers(); });
})(jQuery);