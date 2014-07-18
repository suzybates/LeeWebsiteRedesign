<?php

class ChpWidgetPost extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_post',
                /* Name */ 'Post', array('description' => 'This is Post widget.', 'icon'        => 'fa fa-list-alt fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['img_size'] = '';

        return parent::registerFormElements($elements);
    }

    public function form($instance)
    {
        parent::form($instance);

        CbpWidgetFormElements::selectRegiseredImageSizes(array(
            'name'              => $this->getIdString('img_size'),
            'value'             => $instance['img_size'],
            'description_title' => $this->translate('Select Image Size'),
        ));
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'               => 'cbp_widget_post',
                    'img_size'           => '',
                    'css_class'          => '',
                    'custom_css_classes' => '',
                    'padding'            => '',
                        ), $atts));

        $css_class          = !empty($css_class) ? ' ' . $css_class : '';
        $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
        $padding            = CbpWidgets::getCssClass($padding);
        ?>
        <div class="<?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?>">

            <?php $subtitle           = get_the_subtitle(); ?>
            <?php if ($subtitle): ?> 


                <div class="one whole">
                    <div class="entry-subtitle ch-border-title">
                        <?php echo $subtitle; ?>
                    </div>
                </div>

            <?php endif; ?>
            <div class="<?php echo $type; ?>_meta">
                <?php $this->entryMeta(); ?>
                <?php edit_post_link(__('Edit', CHT_APP_TEXT_DOMAIN), '<span class="edit-link">', '</span>'); ?>
            </div>
            <div class="<?php echo $type; ?>_image double-pad-top">
                <?php cbp_get_the_image(array('size'         => $img_size, 'link_to_post' => false)); ?>
            </div>
            <div class="<?php echo $type; ?>_content double-pad-top">
                <?php the_content(); ?>
            </div>
        </div>
        <?php
        $comments      = new CbpWidgetComments();
        $comments->widget(array(), '');
    }

    protected function entryMeta()
    {
        if (is_sticky() && is_home() && !is_paged())
            echo '<span class="featured-post">' . __('Sticky', CBP_APP_TEXT_DOMAIN) . '</span>';

        if (!has_post_format('link'))
            $this->entryDate();

        // Translators: used between list items, there is a space after the comma.
        $categories_list = get_the_category_list(__(', ', CBP_APP_TEXT_DOMAIN));
        if ($categories_list) {
            echo '/<span class="categories-links">' . $categories_list . '</span>';
        }

        // Translators: used between list items, there is a space after the comma.
        $tag_list = get_the_tag_list('', __(', ', CBP_APP_TEXT_DOMAIN));
        if ($tag_list) {
            echo '/<span class="tags-links">' . $tag_list . '</span>';
        }

        // Post author
        printf('/<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', CBP_APP_TEXT_DOMAIN), get_the_author())), get_the_author()
        );
    }

    protected function entryDate($echo = true)
    {
        if (has_post_format(array('chat', 'status')))
            $format_prefix = _x('%1$s on %2$s', '1: post format name. 2: date', CBP_APP_TEXT_DOMAIN);
        else
            $format_prefix = '%2$s';

        $date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url(get_permalink()), esc_attr(sprintf(__('Permalink to %s', CBP_APP_TEXT_DOMAIN), the_title_attribute('echo=0'))), esc_attr(get_the_date('c')), esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()), get_the_date()))
        );

        if ($echo)
            echo $date;

        return $date;
    }
}

CbpWidgets::registerWidget('ChpWidgetPost');
