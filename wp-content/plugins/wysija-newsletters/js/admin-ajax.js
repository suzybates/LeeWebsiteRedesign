var wysijaAJAXcallback=function(){};wysijaAJAXcallback.prototype.onSuccess=function(){};var displaychange=1,popupopen=0;jQuery(function(a){a.WYSIJA_SEND=function(){displaychange=1,0===popupopen?(a(".wysija-msg.ajax").html('<div class="allmsgs" title="'+wysijaAJAX.popTitle+'"><blink>'+wysijaAJAX.loadingTrans+"</blink></div>"),a(".wysija-msg.ajax .allmsgs").dialog({modal:!0,draggable:!1,resizable:!1,width:400,close:function(){displaychange=0,popupopen=0,a(this).remove()}})):a(".allmsgs.ui-dialog-content.ui-widget-content").html("<blink>"+wysijaAJAX.loadingTrans+"</blink>"),popupopen=1,wysijaAJAX._wpnonce=a("#wysijax").val(),"json"===wysijaAJAX.dataType?a.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:a.WYSIJA_HANDLE_RESPONSE,error:function(a){alert("Request error not JSON:"+a.responseText),wysijaAJAXcallback.onSuccess=""},dataType:wysijaAJAX.dataType}):a(".allmsgs.ui-dialog-content.ui-widget-content").load(wysijaAJAX.ajaxurl,wysijaAJAX,function(s){var i=JSON.parse(s);return"object"!=typeof i?!0:(a.WYSIJA_HANDLE_RESPONSE(i),void 0)})},a.WYSIJA_HANDLE_RESPONSE=function(s){a(".allmsgs.ui-dialog-content.ui-widget-content").html(""),a(".wysija-msg.ajax").html('<div class="allmsgs"></div>'),a.each(s.msgs,function(i,l){displaychange?(a(".allmsgs.ui-dialog-content.ui-widget-content ."+i+" ul").length||a(".allmsgs.ui-dialog-content.ui-widget-content").append('<div class="'+i+'"><ul></ul></div>'),a.each(l,function(s,l){a(".allmsgs.ui-dialog-content.ui-widget-content ."+i+" ul").append("<li>"+l+"</li>")})):(a(".wysija-msg.ajax .allmsgs ."+i+" ul").length||a(".wysija-msg.ajax .allmsgs").append('<div class="'+i+'"><ul></ul></div>'),a.each(l,function(s,l){a(".wysija-msg.ajax .allmsgs ."+i+" ul").append("<li>"+l+"</li>")})),a.isFunction(wysijaAJAXcallback.onSuccess)&&wysijaAJAXcallback.onSuccess(s),wysijaAJAXcallback.onSuccess=function(){}})}});
