<?php

/**
 * Plugin Name: Carry Hill
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Carry Hill helper plugin
 * Version:     1.3.2
 * Author:      Aislin Themes
 * Author URI:  http://themeforest.net/user/Aislin/portfolio
 * License:     GPLv2+
 * Text Domain: chp
 * Domain Path: /languages
 */

define('CHP_PLUGIN_VERSION', '1.3.2');
define('CHP_PLUGIN_NAME', 'Carry Hill');
define('CHP_PLUGIN_PREFIX', 'chp_');
define('CHP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CHP_PLUGIN_PATH', dirname(__FILE__) . '/');
define('CHP_TEXT_DOMAIN', dirname(__FILE__) . '/');

/**
 * Register cbp-widget action
 */
function chp_register_widgets()
{
    require_once 'widgets/ChpWidgetPageTitle.php';
    require_once 'widgets/ChpWidgetLayerSlider.php';
    require_once 'widgets/ChpWidgetFeatureBox.php';
    require_once 'widgets/ChpWidgetLogo.php';
    require_once 'widgets/ChpWidgetTeamMemeber.php';
//    require_once 'widgets/ChpWidgetPortfolioArchive.php';

    CbpWidgets::unregisterWidget('CbpWidgetSinglePost');
    CbpWidgets::unregisterWidget('CbpWidgetPostList');
    CbpWidgets::unregisterWidget('CbpWidgetContent');
    CbpWidgets::unregisterWidget('CbpWidgetPost');
    CbpWidgets::unregisterWidget('CbpWidgetPageTitle');

    require_once 'widgets/ChpWidgetSinglePost.php';
    require_once 'widgets/ChpWidgetPostList.php';
    require_once 'widgets/ChpWidgetContent.php';
//    require_once 'widgets/ChpWidgetPost.php';
    
    
}

add_action('register_cbp_widget', 'chp_register_widgets');
add_action('widgets_init', 'register_wp_widgets');


function register_wp_widgets()
{
    require_once 'wp-widgets/ChpLatestPostsWidget.php';
    
}

add_filter('cbp_filter_disallow', 'chp_disallow_to_widget_panel');

function chp_disallow_to_widget_panel($disallowedWidgets)
{
    $postType = get_post_type();
    if ($postType == 'layouts' || $postType == 'templates') {
        
    } else {
        $disallowedWidgets[] = 'chp_widget_content';
    }

    return $disallowedWidgets;
}

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function chp_init()
{
    chp_add_extensions();
}

/**
 * Activate the plugin
 */
function chp_activate()
{
// First load the init scripts in case any rewrite functionality is being loaded
    chp_init();

    update_option('cbp_global_container_width', '980');
    update_option('cbp_use_scroll_to_top', '1');
    update_option('cbp_global_container_padding_right', '20');
    update_option('cbp_global_container_padding_left', '20');
    update_option('global_load_font_awesome', false);
    
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'chp_activate');

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function chp_deactivate()
{
    
}

register_deactivation_hook(__FILE__, 'chp_deactivate');

// Wireup actions
add_action('plugins_loaded', 'chp_init');

function chp_add_extensions()
{
    require_once 'extensions/brankic-photostream-widget/bra_photostream_widget.php';
    require_once 'extensions/portfolio-post-type/portfolio-post-type.php';
    require_once 'extensions/easy-post-subtitle/easy-post-subtitle.php';
}

require_once 'carry-hill-shortcodes.php';

add_filter('pre_get_posts', 'chp_portfolio_posts');

function chp_portfolio_posts($query)
{
    if (is_admin() || !$query->is_main_query())
        return;

    if (is_tax() && isset($query->tax_query) && $query->tax_query->queries[0]['taxonomy'] == 'portfolio_category') {
        $query->set('posts_per_page', 10);
        return;
    }
}
