<?php

add_filter("the_content", "the_content_filter");

function the_content_filter($content)
{
    // array of custom shortcodes requiring the fix
    $block = join('|', array('chp_marked_text', 'chp_icon_bullet_text'));

    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);

    return $rep;
}

add_filter('mce_external_plugins', 'chpAddTinyMcePlugins');
add_filter('mce_buttons_3', 'chpRegisterTinyMceButtons');

function chpAddTinyMcePlugins($plugin_array)
{
    $plugin_array['chp_marked_text']      = CHP_PLUGIN_URL . '/public/js/tinymce/customcodes.js';
    $plugin_array['chp_icon_bullet_text'] = CHP_PLUGIN_URL . '/public/js/tinymce/customcodes.js';

    return $plugin_array;
}

function chpRegisterTinyMceButtons($buttons)
{
    array_push($buttons, 'chp_marked_text', 'chp_icon_bullet_text');
    return $buttons;
}

add_shortcode('chp_marked_text', 'chp_marked_text_shortcode');

function chp_marked_text_shortcode($atts, $content = null)
{
    extract(shortcode_atts(array(
                'type' => 'borders',
                'tag'  => 'h3',
                    ), $atts));
    $out   = '';

    $tagOpen  = !empty($tag) ? '<' . $tag . '>' : '';
    $tagClose = !empty($tag) ? '</' . $tag . '>' : '';

    $class = '';
    if ($type == 'borders') {
        $class = 'ch-border-title';
        $out   = '<div class="ch-shortcode ' . $class . '">' . $tagOpen . html_entity_decode($content) . $tagClose . '</div>';
    }
    if ($type == 'cite') {
        $class = 'ch-cite';
        $out   = '<cite class="ch-shortcode ' . $class . '">' . $tagOpen . html_entity_decode($content) . $tagClose . '</cite>';
    }

    return $out;
}

add_shortcode('chp_icon_bullet_text', 'chp_icon_bullet_text_shortcode');

function chp_icon_bullet_text_shortcode($atts, $content = null)
{
    extract(shortcode_atts(array(
                'icon'            => 'fa-clock-o',
                'icon_size'       => '1',
                'title'           => 'The Title',
                'title_tag'       => 'h3',
                'subtitle_tag'    => 'h3',
                'subtitle'        => 'The Subtitle',
                'description_tag' => 'p',
                    ), $atts));

    $out      = '';
    $iconSize = (int) $icon_size > 1 ? 'fa-' . $icon_size . 'x' : '';

    $titleTagOpen        = !empty($title_tag) ? '<' . $title_tag . '>' : '';
    $titleTagClose       = !empty($title_tag) ? '</' . $title_tag . '>' : '';
    $subtitleTagOpen     = !empty($subtitle_tag) ? '<' . $subtitle_tag . '>' : '';
    $subtitleTagClose    = !empty($subtitle_tag) ? '</' . $subtitle_tag . '>' : '';
    $descriptionTagOpen  = !empty($description_tag) ? '<' . $description_tag . '>' : '';
    $descriptionTagClose = !empty($description_tag) ? '</' . $description_tag . '>' : '';

    $class = 'ch-icon-bullet-text';

    $out .= '<div class="ch-shortcode ' . $class . '">
        <div class="one eighth mobile align-center ch-icon-bullet-text-icon">
            <i class="fa ' . $icon . ' ' . $iconSize . '"></i>
        </div>
        <div class="seven eighths mobile pad-left">' .
            $titleTagOpen . html_entity_decode($title) . $titleTagClose .
            $subtitleTagOpen . html_entity_decode($subtitle) . $subtitleTagClose .
            $descriptionTagOpen . html_entity_decode($content) . $descriptionTagClose .
            '</div>
        </div>';

    return $out;
}
