<?php
if (!class_exists( 'CbpContentBuilder' )) :
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpContentBuilder
{
    public static function render($type = 'page_builder')
    {
        
        $disallowedWidgets = array(
            CbpWidgets::getPrefix() .'_widget_row', 
            CbpWidgets::getPrefix() .'_widget_box'            
        );
        
        $disallowedWidgets = apply_filters('cbp_filter_disallow', $disallowedWidgets);
        
        if ($type == 'page_builder') {
            $disallowedWidgets[] = CbpWidgets::getPrefix() .'_widget_content';
            $disallowedWidgets[] = CbpWidgets::getPrefix() .'_widget_post';
        }
        
        include CBP_VIEWS_DIR . 'content-builder.phtml';
    }
    
    private static function translate($string) {
        return CbpTranslate::translateString($string);
    }
}
endif;