<?php
if (!class_exists('CbpWidgetMeta')):

    class CbpWidgetMeta extends CbpWidget
    {
        protected $_tags;

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_meta',
                    /* Name */ 'Meta Data', array('description' => 'This is a Meta brick', 'icon'        => 'fa fa-circle-o fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            $elements['content'] = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {

            parent::form($instance);

            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_meta_item',
                'subwidget_id' => 'cbp_subwidget_meta_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => '',
                        'custom_css_classes' => '',
                        'css_class'          => '',
                        'padding'            => 'double-padded',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);


            $shortcodes = CbpWidgets::parseRawShortcode($content);
            global $post;

            wp_reset_postdata(); // author does not work without this
            ?>

            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php foreach ($shortcodes as $key => $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_meta_item'): ?>
                        <?php if ($shortcode['atts']['item_type'] == 'date'): ?> 
                            <?php $dateIcon = (int) $shortcode['atts']['show_date_icon'] ? '<i class="fa fa-calendar"></i> ' : ''; ?> 
                            <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-date">
                                <?php echo $dateIcon; ?><?php echo date_i18n($shortcode['atts']['date_format'], strtotime($post->post_date)); ?>
                            </span>
                        <?php elseif ($shortcode['atts']['item_type'] == 'category'): ?>
                            <?php $categories = CbpUtils::getPostCategories($post->ID); ?>
                            <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-categories">
                                <?php foreach ($categories as $category): ?>
                                    <?php if ((int) $shortcode['atts']['category_is_link']): ?> 
                                        <a href="<?php echo $category['link']; ?>"><?php echo $category['name']; ?></a>
                                    <?php else: ?>
                                        <?php echo $category['name']; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </span>
                        <?php elseif ($shortcode['atts']['item_type'] == 'comment_count'): ?>
                            <?php $commentIcon = (int) $shortcode['atts']['show_comment_icon'] ? '<i class="fa fa-comments"></i> ' : ''; ?> 
                            <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-comments">
                                <?php echo $commentIcon; ?>(<?php echo $post->comment_count; ?>)
                            </span>
                        <?php elseif ($shortcode['atts']['item_type'] == 'tags'): ?>
                            <?php $posttags = get_the_tags(); ?>
                            <?php if ($posttags) : ?>
                                <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-tags">
                                    <?php $tagsIcon = (int) $shortcode['atts']['show_tags_icon'] ? '<i class="fa fa-tags"></i> ' : ''; ?> 
                                    <?php echo $tagsIcon; ?>
                                    <?php if ((int) $shortcode['atts']['tags_is_link']): ?>
                                        <?php foreach ($posttags as $tag) : ?>
                                            <a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach ($posttags as $tag) : ?>
                                            <?php echo $tag->name; ?>&nbsp;
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        <?php elseif ($shortcode['atts']['item_type'] == 'author'): ?>
                            <?php if ((int) $shortcode['atts']['author_is_link']): ?>
                                <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-author">
                                    <?php echo $shortcode['atts']['author_pretext']; ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                    <?php the_author_meta('display_name'); ?>
                                    </a>
                                </span>
                            <?php else: ?>
                                <span class="<?php echo $this->getPrefix(); ?>-widget-post-meta-author">
                                    <?php echo $shortcode['atts']['author_pretext']; ?> <?php the_author_meta('display_name'); ?>
                                </span>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php
        }
    }

    

endif;
