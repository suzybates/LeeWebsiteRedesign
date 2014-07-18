<?php

class ChpLatestPostsWidget extends WP_Widget
{

    /**
     * Constructor
     */
    public function __construct()
    {

        $widget_ops = array('classname'   => 'widget-latest-posts',
            'description' => __('Kids - Latest Post from News category.( for footer section)', 'kids_theme'));

        parent::__construct(
                'chp_latest_posts', 'Carry Hill - Latest Posts Widget', $widget_ops
        );
    }

    /**
     * Outputs the options form on admin
     * @see WP_Widget::form()
     * @param $instance current settings
     */
    public function form($instance)
    {

        //Get Posts from first category (current one)
        $default = array(
            'title'           => __('Latest Posts', CHT_APP_TEXT_DOMAIN),
            'current_cat'     => null,
            'number_of_posts' => 2,
            'date_format'     => 'j M, Y',
            'cat_link_text'   => 'View All',
        );

        $instance = wp_parse_args((array) $instance, $default);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', CHT_APP_TEXT_DOMAIN); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('current_cat'); ?>"><?php _e('Category for News', CHT_APP_TEXT_DOMAIN); ?></label><br />
            <?php
            wp_dropdown_categories(array('selected'   => $instance['current_cat'],
                'name'       => $this->get_field_name('current_cat'),
                'id'         => $this->get_field_id('current_cat'),
                'class'      => 'widefat',
                'show_count' => true,
                'hide_empty' => false,
                'orderby'    => 'name'));
            ?>                          
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_posts'); ?>"><?php _e('Number of Posts', CHT_APP_TEXT_DOMAIN); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('number_of_posts'); ?>" id="<?php echo $this->get_field_id('number_of_posts'); ?>" value="<?php echo esc_attr($instance['number_of_posts']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('date_format'); ?>"><?php _e('Date Format', CHT_APP_TEXT_DOMAIN); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('date_format'); ?>" id="<?php echo $this->get_field_id('date_format'); ?>" value="<?php echo esc_attr($instance['date_format']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat_link_text'); ?>"><?php _e('Category Link Button Text', CHT_APP_TEXT_DOMAIN); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('cat_link_text'); ?>" id="<?php echo $this->get_field_id('cat_link_text'); ?>" value="<?php echo esc_attr($instance['cat_link_text']); ?>" />
        </p>
        <?php
    }

    /**
     * processes widget options to be saved
     * @see WP_Widget::update()
     */
    public function update($new_instance, $old_instance)
    {

        $instance = array();
        if (empty($old_instance)) {
            $old_instance = $new_instance;
        }

        if ($new_instance['num'] > 8)
            $new_instance['num'] = 8;

        foreach ($old_instance as $k => $value) {
            $instance[$k] = trim(strip_tags($new_instance[$k]));
        }
        return $instance;
    }

    /**
     * Front-end display of widget.
     * @see WP_Widget::widget()
     * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget
     */
    public function widget($args, $instance)
    {
        extract($args);
        //Get leatest posts from upcoming Events Category
        $args = array(
            'numberposts'      => $instance['number_of_posts'],
            'category'         => $instance['current_cat'],
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false);

        $posts = get_posts($args);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        ?>
        <?php if ($title): ?>
            <?php echo $before_title . $title . $after_title; ?>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>

            <div class="one whole pad-bottom cbp-widget-post-list-item">
                <div class="ch-border-title">
                    <a title="<?php echo $post->post_title; ?>" href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>                                
                </div>
                <div class="cbp-widget-post-meta-data">

                    <span class="cbp-widget-post-meta-date">
                        <?php echo date($instance['date_format'], strtotime($post->post_date)); ?>
                    </span>
                    <span class="cbp-widget-post-meta-author">
                        <?php _e('by', CHT_APP_TEXT_DOMAIN); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                            <?php the_author_meta('display_name'); ?>
                        </a>
                    </span>

                </div>

                <div class="cbp-widget-post-content">
                    <?php $text = apply_filters('widget_text', $post->post_content); ?>
                    <p><?php echo wp_trim_words($text, 10); ?></p>                        </div>
            </div>











        <?php endforeach; ?>     
        <?php if (!empty($instance['cat_link_text'])): ?>

            <?php $category_link = get_category_link($instance['current_cat']); ?>
            <a class="cbp_widget_link cbp_widget_button" href="<?php echo esc_url($category_link); ?>"><?php echo $instance['cat_link_text']; ?></a>
        <?php endif; ?>
        <?php
        echo $after_widget;
    }

}

//Class End

register_widget('ChpLatestPostsWidget');
