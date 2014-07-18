//////////////////////////////////////////////////////////////////
// Marked Text
//////////////////////////////////////////////////////////////////
(function() {  
    tinymce.create('tinymce.plugins.chp_marked_text', {  
        init : function(ed, url) {  
            ed.addButton('chp_marked_text', {  
                title : 'Add a Marked text',  
                image : url+'/icon-marker.png',  
                onclick : function() {  
                     ed.selection.setContent('[chp_marked_text type="borders" tag="h3"]Text goes here[/chp_marked_text]');  
  
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        } 
    });  
    tinymce.PluginManager.add('chp_marked_text', tinymce.plugins.chp_marked_text);  
})();

//////////////////////////////////////////////////////////////////
// Icon Bullet Text
//////////////////////////////////////////////////////////////////
(function() {  
    tinymce.create('tinymce.plugins.chp_icon_bullet_text', {  
        init : function(ed, url) {  
            ed.addButton('chp_icon_bullet_text', {  
                title : 'Add an Icon Bullet Text',  
                image : url+'/icon-list.png',  
                onclick : function() {  
                     ed.selection.setContent('[chp_icon_bullet_text icon="fa-clock-o" icon_size="1" title="The Title" title_tag="h3"  subtitle="The Subtitle" subtitle_tag="h4" description_tag="p"]Description goes here[/chp_icon_bullet_text]');  
  
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }  
    });  
    tinymce.PluginManager.add('chp_icon_bullet_text', tinymce.plugins.chp_icon_bullet_text);  
})();

