//When DOM Ready...
function CTXPS_Ajax(){}jQuery(function(){var e=jQuery("#ctxps-grouplist-ddl");e.data("options",e.html()).children(".detach").remove();jQuery("#ctxps-grouplist-ddl").data("options",jQuery("#ctxps-grouplist-ddl").html()).children(".detach").remove();jQuery("#ctxps-grouplist-box #ctxps-cb-protect").click(function(){CTXPS_Ajax.toggleContentSecurity("post",parseInt(jQuery("#ctx_ps_post_id").val()),"#ctxps-grouplist-box h3.hndle")});jQuery('#ctxps-grouplist-box label[for="ctx_ps_protectmy"]').click(function(){jQuery("#ctx_ps_protectmy:disabled").length>0&&alert(ctxpsmsg.NoUnprotect)});jQuery("#add_group_page").click(function(){CTXPS_Ajax.addGroupToPage()});jQuery("#edittag #ctxps-cb-protect").click(function(){CTXPS_Ajax.toggleContentSecurity("term",parseInt(jQuery('#edittag input[name="tag_ID"]').val()))});jQuery('#edittag label[for="ctxps-cb-protect"]').click(function(){jQuery("#ctxps-cb-protect:disabled").length>0&&alert(ctxpsmsg.NoUnprotect)});jQuery("#ctxps-grouplist-ddl-btn").click(function(){CTXPS_Ajax.addGroupToTerm()});jQuery("#btn-add-grp-2-user").click(function(){CTXPS_Ajax.addGroupToUser()});jQuery("#enrollit").click(function(){CTXPS_Ajax.addBulkUsersToGroup()});jQuery("#ad-protect-site:checked").click(function(){return jQuery(this).filter(":checked").length===0?confirm(ctxpsmsg.SiteProtectDel):!0});jQuery('#ad-msg-enable, label[for="ad-msg-enable"]').click(function(){var e=jQuery(".toggle-opts-ad-msg"),t=jQuery(".toggle-opts-ad-page"),n=jQuery("#ad-msg-forcelogin:checked").length;jQuery(this).filter(":checked").length?n?e.not(".ad-opt-anon").fadeOut(250,function(){t.not(".ad-opt-anon").fadeIn(250)}):e.fadeOut(250,function(){jQuery(".toggle-opts-ad-page").fadeIn(250)}):n?t.not(".ad-opt-anon").fadeOut(250,function(){e.not(".ad-opt-anon").fadeIn(250)}):t.fadeOut(250,function(){e.fadeIn(250)})});jQuery('#ad-msg-forcelogin, label[for="ad-msg-forcelogin"]').click(function(){var e=jQuery(".ad-opt-anon"),t=jQuery("#ad-msg-enable:checked").length;jQuery(this).filter(":checked").length?e.filter(":visible").fadeOut(250):t?jQuery(".toggle-opts-ad-page").fadeIn(250):jQuery(".toggle-opts-ad-msg").fadeIn(250)})});CTXPS_Ajax.addBulkUsersToGroup=function(){var e=jQuery("#the-list input:checkbox:checked");jQuery.get("admin-ajax.php",{action:"ctxps_user_bulk_add",users:e.serializeArray(),group_id:jQuery("#psc_group_add").val()},function(t){t=jQuery(t);var n=jQuery("#message"),r=t.find("supplemental html").text();n.length?n.replaceWith(r):jQuery("#wpbody-content h2:first").after(r);if(t.find("bulk_enroll").attr("id")=="1"){e.removeAttr("checked").prop("checked",!1);jQuery("#cb-select-all-1,#cb-select-all-2").removeAttr("checked").prop("checked",!1)}},"xml")};CTXPS_Ajax.showSaveMsg=function(e){jQuery(e+" .ctx-ajax-status").length==0&&jQuery(e).append('<span class="ctx-ajax-status">Saved</span>').find(".ctx-ajax-status").fadeIn(500,function(){jQuery(this).delay(750).fadeOut(500,function(){jQuery(this).remove()})})};CTXPS_Ajax.toggleSecurity=function(){var e=parseInt(jQuery("#ctx_ps_post_id").val());jQuery("#ctx_ps_protectmy:checked").length!==0?jQuery.get("admin-ajax.php",{action:"ctxps_security_update",setting:"on",object_type:"post",object_id:e},function(e){e=jQuery(e);if(e.find("update_sec").attr("id")=="1"){jQuery("#ctx_ps_pagegroupoptions").show();CTXPS_Ajax.showSaveMsg("#ctxps-grouplist-box h3.hndle")}else alert(ctxpsmsg.GeneralError+e.find("wp_error").text())},"xml"):confirm(ctxpsmsg.EraseSec)?jQuery.get("admin-ajax.php",{action:"ctxps_security_update",setting:"off",object_type:"post",object_id:e},function(e){e=jQuery(e);if(e.find("update_sec").attr("id")=="1"){jQuery("#ctx_ps_pagegroupoptions").hide();CTXPS_Ajax.showSaveMsg("#ctxps-grouplist-box h3.hndle")}else alert(ctxpsmsg.GeneralError+e.find("wp_error").text())},"xml"):jQuery("#ctx_ps_protectmy").attr("checked","checked").prop("checked","checked")};CTXPS_Ajax.toggleContentSecurity=function(e,t,n){var r=jQuery("#ctxps-grouplist-ddl");typeof e=="undefined"&&alert("Programming Error: Type was undefined. Changes not saved.");typeof t=="undefined"&&(t=parseInt(jQuery("#ctxps-object-id").val()));jQuery("#ctxps-cb-protect:checked").length!==0?jQuery.get("admin-ajax.php",{action:"ctxps_security_update",setting:"on",object_type:e,object_id:t},function(t){t=jQuery(t);if(0!=t.find("update_sec").attr("id")){if(0!=t.find("supplemental html").length)switch(e){case"term":jQuery("#the-list-ctxps-relationships").replaceWith(t.find("supplemental html").text());break;default:}jQuery("#ctxps-relationships-list").show();typeof n!="undefined"&&CTXPS_Ajax.showSaveMsg(n)}else alert(ctxpsmsg.GeneralError+t.find("wp_error").text())},"xml"):confirm(ctxpsmsg.EraseSec)?jQuery.get("admin-ajax.php",{action:"ctxps_security_update",setting:"off",object_type:e,object_id:t},function(t){t=jQuery(t);if(t.find("update_sec").attr("id")=="1"){jQuery("#ctxps-relationships-list").hide();if(t.find("supplemental html").length>0)switch(e){case"term":jQuery("#the-list-ctxps-relationships").replaceWith(t.find("supplemental html").text());break;default:}r.length!=0;typeof save_loc!="undefined"&&CTXPS_Ajax.showSaveMsg(n);jQuery("#ctxps-grouplist-box #ctx-parentmsg").length>0&&jQuery("#publish").click()}else alert(ctxpsmsg.GeneralError+t.find("wp_error").text())},"xml"):jQuery("#ctxps-cb-protect").attr("checked","checked").prop("checked","checked")};CTXPS_Ajax.addGroupToUser=function(){var e=parseInt(jQuery("#ctxps-grouplist-ddl").val()),t=parseInt(jQuery("#ctx-group-user-id").val());if(e!=0){jQuery("#btn-add-grp-2-user").attr("disabled","disabled").prop("disabled","disabled");jQuery.get("admin-ajax.php",{action:"ctxps_add_group_to_user",group_id:e,user_id:t},function(t){t=jQuery(t);if(t.find("enroll").attr("id")=="1"){jQuery("#grouptable > tbody").html(t.find("supplemental html").text());var n=jQuery("#ctxps-grouplist-ddl");n.html(n.data("options")).children('option[value="'+e+'"]').addClass("detach").end().data("options",n.html()).children(".detach").remove();jQuery("#btn-add-grp-2-user").removeAttr("disabled").prop("disabled",!1);CTXPS_Ajax.showSaveMsg(".ctx-ps-tablenav")}else alert(ctxpsmsg.GeneralError+data.find("wp_error").text())},"xml")}else alert(ctxpsmsg.NoGroupSel)};CTXPS_Ajax.removeGroupFromUser=function(e,t,n,r){jQuery.get("admin-ajax.php",{action:"ctxps_remove_group_from_user",groupid:e,user_id:t},function(t){t=jQuery(t);if(t.find("unenroll").attr("id")=="1"){var r=jQuery("#ctxps-grouplist-ddl");r.html(r.data("options")).children('option[value="'+e+'"]').removeClass("detach").end().data("options",r.html()).children(".detach").remove();n.parents("tr:first").fadeOut(500,function(){n.parents("tbody:first").html(t.find("supplemental html").text())});CTXPS_Ajax.showSaveMsg(".ctx-ps-tablenav")}else alert(ctxpsmsg.GeneralError+t.find("wp_error").text())},"xml")};CTXPS_Ajax.addGroupToPage=function(){var e=parseInt(jQuery("#ctxps-grouplist-ddl").val()),t=parseInt(jQuery("#ctx_ps_post_id").val());e!=0?jQuery.get("admin-ajax.php",{action:"ctxps_add_group_to_post",group_id:e,post_id:t},function(t){t=jQuery(t);if(t.find("add_group").attr("id")=="1"){jQuery("#ctx-ps-page-group-list").html(t.find("supplemental html").text());var n=jQuery("#ctxps-grouplist-ddl");n.html(n.data("options")).children('option[value="'+e+'"]').addClass("detach").end().data("options",n.html()).children(".detach").remove();CTXPS_Ajax.showSaveMsg("#ctxps-grouplist-box h3.hndle")}},"xml"):alert(ctxpsmsg.NoGroupSel)};CTXPS_Ajax.addGroupToTerm=function(){var e=jQuery("#ctxps-grouplist-ddl").val(),t=jQuery('#edittag input[name="tag_ID"]').val(),n=jQuery('#edittag input[name="taxonomy"]').val(),r="#the-list-ctxps-relationships",i="#ctxps-grouplist-ddl";typeof e!="undefined"&&e!=0?jQuery.get("admin-ajax.php",{action:"ctxps_add_group_to_term",group_id:e,content_id:t,taxonomy:n},function(t){t=jQuery(t);if(t.find("add_group").attr("id")=="1"){typeof r!="undefined"&&jQuery(r).replaceWith(t.find("supplemental html").text());var n=jQuery(i);n.html(n.data("options")).children('option[value="'+e+'"]').addClass("detach").end().data("options",n.html()).children(".detach").remove();typeof savemsg_selector!="undefined"&&CTXPS_Ajax.showSaveMsg(savemsg_selector)}},"xml"):alert(ctxpsmsg.NoGroupSel)};CTXPS_Ajax.addGroupToContent=function(e,t,n,r,i,s){typeof e!="undefined"&&e!=0?jQuery.get("admin-ajax.php",{action:"ctxps_add_group_to_term",group_id:e,content_type:t,content_id:n},function(t){t=jQuery(t);if(t.find("add_group").attr("id")=="1"){typeof r!="undefined"&&jQuery(r).html(t.find("supplemental html").text());var n=jQuery(i);n.html(n.data("options")).children('option[value="'+e+'"]').addClass("detach").end().data("options",n.html()).children(".detach").remove();typeof s!="undefined"&&CTXPS_Ajax.showSaveMsg(s)}},"xml"):alert(ctxpsmsg.NoGroupSel)};CTXPS_Ajax.removeGroupFromPage=function(e,t){if(confirm(ctxpsmsg.RemoveGroup.replace(/%s/,t.parents(".ctx-ps-sidebar-group:first").children(".ctx-ps-sidebar-group-title").text()))){var n=parseInt(jQuery("#ctx_ps_post_id").val());jQuery.get("admin-ajax.php",{action:"ctxps_remove_group_from_page",group_id:e,post_id:n,requester:"sidebar"},function(n){n=jQuery(n);if(n.find("remove_group").attr("id")=="1"){var r=jQuery("#ctxps-grouplist-ddl");r.html(r.data("options")).children('option[value="'+e+'"]').removeClass("detach").end().data("options",r.html()).children(".detach").remove();t.parent().fadeOut(500,function(){console.log("Removed");jQuery("#ctx-ps-page-group-list").html(n.find("supplemental html").text())});CTXPS_Ajax.showSaveMsg("#ctxps-grouplist-box h3.hndle")}else alert(ctxpsmsg.GeneralError+n.find("wp_error").text())},"xml")}};CTXPS_Ajax.removeGroupFromTerm=function(e,t){var n=jQuery('#edittag input[name="tag_ID"]').val(),r=jQuery('#edittag input[name="taxonomy"]').val(),i=jQuery("#the-list-ctxps-relationships");confirm(ctxpsmsg.RemoveGroup.replace(/%s/,t.parents("tr:first").children("td.column-name a:first").text()))&&jQuery.get("admin-ajax.php",{action:"ctxps_remove_group_from_term",group_id:e,content_id:n,taxonomy:r},function(n){n=jQuery(n);if(n.find("remove_group").attr("id")=="1"){var r=jQuery("#ctxps-grouplist-ddl");r.html(r.data("options")).children('option[value="'+e+'"]').removeClass("detach").end().data("options",r.html()).children(".detach").remove();t.parents("tr:first").fadeOut(500,function(){i.replaceWith(n.find("supplemental html").text())})}else alert(ctxpsmsg.GeneralError+n.find("wp_error").text())},"xml")};CTXPS_Ajax.removePageFromGroup=function(e,t){if(confirm(ctxpsmsg.RemovePage.replace(/%s/,t.parents("td:first").find("strong>a:first").text()))){var n=parseInt(jQuery("#groupid").val());jQuery.get("admin-ajax.php",{action:"ctxps_remove_group_from_page",group_id:n,post_id:e},function(e){e=jQuery(e);e.find("remove_group").attr("id")=="1"?t.parents("tr:first").fadeOut(500,function(){jQuery(this).parents("tbody:first").html(e.find("supplemental html").text())}):alert(ctxpsmsg.GeneralError+e.find("wp_error").text())},"xml")}};