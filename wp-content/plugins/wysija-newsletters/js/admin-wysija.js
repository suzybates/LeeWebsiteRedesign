jQuery(function(a){function i(i){i?(a(".wysija-premium-activate").addClass("wysija-button-loading"),a(".wysija-premium-activate").html(wysijatrans.premium_activating+"<span>.</span><span>.</span><span>.</span>")):(a(".wysija-premium-activate").removeClass("wysija-button-loading"),a(".wysija-premium-activate").html(wysijatrans.premium_activate))}a("#wysija-app .submitdelete").click(function(){return confirm(wysijatrans.suredelete)}),a("#wysija-app .linkignore, .wysija-version .linkignore").click(function(){var i=this;return wysijaAJAX.controller="config",wysijaAJAX.task="linkignore",wysijaAJAX.ignorewhat=a.trim(a(this).attr("class").replace(/linkignore/g,"")),wysijaAJAX._wpnonce=a("#wysijax").val(),a.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:function(){a(i).parents(".removeme").length>0?a(i).parents(".removeme").fadeOut():a(i).parents("li").siblings().size()>0?a(i).parents("li").fadeOut():a(i).parents("div.updated").fadeOut()},error:function(a){alert("Request error not JSON:"+a.responseText),wysijaAJAXcallback.onSuccess=""},dataType:"json"}),!0}),a(document).on("click","#wysija-app .premium-tab, .wysija-msg .premium-tab, #theme-view .premium-tab",function(){a("#wysija-app .wysija-premium img").hide(),a(this).hasClass("ispopup")?(window.parent.tb_remove(),window.parent.location.href=wysijatrans.urlpremium):a("#wysija-tabs").length>0?a('#wysija-tabs a[href="#premium"]').trigger("click"):window.location.href=wysijatrans.urlpremium}),a(".wysija-premium-activate").click(function(){wysijaAJAX.controller="config",wysijaAJAX.task="validate",wysijaAJAX._wpnonce=a("#wysijax").val(),i(!0);var e=this;return a.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:function(s){s.result.result?window.location.href="admin.php?page=wysija_campaigns&sp=1":(s.result.nocontact?window.location.href="admin.php?page=wysija_campaigns&nocontact=1":(displaychange=0,a.WYSIJA_HANDLE_RESPONSE(s)),i(!1)),a(e).removeClass("wysija-button-loading")},error:function(a){alert(a.responseText),delete wysijaAJAXcallback.onSuccess,i(!1)},dataType:"json"}),!1}),a("#premium-deactivate").click(function(){return wysijaAJAX.controller="config",wysijaAJAX.task="devalidate",a("#wysija-app .wysija-premium img").hide(),wysijaAJAX._wpnonce=a("#wysijax").val(),a.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:function(){a("#wysija-app .wysija-premium img").hide(),window.location.reload()},error:function(i){a("#wysija-app .wysija-premium img").hide(),alert("Request error not JSON:"+i.responseText),wysijaAJAXcallback.onSuccess=""},dataType:"json"}),!0}),a("#install-wjp,#switch_to_package").click(function(){return confirm(a(this).data("warn"))?(tb_show(a(this).attr("title"),a(this).attr("href")+"&KeepThis=true&TB_iframe=true&height=400&width=600",null),tb_showIframe(),!1):!1}),a("#share_analytics").click(function(){var i=this;return wysijaAJAX.controller="config",wysijaAJAX.task="share_analytics",wysijaAJAX._wpnonce=a("#wysijax").val(),a.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:function(){a(i).text("Thanks!").contents().unwrap()},error:function(a){alert("Request error not JSON:"+a.responseText),wysijaAJAXcallback.onSuccess=""},dataType:"json"}),!0})});
