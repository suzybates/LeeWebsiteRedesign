<?php

require_once 'globals.php';
require_once 'autoloader.php';
require_once 'ChtBootstrap.php';

$bootstrap = new ChtBootstrap();

function ch_comment($comment, $args, $depth)
{
    global $bootstrap;
    $bootstrap->getComments()->renderComments($comment, $args, $depth);
}
add_filter('comment_form_defaults', 'ch_filter_comment_form');

function ch_filter_comment_form($fields)
{
    $fields['comment_notes_after'] = '';

    return $fields;
}
add_filter('cbp_register_custom_css_classes', 'cht_register_custom_css_classes');

/**
 * Register custom css classes for widgets
 */
function cht_register_custom_css_classes($widgetType)
{
    $options = null;

    if ($widgetType == 'cbp_widget_row') {
        $options = array(
            ''                                                                   => 'No styling',
            'ch-header'                                                          => 'Header',
            'ch-header ch-header-inner'                                          => 'Header Inner',
            'ch-content'                                                         => 'Content',
            'ch-content ch-content-top'                                          => 'Content Top',
            'ch-content ch-content-top ch-content-top-rainbow'                   => 'Content Top - Rainbow',
            'ch-content ch-content-bottom'                                       => 'Content Bottom',
            'ch-content ch-content-bottom double-padded'                         => 'Events Content Bottom',
            'ch-content ch-content-top ch-content-bottom'                        => 'Content Top and Bottom',
            'ch-content ch-content-top ch-content-bottom ch-content-top-rainbow' => 'Content Top and Bottom - Rainbow',
            'ch-middle-bar'                                                      => 'Middle Bar',
            'ch-footer-top'                                                      => 'Footer Top',
            'ch-footer-bottom'                                                   => 'Footer Bottom',
            'ch-slider'                                                          => 'Main Slider'
        );
    }

    if ($widgetType == 'cbp_widget_slider') {
        $options = array(
            ''                           => 'No styling',
            'ch-quote-testimonials'      => 'Testimonials',
            'ch-quote-middle-bar-quotes' => 'Middle Bar Quotes'
        );
    }

    if ($widgetType == 'cbp_widget_menu') {
        $options = array(
            ''               => 'Default styling',
            'ch-menu-main'   => 'Main menu',
            'ch-menu-footer' => 'Footer menu'
        );
    }

    if ($widgetType == 'cbp_widget_icon') {
        $options = array(
            ''                               => 'no styling',
            'ch-icon-circle'                 => 'Inside of a circle',
            'ch-icon-circle ch-move-up-10'   => 'Inside of a circle - move up 10px',
            'ch-icon-circle ch-move-up-20'   => 'Inside of a circle - move up 20px',
            'ch-icon-circle ch-move-up-30'   => 'Inside of a circle - move up 30px',
            'ch-icon-circle ch-move-down-10' => 'Inside of a circle - move down 10px',
            'ch-icon-circle ch-move-down-20' => 'Inside of a circle - move down 20px',
            'ch-icon-circle ch-move-down-30' => 'Inside of a circle - move down 30px',
        );
    }

    if ($widgetType == 'cbp_widget_text') {
        $options = array(
            ''                    => 'no styling',
            'ch-close-paragraphs' => 'Close paragraphs',
            'ch-footer-copyright' => 'Footer copyright',
        );
    }
    
    if ($widgetType == 'chp_widget_feature_box') {
        $options = array(
            ''                           => 'Default Styling',
            'ch-featured-box-icon-small' => 'Small Icon',
        );
    }
    
    if ($widgetType == 'chp_widget_post_list') {
        $options = array(
            ''                                         => 'Default Styling',
            'ch-post-list-featured-image-left-compact' => 'Compact (Only for Widget Grid: Featured Image to Left)',
        );
    }

    return $options;
}

function cht_get_theme_partial_content($filename)
{
    ob_start();

    require_once CHT_THEME_ROOT . '/partials/' . $filename;

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
if (!function_exists('ch_entry_meta')) :

    /**
     * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
     *
     * @return void
     */
    function ch_entry_meta()
    {
        if (is_sticky() && is_home() && !is_paged())
            echo '<span class="featured-post">' . __('Sticky', CHT_APP_TEXT_DOMAIN) . '</span>';

        if (!has_post_format('link') && 'post' == get_post_type())
            ch_entry_date();

        // Translators: used between list items, there is a space after the comma.
        $categories_list = get_the_category_list(__(', ', CHT_APP_TEXT_DOMAIN));
        if ($categories_list) {
            echo '/<span class="categories-links">' . $categories_list . '</span>';
        }

        // Translators: used between list items, there is a space after the comma.
        $tag_list = get_the_tag_list('', __(', ', CHT_APP_TEXT_DOMAIN));
        if ($tag_list) {
            echo '/<span class="tags-links">' . $tag_list . '</span>';
        }

        // Post author
        if ('post' == get_post_type()) {
            printf('/<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', CHT_APP_TEXT_DOMAIN), get_the_author())), get_the_author()
            );
        }
    }
endif;

if (!function_exists('ch_entry_date')) :

    /**
     * Prints HTML with date information for current post.
     *
     * @param boolean $echo Whether to echo the date. Default true.
     * @return string The HTML-formatted post date.
     */
    function ch_entry_date($echo = true)
    {
        if (has_post_format(array('chat', 'status')))
            $format_prefix = _x('%1$s on %2$s', '1: post format name. 2: date', CHT_APP_TEXT_DOMAIN);
        else
            $format_prefix = '%2$s';

        $date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url(get_permalink()), esc_attr(sprintf(__('Permalink to %s', CHT_APP_TEXT_DOMAIN), the_title_attribute('echo=0'))), esc_attr(get_the_date('c')), esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()), get_the_date()))
        );

        if ($echo)
            echo $date;

        return $date;
    }



endif;

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function ch_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', CHT_APP_TEXT_DOMAIN ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'ch_wp_title', 10, 2 );

//function ch_mime_types( $mimes ){
//	$mimes['svg'] = 'image/svg+xml';
//	return $mimes;
//}
//add_filter( 'upload_mimes', 'ch_mime_types' );
