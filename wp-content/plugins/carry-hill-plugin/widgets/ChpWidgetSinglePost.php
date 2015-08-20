<?php

class ChpWidgetSinglePost extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_single_post',
                /* Name */ 'Single Post', array('description' => 'This is Single Post widget.', 'icon'        => 'fa fa-list-alt fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['post_id']    = null;
        $elements['title']      = '';
        $elements['title_size'] = 'h2';

        $elements['show_post_date']      = '1';
        $elements['show_post_date_icon'] = '1';
        $elements['post_date_format']    = 'M j, Y';

        $elements['show_comment_count'] = '1';
        $elements['show_comment_icon']  = '1';

        $elements['show_tags']      = '1';
        $elements['tags_is_link']   = '1';
        $elements['show_tags_icon'] = '1';

        $elements['show_author']      = '1';
        $elements['author_is_link']   = '1';
        $elements['show_author_icon'] = '1';

        $elements['show_featured_image']  = '0';
        $elements['thumbnail_dimensions'] = 'thumbnail';

        $elements['link_text']            = 'read more';
        $elements['number_of_characters'] = 200;
        $elements['title_link_to_post']   = '1';

        return parent::registerFormElements($elements);
    }

    public function form($instance)
    {
        parent::form($instance);

        CbpWidgetFormElements::selectPost(array(
            'name'              => $this->getIdString('post_id'),
            'value'             => $instance['post_id'],
            'description_title' => $this->translate('Select Post'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('title'),
            'value'             => $instance['title'],
            'description_title' => $this->translate('Custom Title'),
            'description_body'  => $this->translate('If this is not set post title will be used.'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                'h1'                => $this->translate('H1'),
                'h2'                => $this->translate('H2'),
                'h3'                => $this->translate('H3'),
                'h4'                => $this->translate('H4'),
                'h5'                => $this->translate('H5'),
                'h6'                => $this->translate('H6'),
            ),
            'name'              => $this->getIdString('title_size'),
            'value'             => $instance['title_size'],
            'description_title' => $this->translate('Title Size'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('title_link_to_post'),
            'value'             => $instance['title_link_to_post'],
            'description_title' => $this->translate('Should Title Link to Post?'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_post_date'),
            'value'             => $instance['show_post_date'],
            'description_title' => $this->translate('Show Post Date?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_post_date')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_post_date_icon'),
            'value'             => $instance['show_post_date_icon'],
            'description_title' => $this->translate('Show Post Date Icon?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_post_date', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                'M j, Y'            => date('M j, Y'),
                'j M, Y'            => date('j M, Y'),
                'F j, Y'            => date('F j, Y'),
                'j F, Y'            => date('j F, Y'),
            ),
            'name'              => $this->getIdString('post_date_format'),
            'value'             => $instance['post_date_format'],
            'description_title' => $this->translate('Post Date Format'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_post_date', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_comment_count'),
            'value'             => $instance['show_comment_count'],
            'description_title' => $this->translate('Show Comment Count?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_comment_count')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_comment_icon'),
            'value'             => $instance['show_comment_icon'],
            'description_title' => $this->translate('Show Comment Icon?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_comment_count', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_tags'),
            'value'             => $instance['show_tags'],
            'description_title' => $this->translate('Show Tags?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_tags')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('tags_is_link'),
            'value'             => $instance['tags_is_link'],
            'description_title' => $this->translate('Should Tags link to tags page?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_tags', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_tags_icon'),
            'value'             => $instance['show_tags_icon'],
            'description_title' => $this->translate('Show Tags Icon?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_tags', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_author'),
            'value'             => $instance['show_author'],
            'description_title' => $this->translate('Show Autor?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_author')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('author_is_link'),
            'value'             => $instance['author_is_link'],
            'description_title' => $this->translate('Should Author link to author page?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_author', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_author_icon'),
            'value'             => $instance['show_author_icon'],
            'description_title' => $this->translate('Show Author Icon?'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_author', 'data-parentstate' => '1')
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_featured_image'),
            'value'             => $instance['show_featured_image'],
            'description_title' => $this->translate('Show Featured Image?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_featured_image')
        ));
        CbpWidgetFormElements::selectRegiseredImageSizes(array(
            'name'              => $this->getIdString('thumbnail_dimensions'),
            'value'             => $instance['thumbnail_dimensions'],
            'description_title' => $this->translate('Featured Image Dimensions'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_featured_image', 'data-parentstate' => '1')
        ));

        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('number_of_characters'),
            'value'             => $instance['number_of_characters'],
            'description_title' => $this->translate('Number Of Characters'),
        ));

        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('link_text'),
            'value'             => $instance['link_text'],
            'description_title' => $this->translate('Link Text'),
        ));
    }

    public function sanitize(&$attribute)
    {
        switch ($attribute['name']) {
            case CBP_APP_PREFIX . 'title':
            case CBP_APP_PREFIX . 'link_text':
                $attribute['value'] = sanitize_text_field($attribute['value']);
                break;
            case CBP_APP_PREFIX . 'number_of_characters':
                if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                    $attribute['value'] = 200;
                }
                break;
        }

        return parent::sanitize($attribute);
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'                 => '',
                    'css_class'            => '',
                    'custom_css_classes'   => '',
                    'post_id'              => 1,
                    'title'                => '',
                    'title_size'           => 'h2',
                    'title_link_to_post'   => '1',
                    'show_post_date'       => '1',
                    'show_post_date_icon'  => '1',
                    'post_date_format'     => 'M j, Y',
                    'show_comment_count'   => '1',
                    'show_comment_icon'    => '1',
                    'show_tags'            => '1',
                    'tags_is_link'         => '1',
                    'show_tags_icon'       => '1',
                    'show_author'          => '1',
                    'author_is_link'       => '1',
                    'show_author_icon'     => '1',
                    'show_featured_image'  => '0',
                    'thumbnail_dimensions' => 'thumbnail',
                    'number_of_characters' => 200,
                    'link_text'            => 'read more',
                    'padding'              => 'double-pad-bottom',
                        ), $atts));

        $post               = get_post($post_id);
        $padding            = CbpWidgets::getCssClass($padding);
        $css_class          = !empty($css_class) ? ' ' . $css_class : '';
        $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
        ?>
        <div class="<?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
            <<?php echo $title_size; ?>>
            <?php if ((int) $title_link_to_post): ?>
                <a href="<?php echo get_permalink($post->ID); ?>"><?php echo!empty($title) ? $title : $post->post_title; ?></a>
            <?php else: ?>
                <<?php echo $title_size; ?>><?php echo!empty($title) ? $title : $post->post_title; ?>
            <?php endif; ?>
            </<?php echo $title_size; ?>>
            <?php if ((int) $show_featured_image): ?> 

                <?php $imageArgs = array('post_id' => $post_id, 'echo' => false, 'size' => $thumbnail_dimensions); ?>
                <?php $image = cbp_get_the_image($imageArgs); ?>
                <?php if (isset($image) && $image): ?>
                    <div class="<?php echo $this->getPrefix(); ?>-widget-post-image">
                        <?php echo $image; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
            <div class="<?php echo $this->getPrefix(); ?>-widget-post-meta-data">
                <?php if ((int) $show_post_date): ?> 
                    <?php $postDateIcon = (int) $show_post_date_icon ? '<i class="fa fa-calendar"></i> ' : ''; ?> 
                    <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-date">
                        <?php echo $postDateIcon; ?><?php echo date_i18n($post_date_format, strtotime($post->post_date)); ?>
                    </span>
                <?php endif; ?>
                <?php if ((int) $show_comment_count): ?> 
                    <?php $commentIcon = (int) $show_comment_icon ? '<i class="fa fa-comments"></i> ' : ''; ?> 
                    <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-comments">
                        <?php echo $commentIcon; ?>(<?php echo $post->comment_count; ?>)
                    </span>
                <?php endif; ?>
                <?php if ((int) $show_tags): ?>
                    <?php $posttags = get_the_tags(); ?>
                    <?php if ($posttags) : ?>
                        <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-tags">
                            <?php $tagsIcon = (int) $show_tags_icon ? '<i class="fa fa-tags"></i> ' : ''; ?> 
                            <?php echo $tagsIcon; ?>
                            <?php if ((int) $tags_is_link): ?>
                                <?php foreach ($posttags as $tag) : ?>
                                    <a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach ($posttags as $tag) : ?>
                                    <?php echo $tag->name; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ((int) $show_author): ?>
                    <?php if ((int) $author_is_link): ?>
                        <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-author">
                            <?php echo $this->translate('by'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                <?php the_author_meta('display_name'); ?>
                            </a>
                        </span>
                    <?php else: ?>
                        <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-author">
                            <?php echo $this->translate('by'); ?> <?php the_author_meta('display_name'); ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="<?php echo $this->getPrefix(); ?>-widget-post-content">
                <?php echo CbpUtils::trimmer(strip_shortcodes($post->post_content), $number_of_characters); ?>
            </div>
            <div class="<?php echo $this->getPrefix(); ?>-widget-post-link double-pad-top">
                <a class="cbp_widget_link" href="<?php echo get_permalink($post->ID); ?>"><?php echo $link_text; ?></a>
            </div>
        </div>

        <?php
    }
}

CbpWidgets::registerWidget('ChpWidgetSinglePost');