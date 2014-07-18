(function ($) {
    
    var prefix = 'cbp';
    
    
    $('#' + prefix + '-options-reset').on('click', function (e) {
        
        e.preventDefault();
        
        
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cbpMenuPageAjax',
                reset: true
            },
            beforeSend: function() {
                $('body').append('<div id="cbp-form-loader"></div>');
            }
        }).done(function(response) {
            $('#cbp-form-loader').remove();
            alert('Settings Saved');
            window.location.reload(true);
            if (response) {
            }
            
        });
        
        
        
    });
    
    $('#' + prefix + '-options-form').on('submit', function(e){
        e.preventDefault();
            
//        var serializedForm = $(this).serializeArray();
        var serializedForm = $(this).serialize();
        
          
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cbpMenuPageAjax',
                form: serializedForm
            },
            beforeSend: function() {
                $('body').append('<div id="cbp-form-loader"></div>');
            }
        }).done(function(response) {
            $('#cbp-form-loader').remove();
            alert('Settings Saved');
            if (response) {
            }
            
        });
    });
    

    var $formfield = null;
    var $imgContainer;

    $('.upload-button').on('click', function(e) {
        e.preventDefault();

        var $parent = $(this).parent();

        $formfield    = $('.upload-field',$parent);
        $imgContainer = $('.image-container',$parent);

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    });                                   

    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html) {
        var imgurl;

        if ($formfield) {
            imgurl = $('img',html).attr('src');
            $formfield.val(imgurl);
            $imgContainer.html(html);
            tb_remove();
        }
        else {
            window.original_send_to_editor(html);
        }
        $formfield = null;
    }
    
    $(".spectrum-color").spectrum({
        preferredFormat: "hex",
//        color: "#f2f2f2",
        showPalette: true,
        showInput: true,
        showInitial: true,
        palette: [
        ["#f2f2f2","#333333", "#555555"]
        ]
    });
    
})(jQuery);

