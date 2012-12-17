/**
 *
 * Style Select
 *
 * Replace Select text
 * Dependencies: jQuery
 *
 */(function(e){styleSelect={init:function(){e(".select_wrapper").each(function(){e(this).prepend("<span>"+e(this).find(".woo-input option:selected").text()+"</span>")});e("select.woo-input").live("change",function(){e(this).prev("span").replaceWith("<span>"+e(this).find("option:selected").text()+"</span>")});e("select.woo-input").bind(e.browser.msie?"click":"change",function(t){e(this).prev("span").replaceWith("<span>"+e(this).find("option:selected").text()+"</span>")})}}})(jQuery);jQuery(document).ready(function(){styleSelect.init()});