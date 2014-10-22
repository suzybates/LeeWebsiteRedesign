<?php

/**
 * Plugin Name: Bricklayer Content Builder
 * Plugin URI:  http://codecanyon.net/item/bricklayer-content-builder-wp-plugin/6834212
 * Description: Bricklayer is a shortcode based, content builder that easily allows building complex layouts brick by brick. 
 * Version:     1.4
 * Author:      Parmenides
 * Author URI:  http://codecanyon.net/user/parmenides
 * License:     GPLv2+
 * Text Domain: bricklayer
 * Domain Path: /languages
 */
define('CBP_PLUGIN_VERSION', '1.4');
define('CBP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CBP_PLUGIN_PATH', dirname(__FILE__) . '/');
define('CBP_PLUGIN_SLUG', basename(dirname(__FILE__)));

if (!function_exists('array_replace_recursive'))
{
    function array_replace_recursive($base, $replacements) 
    { 
        foreach (array_slice(func_get_args(), 1) as $replacements) { 
            $bref_stack = array(&$base); 
            $head_stack = array($replacements); 

            do { 
                end($bref_stack); 

                $bref = &$bref_stack[key($bref_stack)]; 
                $head = array_pop($head_stack); 

                unset($bref_stack[key($bref_stack)]); 

                foreach (array_keys($head) as $key) { 
                    if (isset($key, $bref) && is_array($bref[$key]) && is_array($head[$key])) { 
                        $bref_stack[] = &$bref[$key]; 
                        $head_stack[] = $head[$key]; 
                    } else { 
                        $bref[$key] = $head[$key]; 
                    } 
                } 
            } while(count($head_stack)); 
        } 

        return $base; 
    } 
} 

$cbp_settings = array();

require_once 'init/config.php';
// Wireup actions
add_action('init', 'cbp_init', 999);

add_action('template_redirect', 'cbp_page_template_redirect');

add_action('wp_head', 'cbpCustomLayerStyles');




$cbpContentTemplateLinks = CbpUtils::getOption('template_links');
if ($cbpContentTemplateLinks) {

    add_filter('cbp_filter_use_content_builder_layout', 'cbp_use_content_builder_layout', 1); // set low priority so it can be overriden in other plugins or theme
    add_filter('cbp_filter_layout', 'cbp_register_layouts', 1); // set low priority so it can be overriden in other plugins or theme
}

add_filter('image_size_names_choose', 'cbp_insert_custom_image_sizes');

/**
 * Default initialization for the plugin:
 */
function cbp_init()
{
    global $content_width;
    if (isset($content_width) && CbpUtils::getOption('use_content_builder_layouts_and_templates')) {
        $content_width = (int) CbpUtils::getOption('global_container_width');
    }

    add_theme_support('post-thumbnails');
    cbp_register_custom_thumbnail_sizes();
    

//    add_action('edit_form_after_title', 'cbp_add_bricklayer_switcher_button', 20);
}

function cbp_insert_custom_image_sizes($sizes)
{
    global $_wp_additional_image_sizes;
    if (empty($_wp_additional_image_sizes))
        return $sizes;

    foreach ($_wp_additional_image_sizes as $id => $data) {
        if (!isset($sizes[$id]))
            $sizes[$id] = ucfirst(str_replace('-', ' ', $id));
    }

    return $sizes;
}

/**
 * Activate the plugin
 */
function cbp_activate()
{
    // First load the init scripts in case any rewrite functionality is being loaded
    cbp_init();

    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'cbp_activate');

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function cbp_deactivate()
{
    
}

register_deactivation_hook(__FILE__, 'cbp_deactivate');

