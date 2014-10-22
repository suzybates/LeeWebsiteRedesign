<?php

require_once (TEMPLATEPATH . '/init/config.php');
require_once (TEMPLATEPATH . '/activate-plugins.php');

if (!class_exists('CbpWidgets')) {

    function cht_enqueue_cbp_style()
    {
        wp_enqueue_style('bricklayer.carry-hill', get_template_directory_uri() . '/public/css/content-builder.css', false);
        wp_enqueue_style('bricklayer.groundwork', get_template_directory_uri() . '/public/css/groundwork-responsive.css', false);
    }
    add_action('wp_enqueue_scripts', 'cht_enqueue_cbp_style');

    function cht_enqueue_cbp_script()
    {
        wp_enqueue_script('cbp-plugins', get_template_directory_uri() . '/public/js/cbp-plugins.js', array('jquery', 'jquery-ui-accordion'), false, true);
        wp_enqueue_script('cbp-main', get_template_directory_uri() . '/public/js/cbp-main.js', array('jquery'), false, true);
    }
    add_action('wp_enqueue_scripts', 'cht_enqueue_cbp_script');

    add_action('template_redirect', 'cht_page_template_redirect');

    function cht_page_template_redirect()
    {
        if (is_page_template('page-home.php')
                || is_page_template('page-home-boxed.php')
                || is_page_template('page-fullwidth.php')
                || is_page_template('page-fullwidth-no-wrap.php')
                || is_page_template('page-boxed.php')
                || is_page_template('page-boxed-sidebar.php')
                || is_page_template('page-portfolio.php')
        ) {
            include_once 'page.php';
            exit;
        }
    }
}

function cht_enqueue_style()
{
    wp_enqueue_style('style.default', get_stylesheet_uri(), false);
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/public/css/font-awesome.min.css', false);
}

function cht_enqueue_script()
{
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/public/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js', array('jquery'));
    wp_enqueue_script('ch-plugins', get_template_directory_uri() . '/public/js/ch-plugins.js', array('jquery'), false, true);
    wp_enqueue_script('ch-main', get_template_directory_uri() . '/public/js/ch-main.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'cht_enqueue_style', '999');
add_action('wp_enqueue_scripts', 'cht_enqueue_script');

function cht_new_excerpt_more($more)
{
    global $post;
    return ' <a href="' . get_permalink($post->ID) . '">' . '[...]' . '</a>';
}
add_filter('excerpt_more', 'cht_new_excerpt_more');
