jQuery(function(u){function n(){return u("#poll_result").html(""),"url"==u(this).val()&&""!=u('input[name="how_did_you_find_us_url"]')?(u('input[name="how_did_you_find_us_url"]').focus(),!1):(wysijaAJAX.task="save_poll",wysijaAJAX.how=u('input[name="how_did_you_find_us"]').val(),wysijaAJAX.where=u('input[name="how_did_you_find_us_url"]').val(),jQuery.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,success:_,error:i,dataType:"json"}),!1)}function _(n){u("#poll_result").html(n.result.msg)}function i(u){alert("Request error not JSON:"+u.responseText)}u('input[name="how_did_you_find_us"]').change(n),u('input[name="how_did_you_find_us_url"]').blur(n)});
