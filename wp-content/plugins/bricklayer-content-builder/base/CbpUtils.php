<?php

if (!class_exists('CbpUtils')):

    /**
     * @author Parmenides <krivinarius@gmail.com>
     */
    class CbpUtils
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
            if (function_exists('iconv')) {
                $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            }

            // lowercase
            $text = strtolower($text);

            // remove unwanted characters
            $text = preg_replace('~[^-\w]+~', '', $text);

            if (empty($text)) {
                return 'n-a';
            }

            return $text;
        }

        public static function spaceOutCamelCase($camel, $glue = '-')
        {
            return preg_replace('/([a-z0-9])([A-Z])/', "$1$glue$2", $camel);
        }

        public static function scanDir($directory, $extension = CBP_VIEW_FILE_TYPE)
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

        public static function trimmer($str, $length = 100, $link = null, $tail = '...', $wrap = null, $tags = false)
        {

            //start wrap if set
            $open = !is_null($wrap) && $tags == false ? $wrap : '';
            
            //check if we want tags or not
            $str = $tags == false ? strip_tags($str) : $str;
            
            //check length and add tail if needed.
            if (strlen($str) > $length) {

                //$str   = substr($str, 0 , $length);
                $str = preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $length));
                $str .= $tail;
            }//end if			

            //add link if set
            $str .=!is_null($link) ? '&nbsp;<a href="' . $link . '">Read more</a>' : '';

            //end wrap if set
            $close = !is_null($wrap) && $tags == false ? '</' . substr($wrap, 1) : '';

            return $open . $str . $close;
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
            return get_option(CBP_APP_PREFIX . $optionName);
        }

        public static function getMeta($postId, $metaName)
        {
            return get_post_meta($postId, CBP_APP_PREFIX . $metaName, true);
        }

        public static function translate($string)
        {
            return CbpTranslate::translateString($string);
        }

        public static function getImageSizes()
        {
            global $_wp_additional_image_sizes;

            $sizes = array();
            foreach (get_intermediate_image_sizes() as $s) {
                $sizes[$s] = array(0, 0);
                if (in_array($s, array('thumbnail', 'medium', 'large'))) {
                    $sizes[$s][0] = get_option($s . '_size_w');
                    $sizes[$s][1] = get_option($s . '_size_h');
                } else {
                    if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s]))
                        $sizes[$s] = array($_wp_additional_image_sizes[$s]['width'], $_wp_additional_image_sizes[$s]['height'],);
                }
            }
            return $sizes;
        }

        public static function getDownsizedImgUrl($imgUrl, $registeredImageSize)
        {
            if (is_string($registeredImageSize)) {
                $thumbSize  = '';
                $imageSizes = self::getImageSizes();
                if (isset($imageSizes[$registeredImageSize])) {
                    $thumbSize = '-' . implode('x', $imageSizes[$registeredImageSize]);
                }

                return preg_replace("/(.+)[\.]{1}(jpg|png|gif|jpeg){1}$/i", '$1' . $thumbSize . '.$2', $imgUrl);
            }

            return $imgUrl;
        }

        public static function getPostCategories($postId = 0)
        {
            $post_categories = array();
            
            if ((int) $postId) {
                $post_categories = wp_get_post_categories((int) $postId);
            } else {
                global $post;
                $post_categories = wp_get_post_categories($post->ID);
            }

            $cats = array();

            foreach ($post_categories as $c) {
                $cat    = get_category($c);
                $cats[] = array('name'   => $cat->name, 'slug'   => $cat->slug, 'link'   => get_term_link($cat), 'object' => $cat);
            }

            return $cats;
        }

        public static function getSetting($name)
        {
            global $cbp_settings;

            return isset($cbp_settings[$name]) ? $cbp_settings[$name] : null;
        }

        public static function getSettingAttribute($settingName, $attributeName)
        {
            $setting = self::getSetting($settingName);

            return $setting && isset($setting[$attributeName]) ? $setting[$attributeName] : '';
        }

        public static function pagination($pages = '', $range = 2)
        {
            $showitems = ($range * 2) + 1;

            global $paged;
            if (empty($paged))
                $paged = 1;

            if ($pages == '') {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                if (!$pages) {
                    $pages = 1;
                }
            }

            if (1 != $pages) {
                echo "<div class='pagination'>";
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
                    echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
                if ($paged > 1 && $showitems < $pages)
                    echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";

                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                        echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
                    }
                }

                if ($paged < $pages && $showitems < $pages)
                    echo "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
                    echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
                echo "</div>\n";
            }
        }

    }

    

    

    

    

    
endif;