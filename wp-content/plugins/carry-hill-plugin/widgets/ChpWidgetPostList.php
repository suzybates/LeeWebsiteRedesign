<?php

class ChpWidgetPostList extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_post_list',
                /* Name */ 'Post List', array('description' => 'This is Post List widget.', 'icon'        => 'fa fa-list-alt fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['grid_type'] = 'default';

        $elements['title']              = '';
        $elements['title_size']         = 'h2';
        $elements['title_link_to_post'] = '1';
        $elements['show_subtitle']      = '1';

        $elements['post_categories'] = '';
        $elements['post_title_size'] = 'h3';

        $elements['order_by'] = 'date';
        $elements['order']    = 'DESC';

        $elements['use_pagination'] = '0';
        $elements['posts_per_page'] = 10;

        $elements['show_post_date']      = '1';
        $elements['show_post_date_icon'] = '1';
        $elements['post_date_format']    = 'M j, Y';

        $elements['show_comment_count'] = '1';
        $elements['show_comment_icon']  = '1';

        $elements['show_tags']      = '1';
        $elements['tags_is_link']   = '1';
        $elements['show_tags_icon'] = '1';

        $elements['show_author']    = '1';
        $elements['author_is_link'] = '1';

        $elements['show_featured_image']  = '1';
        $elements['thumbnail_dimensions'] = 'thumbnail';

        $elements['number_of_columns']    = 'one whole';
        $elements['number_of_characters'] = 200;

        $elements['use_button_link'] = '1';
        $elements['link_text']       = 'read more';

        return parent::registerFormElements($elements);
    }

    public function form($instance)
    {
        parent::form($instance);

        CbpWidgetFormElements::select(array(
            'options' => array(
                'default'            => $this->translate('Default'),
                'grid_image_to_left' => $this->translate('Featured Image to Left')
            ),
            'name'               => $this->getIdString('grid_type'),
            'value'              => $instance['grid_type'],
            'description_title'  => $this->translate('Widget Grid'),
            'description_body'   => $this->translate('Select the order of post elements.'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('title'),
            'value'             => $instance['title'],
            'description_title' => $this->translate('Title'),
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
            'name'              => $this->getIdString('show_subtitle'),
            'value'             => $instance['show_subtitle'],
            'description_title' => $this->translate('Show Subtitle?'),
        ));
        CbpWidgetFormElements::selectCategories(array(
            'name'              => $this->getIdString('post_categories'),
            'value'             => explode(',', $instance['post_categories']),
            'description_title' => $this->translate('Select Post Categories'),
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
            'name'              => $this->getIdString('post_title_size'),
            'value'             => $instance['post_title_size'],
            'description_title' => $this->translate('Post Title Size'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                'date'              => $this->translate('Date'),
                'title'             => $this->translate('Title'),
            ),
            'name'              => $this->getIdString('order_by'),
            'value'             => $instance['order_by'],
            'description_title' => $this->translate('Order By'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                'ASC'               => $this->translate('Ascending'),
                'DESC'              => $this->translate('Descending'),
            ),
            'name'              => $this->getIdString('order'),
            'value'             => $instance['order'],
            'description_title' => $this->translate('Order'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('use_pagination'),
            'value'             => $instance['use_pagination'],
            'description_title' => $this->translate('Use Pagination?'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('posts_per_page'),
            'value'             => $instance['posts_per_page'],
            'description_title' => $this->translate('Posts Per Page'),
            'description_body'  => $this->translate('Enter number.'),
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
        CbpWidgetFormElements::select(array(
            'options' => array(
                'one whole'         => 1,
                'one half'          => 2,
                'one third'         => 3,
                'one fourth'        => 4,
            ),
            'name'              => $this->getIdString('number_of_columns'),
            'value'             => $instance['number_of_columns'],
            'description_title' => $this->translate('Number Of Columns'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('number_of_characters'),
            'value'             => $instance['number_of_characters'],
            'description_title' => $this->translate('Number Of Characters'),
            'description_body'  => $this->translate('Enter number.'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No'),
            ),
            'name'              => $this->getIdString('use_button_link'),
            'value'             => $instance['use_button_link'],
            'description_title' => $this->translate('Use Button Link?'),
            'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_button_link')
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('link_text'),
            'value'             => $instance['link_text'],
            'description_title' => $this->translate('Link Text'),
            'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_button_link', 'data-parentstate' => '1')
        ));
    }

    public function sanitize(&$attribute)
    {
        switch ($attribute['name']) {
            case CBP_APP_PREFIX . 'title':
            case CBP_APP_PREFIX . 'link_text':
                $attribute['value'] = sanitize_text_field($attribute['value']);
                break;
            case CBP_APP_PREFIX . 'posts_per_page':
                if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                    $attribute['value'] = 10;
                }
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
                    'grid_type'            => 'default',
                    'custom_css_classes'   => '',
                    'css_class'            => '',
                    'title'                => '',
                    'title_size'           => 'h2',
                    'title_link_to_post'   => '1',
                    'show_subtitle'        => '1',
                    'post_categories'      => '',
                    'post_title_size'      => 'h3',
                    'order_by'             => 'date',
                    'order'                => 'DESC',
                    'use_pagination'       => '0',
                    'posts_per_page'       => 10,
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
                    'show_featured_image'  => '1',
                    'thumbnail_dimensions' => 'thumbnail',
                    'number_of_columns'    => 'one whole',
                    'number_of_characters' => 200,
                    'use_button_link'      => '1',
                    'link_text'            => 'read more',
                    'padding'              => '',
                        ), $atts));

        global $paged;
        global $post;

        query_posts(array(
            'posts_per_page' => $posts_per_page,
            'category__in'   => explode(',', $post_categories),
            'paged'          => $paged,
            'orderby'        => $order_by,
            'order'          => $order
        ));

        $padding            = CbpWidgets::getCssClass($padding);
        $css_class          = !empty($css_class) ? ' ' . $css_class : '';
        $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
        ?>
        <?php if (have_posts()) : ?>
            <div class="<?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php if (!empty($title)): ?>
                    <<?php echo $title_size; ?>><?php echo $title; ?></<?php echo $title_size; ?>>
                <?php endif; ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="<?php echo $number_of_columns; ?> <?php echo $number_of_columns == 'one whole' ? '': 'double-pad-right'; ?> double-pad-bottom <?php echo $this->getPrefix(); ?>-widget-post-list-item">
                        <?php if ($grid_type == 'default'): ?> 
                            <?php include 'partials/post-list/default.phtml'; ?>
                        <?php elseif ($grid_type == 'grid_image_to_left'): ?>
                            <?php include 'partials/post-list/image-to-left.phtml'; ?>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        <?php if ((int) $use_pagination): ?>
            <?php CbpUtils::pagination(); ?>
        <?php endif; ?>
        <?php wp_reset_query(); ?>
        <?php
    }

}

CbpWidgets::registerWidget('ChpWidgetPostList');