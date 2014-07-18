jQuery(document).ready(function(){
    
    jQuery("#eps_subtitle input.eps_subtitle").focus(function(){
                        jQuery("#eps_subtitle label").hide();
                    });
                    
    jQuery("#eps_subtitle input.eps_subtitle").blur(function(){
        if( jQuery("#eps_subtitle input.eps_subtitle").val().length < 1 )
            jQuery("#eps_subtitle label").show();
    });
    
});