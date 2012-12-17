/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript in the admin for the Woo_Meta extension.
Date Created: 2011-03-21.
Author: Matty.
Since: 4.0.0


TABLE OF CONTENTS

- Interface JavaScript.
- Form Reset Logic.
- Form AJAX Submission.
- Menu Toggle Logic.

- function center() - Centre an element.

-----------------------------------------------------------------------------------*/jQuery(function(e){var t="";jQuery("#woo-nav ul li:first, .content-section:first").addClass("current");jQuery("#content .content-section:first").hide().fadeIn();jQuery("#content .content-section:not(:first)").hide();jQuery("#woo-nav ul li a").click(function(){jQuery("#woo-nav ul li.current").removeClass("current");jQuery(this).parents("li").addClass("current");var e=jQuery(this).attr("href");e=e.replace("#","");jQuery(".content-section:not( #"+e+" )").fadeOut("fast",function(){jQuery("#"+e).fadeIn()});return!1});jQuery("form#wooform input.reset-button").click(function(){t="reset";var e=confirm("Are you sure you want to reset these options? All customised meta will be lost!");if(!e)return!1});jQuery("form#wooform input.submit-button:not(.reset-button)").click(function(){t="save"});jQuery("form#wooform").submit(function(e){e.preventDefault();jQuery("img.ajax-loading-img").fadeIn("slow");jQuery('input[type="submit"]').attr("disabled","disabled");var n=jQuery(this).attr("action"),r=jQuery(this).serialize();switch(t){case"reset":r="&woometa_reset=true&"+r;n=n.replace("updated=true","reset=true");break;case"save":r="&woometa_update=true&"+r}if(n)var i=jQuery.post(n,r,function(e,n,r){var i=jQuery(e).find(".updated"),s=jQuery("<div></div>").attr("id","woo-popup-"+t).addClass("woo-save-popup");s.html('<div class="woo-save-'+t+'">'+i.text()+"</div>");t=="reset"&&jQuery("#woo_container #content").fadeTo("slow",.5,function(){var t=jQuery(e).find("#content");jQuery(this).html(jQuery(t).html()).fadeTo("slow",1)});s.center();jQuery(window).scroll(function(e){s.center()});jQuery(window).resize(function(e){s.center()});s.css("display","block").fadeIn("slow");jQuery("form#wooform").before(s);window.setTimeout(function(){s.fadeOut("slow",function(){s.remove()})},2e3);jQuery("img.ajax-loading-img").fadeOut("slow");jQuery('input[type="submit"]').removeAttr("disabled")});return!1});jQuery("#support-links .submit-button").before('<a href="#" id="expand_options">[+]</a> ');jQuery("a#expand_options").toggle(function(){jQuery(this).text("[-]");jQuery(".group h2").show();jQuery("#woo_container #content").css("width","785px");jQuery("#woo-nav").hide();jQuery(".content-section").show();return!1},function(){jQuery(this).text("[+]");jQuery(".group h2").hide();jQuery("#woo_container #content").removeAttr("style");jQuery("#woo-nav").show();jQuery(".content-section:not(.current)").hide();return!1});jQuery.fn.center=function(){this.stop().animate({top:(jQuery(window).height()-this.height()-200)/2+jQuery(window).scrollTop()+"px"},500);this.css("left",250);return this}});