function cbp_page_template_redirect()
{
    global $post, $cbp_settings;

    $layout                                   = null;
    $useLayoutGlobalSettingsOverride          = CbpUtils::getOption('use_layout_global_settings_override');
    $globalUseLayoutsAndTemplates             = CbpUtils::getOption('use_content_builder_layouts_and_templates');
    $themeOverride                            = CbpUtils::getOption('global_theme_override');
    $backgroundImage                          = CbpUtils::getOption('global_background_image');
    $cbp_settings['container_width']          = CbpUtils::getOption('global_container_width');
    $cbp_settings['container_padding_top']    = CbpUtils::getOption('global_container_padding_top');
    $cbp_settings['container_padding_right']  = CbpUtils::getOption('global_container_padding_right');
    $cbp_settings['container_padding_bottom'] = CbpUtils::getOption('global_container_padding_bottom');
    $cbp_settings['container_padding_left']   = CbpUtils::getOption('global_container_padding_left');
    $cbp_settings['body_background_color']    = CbpUtils::getOption('global_use_background_color') ? CbpUtils::getOption('global_background_color') : false;

    $useLayout = false;
    if (is_page()) {
        $useLayout = (bool) CbpUtils::getMeta($post->ID, 'use_layout');
    }

    if ($useLayout = apply_filters('cbp_filter_use_content_builder_layout', $useLayout) && $globalUseLayoutsAndTemplates) {

        $layoutId = 0;
        if (is_page()) {
            $layoutId = CbpUtils::getMeta($post->ID, 'layout');
        }

        $layoutId = apply_filters('cbp_filter_layout', $layoutId);

        if ($layoutId) {
            $layout = get_post($layoutId);

            if ($useLayoutGlobalSettingsOverride) {
                // specific layout settings
                // theme override
                $layoutOverrideGlobalThemeOverride = (int) CbpUtils::getMeta($layoutId, 'layout_override_global_theme_override');
                if ($layoutOverrideGlobalThemeOverride) {
                    $themeOverride = (int) CbpUtils::getMeta($layoutId, 'layout_theme_override');
                }

                // container width
                $layoutOverrideGlobalContainerWidth = (int) CbpUtils::getMeta($layoutId, 'layout_override_global_container_width');
                if ($layoutOverrideGlobalContainerWidth) {
                    $cbp_settings['container_width'] = (int) CbpUtils::getMeta($layoutId, 'layout_container_width');
                }

                // container padding
                $layoutOverrideGlobalContainerPadding = (int) CbpUtils::getMeta($layoutId, 'layout_override_global_container_padding');
                if ($layoutOverrideGlobalContainerPadding) {
                    $cbp_settings['container_padding_top']    = (int) CbpUtils::getMeta($layoutId, 'layout_container_padding_top');
                    $cbp_settings['container_padding_right']  = (int) CbpUtils::getMeta($layoutId, 'layout_container_padding_right');
                    $cbp_settings['container_padding_bottom'] = (int) CbpUtils::getMeta($layoutId, 'layout_container_padding_bottom');
                    $cbp_settings['container_padding_left']   = (int) CbpUtils::getMeta($layoutId, 'layout_container_padding_left');
                }

                // body background color
                $layoutOverrideGlobalBodyBgColor = (int) CbpUtils::getMeta($layoutId, 'layout_override_global_body_background_color');
                if ($layoutOverrideGlobalBodyBgColor) {
                    $cbp_settings['body_background_color'] = CbpUtils::getMeta($layoutId, 'layout_override_global_use_body_background_color') ? CbpUtils::getMeta($layoutId, 'layout_body_background_color') : false;
                }

                // body background image
                $layoutOverrideGlobalBodyBgImg = (int) CbpUtils::getMeta($layoutId, 'layout_override_global_body_background_image');
                if ($layoutOverrideGlobalBodyBgImg) {
                    $backgroundImage = CbpUtils::getMeta($layoutId, 'layout_body_background_image');
                }
            }

            include_once 'content-builder-template.php';
            exit();
        }
    }
}

