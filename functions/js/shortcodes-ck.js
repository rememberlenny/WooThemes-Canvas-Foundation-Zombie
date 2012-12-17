/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript used on WooFramework shortcodes.
Date Created: 2011-01-24.
Author: Matty.
Since: 3.5.0


TABLE OF CONTENTS

- Tabs shortcode
- Toggle shortcode

-----------------------------------------------------------------------------------*/jQuery(function(e){jQuery(".shortcode-tabs").length&&jQuery(".shortcode-tabs").each(function(){var e=1;jQuery(this).children(".tab").each(function(t,n){var r=jQuery(this).parents(".shortcode-tabs").attr("id"),i=r+"-tab-"+e;jQuery(this).attr("id",i);jQuery(this).parents(".shortcode-tabs").find("ul.tab_titles").children("li").eq(t).find("a").attr("href","#"+i);e++});var t=jQuery(this).attr("id"),n=jQuery(this).tabs({fx:{opacity:"toggle",duration:200}}),r=window.location.hash;r=r.replace("#","");r!=""&&n.tabs("select",r)});jQuery(".shortcode-toggle").length&&jQuery(".shortcode-toggle").each(function(){var e=jQuery(this);e.closedText=e.find('input[name="title_closed"]').attr("value");e.openText=e.find('input[name="title_open"]').attr("value");if(e.find("a.more-link.read-more").length){e.readMoreText=e.find("a.more-link.read-more").text();e.readLessText=e.find("a.more-link.read-more").attr("readless");e.find("a.more-link.read-more").removeAttr("readless");e.find("a.more-link").click(function(){var t=jQuery(this).next(".more-text");t.animate({opacity:"toggle",height:"toggle"},300).css("display","block");t.toggleClass("open").toggleClass("closed");t.hasClass("open")&&jQuery(this).text(e.readLessText);t.hasClass("closed")&&jQuery(this).text(e.readMoreText);return!1})}e.find('input[name="title_closed"]').remove();e.find('input[name="title_open"]').remove();e.find("h4.toggle-trigger a").click(function(){e.find(".toggle-content").animate({opacity:"toggle",height:"toggle"},300);e.toggleClass("open").toggleClass("closed");e.hasClass("open")&&jQuery(this).text(e.openText);e.hasClass("closed")&&jQuery(this).text(e.closedText);return!1})})});