(function ($) {
    
    var CbpCodeMirror = {

        init : function(settings) {

            var defaults = {
                mode: 'css',
                theme: 'ambiance',
                lineNumbers: true,
                autofocus: true,
                indentUnit: 4,
                indentWithTabs: true,
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                styleActiveLine: true,
                extraKeys: {
                    "Ctrl-Q": function(cm) {
                        cm.foldCode(cm.getCursor());
                    }
                }
            }

            if(typeof settings !== "undefined") {
                $.extend(defaults, settings);
            }

            $('.cbp-codemirror').each(function() {
                CodeMirror.fromTextArea(this, defaults);
            });
        }
    };
    CbpCodeMirror.init();
    
    $('select[name="skin"]').change(function() {
        document.location.href = 'admin.php?page=cbp-style-editor&skin=' + $(this).children(':selected').val();
    });    
})(jQuery);