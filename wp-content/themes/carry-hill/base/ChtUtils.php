<?php
if (!class_exists( 'ChtUtils' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtUtils
{

    public static function toLower($string)
    {
        return strtolower($string);
    }

    public static function slugify($text)
    {
 
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }
 
    return $text;
}

    public static function spaceOutCamelCase($camel, $glue = '-')
    {
        return preg_replace('/([a-z0-9])([A-Z])/', "$1$glue$2", $camel);
    }

    public static function scanDir($directory, $extension = CHT_VIEW_FILE_TYPE)
    {
        $fileNames = array_slice(scandir($directory), '2');
        $length    = strlen($extension);
        $result    = array();
        
        foreach ($fileNames as $fileName) {
            $result[] = substr($fileName, 0, -$length);
        }

        return $result;
    }
    
    public static function extractTabName($filename)
    {
        return ucwords(str_replace('-', ' ', substr($filename, 5, 15)));
    }
    
    public static function trimmer($str, $length=100, $link=null, $tail='...', $wrap=null, $tags=false)
    {	

            //start wrap if set
            $open = !is_null($wrap) && $tags == false  ? $wrap : '';

            //check length and add tail if needed.
            if(strlen($str) > $length)
            {				

                    //$str   = substr($str, 0 , $length);
                    $str   = preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $length));
                    $str   .= $tail;						

            }//end if			

            //check if we want tags or not
            $str = $tags == false ? strip_tags($str) : $str;

            //add link if set
            $str .= !is_null($link) ? '&nbsp;<a href="'.$link.'">Read more</a>' : '';

            //end wrap if set
            $close = !is_null($wrap) && $tags == false  ? '</'. substr($wrap, 1) : '';

            return $open.$str.$close;

    }
    
    public static function maybeSelected($condition)
    {
        return $condition ? 'selected="selected"' : '';
    }
    
    public static function maybeChecked($condition)
    {
        return $condition ? 'checked="checked"' : '';
    }
    
    public static function titleize($string)
    {
        return ucfirst(self::slugify($string));
    }
    
    public static function getOption($optionName)
    {
        return get_option(CHT_APP_PREFIX . $optionName);
    }
    
    public static function getMeta($postId, $metaName)
    {
        return get_post_meta($postId, CHT_APP_PREFIX . $metaName, true);
    }
    
    public static function translate($string)
    {
        return ChtTranslate::translateString($string);
    }
    
    public static function pagination($pages = '', $range = 2)
    {  
         $showitems = ($range * 2)+1;  

         global $paged;
         if(empty($paged)) $paged = 1;

         if($pages == '')
         {
             global $wp_query;
             $pages = $wp_query->max_num_pages;
             if(!$pages)
             {
                 $pages = 1;
             }
         }   

         if(1 != $pages)
         {
             echo "<div class='pagination'>";
             if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
             if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

             for ($i=1; $i <= $pages; $i++)
             {
                 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                 {
                     echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
                 }
             }

             if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
             if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
             echo "</div>\n";
         }
    }
    
}
endif;