function cbpCustomLayerStyles()
{
    global $cbp_settings;
    $out = '<style>';
    if (isset($cbp_settings['body_background_color']) && $cbp_settings['body_background_color']) {
        $out .= 'body {background-color: ' . $cbp_settings['body_background_color'] . ' !important;}';
    }
    if (isset($cbp_settings['container_width']) && $cbp_settings['container_width']) {

        $out .= 'body > .cbp-container {';
        $out .= 'max-width: ' . $cbp_settings['container_width'] . 'px;';

        if (isset($cbp_settings['container_padding_top']) && $cbp_settings['container_padding_top']) {
            $out .= 'padding-top: ' . $cbp_settings['container_padding_top'] . 'px;';
        }
        if (isset($cbp_settings['container_padding_right']) && $cbp_settings['container_padding_right']) {
            $out .= 'padding-right: ' . $cbp_settings['container_padding_right'] . 'px;';
        }
        if (isset($cbp_settings['container_padding_bottom']) && $cbp_settings['container_padding_bottom']) {
            $out .= 'padding-bottom: ' . $cbp_settings['container_padding_bottom'] . 'px;';
        }
        if (isset($cbp_settings['container_padding_left']) && $cbp_settings['container_padding_left']) {
            $out .= 'padding-left: ' . $cbp_settings['container_padding_left'] . 'px;';
        }
        $out .= '}';
    }
    $out .= '</style>';
    echo $out;
}

function cbp_load_scripts()
{
    $loadFontAwesome = CbpUtils::getOption('global_load_font_awesome');

    $styles = array('css/groundwork-responsive.css', 'css/content-builder.css');
    if ($loadFontAwesome) {
        $styles[] = 'css/font-awesome.min.css';
    }

    new CbpStyle('front', $styles);
    new CbpScript('front', array('js/cbp-plugins.min.js', 'js/cbp-main.js'), array('jquery', 'jquery-ui-accordion'));
}

cbp_load_scripts();

function cbp_use_content_builder_layout($useContentBuilder)
{
    global $cbpContentTemplateLinks, $post;

    $taxonomies = get_object_taxonomies($post);
    $terms      = get_terms($taxonomies);
    $termsIds   = array();

    foreach ($terms as $term) {
        $termsIds[] = $term->term_id;
    }

    foreach ($cbpContentTemplateLinks as $contentTemplateLink) {

        $linkContentTemplate = isset($contentTemplateLink['content_template']) ? $contentTemplateLink['content_template'] : false;
        $linkPostType        = isset($contentTemplateLink['post_type']) ? $contentTemplateLink['post_type'] : false;
        $linkTerm            = isset($contentTemplateLink['term']) ? $contentTemplateLink['term'] : false;

        if ($linkContentTemplate == '404' && is_404()) {
            $useContentBuilder = true;
            break;
        }

        if ($linkContentTemplate == 'archive' && is_archive()) {
            $useContentBuilder = true;
            break;
        }

        if ($linkPostType == get_post_type() && $linkContentTemplate == 'single' && is_single()) {
            $useContentBuilder = true;
            break;
        }

        if ($linkPostType == get_post_type() && $linkContentTemplate == 'category' && is_category()) {
            $useContentBuilder = true;
            break;
        }
        if ($linkPostType == get_post_type() && $linkContentTemplate == 'tag' && is_tax()) {
            $useContentBuilder = true;
            break;
        }
    }

    return $useContentBuilder;
}

