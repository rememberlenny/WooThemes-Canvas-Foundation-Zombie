/*
 * jQuery Foundation Tooltips 2.0.1
 * http://foundation.zurb.com
 * Copyright 2012, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*//*jslint unparam: true, browser: true, indent: 2 */(function(e,t,n){"use strict";var r={bodyHeight:0,targetClass:".has-tip",tooltipClass:".tooltip",tipTemplate:function(e,t){return'<span data-selector="'+e+'" class="'+r.tooltipClass.substring(1)+'">'+t+'<span class="nub"></span></span>'}},i={init:function(t){r=e.extend(r,t);return this.each(function(){var t=e("body");if(Modernizr.touch){t.on("click.tooltip touchstart.tooltip touchend.tooltip",r.targetClass,function(t){t.preventDefault();e(r.tooltipClass).hide();i.showOrCreateTip(e(this))});t.on("click.tooltip touchstart.tooltip touchend.tooltip",r.tooltipClass,function(t){t.preventDefault();e(this).fadeOut(150)})}else t.on("mouseenter.tooltip mouseleave.tooltip",r.targetClass,function(t){var n=e(this);t.type==="mouseenter"?i.showOrCreateTip(n):t.type==="mouseleave"&&i.hide(n)});e(this).data("tooltips",!0)})},showOrCreateTip:function(e){var t=i.getTip(e);t&&t.length>0?i.show(e):i.create(e)},getTip:function(t){var n=i.selector(t),s=null;n&&(s=e("span[data-selector="+n+"]"+r.tooltipClass));return s.length>0?s:!1},selector:function(e){var t=e.attr("id"),r=e.data("selector");if(t===n&&r===n){r="tooltip"+Math.random().toString(36).substring(7);e.attr("data-selector",r)}return t?t:r},create:function(t){var n=e(r.tipTemplate(i.selector(t),e("<div>").text(t.attr("title")).html())),s=i.inheritable_classes(t);n.addClass(s).appendTo("body");Modernizr.touch&&n.append('<span class="tap-to-close">tap to close </span>');t.removeAttr("title");i.show(t)},reposition:function(n,r,i){var s,o,u,a,f,l;r.css("visibility","hidden").show();s=n.data("width");o=r.children(".nub");u=o.outerHeight();a=o.outerWidth();l=function(e,t,n,r,i,s){return e.css({top:t,bottom:r,left:i,right:n,width:s?s:"auto"}).end()};l(r,n.offset().top+n.outerHeight()+10,"auto","auto",n.offset().left,s);l(o,-u,"auto","auto",10);if(e(t).width()<767){f=n.closest(".columns");f.length<0&&(f=e("body"));r.width(f.outerWidth()-25).css("left",15).addClass("tip-override");l(o,-u,"auto","auto",n.offset().left)}else if(i.indexOf("tip-top")>-1){l(r,n.offset().top-r.outerHeight()-u,"auto","auto",n.offset().left,s).removeClass("tip-override");l(o,"auto","auto",-u,"auto")}else if(i.indexOf("tip-left")>-1){l(r,n.offset().top+n.outerHeight()/2-u,"auto","auto",n.offset().left-r.outerWidth()-10,s).removeClass("tip-override");l(o,r.outerHeight()/2-u/2,-u,"auto","auto")}else if(i.indexOf("tip-right")>-1){l(r,n.offset().top+n.outerHeight()/2-u,"auto","auto",n.offset().left+n.outerWidth()+10,s).removeClass("tip-override");l(o,r.outerHeight()/2-u/2,"auto","auto",-u)}r.css("visibility","visible").hide()},inheritable_classes:function(t){var n=["tip-top","tip-left","tip-bottom","tip-right","noradius"],r=e.map(t.attr("class").split(" "),function(t,r){if(e.inArray(t,n)!==-1)return t}).join(" ");return e.trim(r)},show:function(e){var t=i.getTip(e);i.reposition(e,t,e.attr("class"));t.fadeIn(150)},hide:function(e){var t=i.getTip(e);t.fadeOut(150)},reload:function(){var t=e(this);return t.data("tooltips")?t.foundationTooltips("destroy").foundationTooltips("init"):t.foundationTooltips("init")},destroy:function(){return this.each(function(){e(t).off(".tooltip");e(r.targetClass).off(".tooltip");e(r.tooltipClass).each(function(t){e(e(r.targetClass).get(t)).attr("title",e(this).text())}).remove()})}};e.fn.foundationTooltips=function(t){if(i[t])return i[t].apply(this,Array.prototype.slice.call(arguments,1));if(typeof t=="object"||!t)return i.init.apply(this,arguments);e.error("Method "+t+" does not exist on jQuery.foundationTooltips")}})(jQuery,this);