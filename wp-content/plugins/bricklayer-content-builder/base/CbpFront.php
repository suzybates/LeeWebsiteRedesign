<?php
if (!class_exists( 'CbpFront' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpFront
{

    public static function baseUrl()
    {
        return CBP_FRONT_PUBLIC_URI;
    }

    public static function addScript($script, $args = array())
    {
        global $post;
        $args = wp_parse_args($args, array(
            'template'     => false,
            'dependencies' => array(),
            'version'  => false,
            'inFooter' => true
                ));

        if ($args['template'] && is_array($args['template'])) {
            $pageTemplate = self::getTemplate($post->ID);

            if (in_array($pageTemplate, $args['template']) || (is_single() && in_array('single', $args['template']))) {
                new CbpScript('front', array($script), $args['dependencies'], $args['version'], $args['inFooter']);
            }
            return;
        }

        new CbpScript('front', array($script), $args['dependencies'], $args['version'], $args['inFooter']);
        //echo '<script src="' . FRONT_PUBLIC_URI . $dir . $path . '"></script>';
    }
    
    public static function addStylesheet($path, $args = array())
    {
        global $post;
        $args = wp_parse_args($args, array(
            'template'     => false,
            'dependencies' => array(),
            'version'      => false,
            'media'        => 'screen',
            'conditional'  => false,
                ));
        if ($args['template'] && is_array($args['template'])) {
            $pageTemplate = self::getTemplate($post->ID);

            if (in_array($pageTemplate, $args['template']) || (is_single() && in_array('single', $args['template']))) {
                new CbpStyle('front', array($path), $args['dependencies'], $args['version'], $args['media'], $args['conditional']);
            }
            return;
        }
        new CbpStyle('front', array($path), $args['dependencies'], $args['version'], $args['media'], $args['conditional']);
    }

//    public static function addStylesheet($path, $dir = 'css/', $condition = false)
//    {
//        if ($condition) {
//            echo '<!--[if ' . $condition . ']><link rel="stylesheet" href="' . FRONT_PUBLIC_URI . $dir . $path . '"><![endif]-->';
//        } else {
//
//            if ($path == 'default') {
//                echo '<link rel="stylesheet" href="' . get_bloginfo('stylesheet_url') . '">';
//            } else {
//                echo '<link rel="stylesheet" href="' . FRONT_PUBLIC_URI . $dir . $path . '">';
//            }
//        }
//    }

    public static function isXmlHttpRequest()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    public static function getTemplate($postId)
    {
        return get_post_meta($postId, '_wp_page_template', TRUE);
    }

    public static function getSidebars()
    {
        return $GLOBALS['wp_registered_sidebars'];
    }

    public static function getPageSidebar($postId, $type)
    {
        if ($type == 'glider-top') {
            return get_post_meta($postId, CBP_APP_PREFIX . 'glider-sidebar-top', true);
        }
        if ($type == 'glider-bottom') {
            return get_post_meta($postId, CBP_APP_PREFIX . 'glider-sidebar-bottom', true);
        }
        if ($type == 'bottom-widgets') {
            return get_post_meta($postId, CBP_APP_PREFIX . 'sidebar-bottom-widgets', true);
        }
        return false;
    }
}
endif;