function cbp_register_layouts($layoutId)
{
    global $cbpContentTemplateLinks, $post;

    $taxonomies = get_object_taxonomies($post);
    $terms      = wp_get_object_terms($post->ID, $taxonomies);
    $termsIds   = array();

    foreach ($terms as $term) {
        $termsIds[] = $term->term_id;
    }

    foreach ($cbpContentTemplateLinks as $contentTemplateLink) {

        $linkContentTemplate = isset($contentTemplateLink['content_template']) ? $contentTemplateLink['content_template'] : false;
        $linkPostType        = isset($contentTemplateLink['post_type']) ? $contentTemplateLink['post_type'] : false;
        $linkTerm            = isset($contentTemplateLink['term']) ? $contentTemplateLink['term'] : false;
        $linkLayout          = isset($contentTemplateLink['layout']) ? $contentTemplateLink['layout'] : false;


        if ($linkContentTemplate == '404' && is_404()) {
            $layoutId = $linkLayout;
            break;
        }

        if ($linkContentTemplate == 'archive' && is_archive()) {
            $layoutId = $linkLayout;
            break;
        }

        if ($linkPostType == get_post_type() && $linkContentTemplate == 'single' && is_single()) {
            $layoutId = $linkLayout;

            // filter term
            foreach ($cbpContentTemplateLinks as $contentTemplateLinkSingle) {

                $linkSingleTerm   = isset($contentTemplateLinkSingle['term']) ? $contentTemplateLinkSingle['term'] : false;
                $linkSingleLayout = isset($contentTemplateLinkSingle['layout']) ? $contentTemplateLinkSingle['layout'] : false;

                if ($linkSingleTerm && in_array($linkSingleTerm, $termsIds)) {
                    if ($linkSingleLayout) {
                        $layoutId = $linkSingleLayout;
                    }
                    break;
                }
            }
            break;
        }

        if ($linkPostType == get_post_type() && $linkContentTemplate == 'category' && is_category()) {
            $layoutId = $linkLayout;

            // filter term
            foreach ($cbpContentTemplateLinks as $contentTemplateLinkCategory) {

                $linkCategoryTerm   = isset($contentTemplateLinkCategory['term']) ? $contentTemplateLinkCategory['term'] : false;
                $linkCategoryLayout = isset($contentTemplateLinkCategory['layout']) ? $contentTemplateLinkCategory['layout'] : false;

                if ($linkCategoryTerm && in_array($linkCategoryTerm, $termsIds)) {
                    if ($linkCategoryLayout) {
                        $layoutId = $linkCategoryLayout;
                    }
                    break;
                }
            }
            break;
        }

        if ($linkPostType == get_post_type() && $linkContentTemplate == 'tags' && is_tax()) {
            $layoutId = $linkLayout;
            break;
        }
    }


    return $layoutId;
}

function cbp_register_custom_thumbnail_sizes()
{
    $string = CbpUtils::getOption('custom_thumbnail_sizes');

    $pattern     = '/[^a-zA-Z0-9\-\|\:]/';
    $replacement = '';
    $string      = preg_replace($pattern, $replacement, $string);

    $resArr = explode('|', $string);
    $thumbs = array();

    foreach ($resArr as $thumbString) {
        if (!empty($thumbString)) {
            $parts             = explode(':', trim($thumbString));
            $thumbs[$parts[0]] = explode('x', $parts[1]);
        }
    }

    foreach ($thumbs as $name => $sizes) {
        add_image_size($name, (int) $sizes[0], (int) $sizes[1], true);
    }
}

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */

function cbp_duplicate_post_as_draft()
{
    global $wpdb;
    if (!( isset($_GET['post']) || isset($_POST['post']) || ( isset($_REQUEST['action']) && 'cbp_duplicate_post_as_draft' == $_REQUEST['action'] ) )) {
        wp_die('No post to duplicate has been supplied!');
    }

    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    /*
     * and all the original post data then
     */
    $post    = get_post($post_id);

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if (isset($post) && $post != null) {

        /*
         * new post data array
         */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title . ' (Copy)',
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post($args);

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        /*
         * duplicate all post meta
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key        = $meta_info->meta_key;
                $meta_value      = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }


        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}

add_action('admin_action_cbp_duplicate_post_as_draft', 'cbp_duplicate_post_as_draft');

/*
 * Add the duplicate link to action list for post_row_actions
 */

function cbp_duplicate_post_link($actions, $post)
{
    if (($post->post_type == "layouts") or ($post->post_type == "templates")) {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="admin.php?action=cbp_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
        }
    }
    return $actions;
}

add_filter('post_row_actions', 'cbp_duplicate_post_link', 10, 2);

function cbp_add_bricklayer_switcher_button()
{
    echo '<div id="cbp-bricklayer-switcher"></div>';
}
