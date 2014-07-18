<?php

/*
  Plugin Name: Easy post subtitle
  Plugin URI: http://www.greatwpplugins.com/easy-post-subtitle/
  Description: The plugin adds a visual imput in the post editing section for adding a subtitle for it.
  Version: 1.0
  Author: GreatWPPlugins
  Author URI: http://www.greatwpplugins.com/
  License: GPL2
 */
if (!function_exists('the_subtitle')):

    function eps_add_input()
    {
        global $post_ID, $post;
        if (!in_array($post->post_type, array('post'))) {
            return;
        }
        $eps_subtitle   = the_subtitle($post_ID);
        $eps_show_label = ( strlen(esc_attr($eps_subtitle)) ) ? ' style="display:none;"' : '';
        echo '<div><div id="eps_subtitle">
                <label' . $eps_show_label . '>' . __('Enter subtitle here') . '</label><input type="text" class="eps_subtitle" name="eps_subtitle" value="' . esc_attr($eps_subtitle) . '" />
            </div></div>
            <script>
                var html = jQuery("#eps_subtitle").parent("div").html();
                jQuery("#eps_subtitle").parent("div").remove();
                jQuery("#titlediv").after( html );
            </script>';
    }

    function eps_set_subtitle($post_id)
    {

        if (isset($post_id))
            $post = get_post($post_id);

        if (is_object($post) && !in_array($post->post_type, array('post'))) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        if (!isset($_POST['eps_subtitle']))
            $_POST['eps_subtitle'] = '';

        update_post_meta($post_id, CHP_PLUGIN_PREFIX . 'eps_subtitle', strip_tags(trim($_POST['eps_subtitle'])));
    }

    function the_subtitle($pid = null)
    {
        global $post;

        $postId   = is_null($pid) ? $post->ID : $pid;
        $metaData = get_post_meta($postId, CHP_PLUGIN_PREFIX . 'eps_subtitle', true);

        if (is_null($pid)) {
            echo $metaData;
        } else {
            return $metaData;
        }
    }

    function get_the_subtitle($pid = null)
    {
        global $post;

        $postId   = is_null($pid) ? $post->ID : $pid;
        $metaData = get_post_meta($postId, CHP_PLUGIN_PREFIX . 'eps_subtitle', true);

        return $metaData;
    }

    function eps_admin_css()
    {
        global $post;
        if (is_object($post) && !in_array($post->post_type, array('post'))) {
            return;
        }
        wp_enqueue_style('eps_style', CHP_PLUGIN_URL . '/extensions/easy-post-subtitle/include/style.css');
    }

    function eps_admin_scripts()
    {
        global $post;

        if (is_object($post) && !in_array($post->post_type, array('post'))) {
            return;
        }
        wp_enqueue_script('eps_scripts', CHP_PLUGIN_URL . '/extensions/easy-post-subtitle/include/scripts.js');
    }
    add_action('admin_print_scripts', 'eps_admin_scripts');
    add_action('admin_print_styles', 'eps_admin_css');

    add_action('dbx_post_sidebar', 'eps_add_input');
    add_action('save_post', 'eps_set_subtitle');

endif;