<?php
if (!class_exists('CbpWidgetCategory')):

    class CbpWidgetCategory extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_category',
                    /* Name */ 'Category', array('description' => 'This is only for use with category pages.', 'icon'        => 'fa fa-sitemap fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['show_category_description'] = '0';
            $elements['show_tags']                 = '0';
            $elements['orderby']                   = 'name';
            $elements['order']                     = 'ASC';
            $elements['taxonomy']                  = 'category';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No'),
                ),
                'name'              => $this->getIdString('show_category_description'),
                'value'             => $instance['show_category_description'],
                'description_title' => $this->translate('Show Category Description?'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No'),
                ),
                'name'              => $this->getIdString('show_tags'),
                'value'             => $instance['show_tags'],
                'description_title' => $this->translate('Show Tags?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_tags'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'name'              => $this->translate('Name'),
                    'date'              => $this->translate('Date'),
                ),
                'name'              => $this->getIdString('orderby'),
                'value'             => $instance['orderby'],
                'description_title' => $this->translate('Order By'),
                'description_body'  => $this->translate('Order tags by...'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_tags', 'data-parentstate' => '1'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'ASC'               => $this->translate('Ascending'),
                    'DESC'              => $this->translate('Descending'),
                ),
                'name'              => $this->getIdString('order'),
                'value'             => $instance['order'],
                'description_title' => $this->translate('Order'),
                'description_body'  => $this->translate('Tag Order'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_tags', 'data-parentstate' => '1'),
            ));
            CbpWidgetFormElements::selectTaxonomy(array(
                'name'              => $this->getIdString('taxonomy'),
                'value'             => $instance['taxonomy'],
                'description_title' => $this->translate('Taxonomy'),
                'description_body'  => $this->translate('Select taxonomy.'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_tags', 'data-parentstate' => '1'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'                      => 'cbp_widget_category',
                        'css_class'                 => '',
                        'custom_css_classes'        => '',
                        'padding'                   => 'double-padded',
                        'show_tags'                 => '0',
                        'show_category_description' => '0',
                        'orderby'                   => '0',
                        'order'                     => '0',
                        'taxonomy'                  => '0',
                        'padding'                   => '0',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>

            <?php if (have_posts()): ?>
                <?php if ((int) $show_category_description && category_description()): // Show an optional category description    ?>
                <div class="<?php echo CbpWidgets::getPrefix()?>-widget-category-description"><?php echo category_description(); ?></div>
                <?php endif; ?>
                <div class="ch-gallery">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php if ((int) $show_tags): ?> 
                            <?php $tags      = get_the_terms(get_the_ID(), $taxonomy) ?>
                            <?php $tagsClass = ''; ?>
                            <?php if ($tags) : ?>
                                <?php foreach ($tags as $tag) : ?>
                                    <?php $tagsClass .= ' ' . $tag->slug; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="ch-gallery-item mix one fourth <?php echo $tagsClass; ?> double-pad-right">
                            <div class="ch-gallery-item-overlay">
                                <a href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
                                <?php $get_the_image_as_array = cbp_get_the_image(array('format' => 'array', 'size'   => 'full')); ?>
                                <?php if ($get_the_image_as_array): ?>
                                    <a href="<?php echo $get_the_image_as_array['src']; ?>" data-lightbox="gallery" title="<?php the_title(); ?>"><i class="fa fa-eye"></i></a>
                                <?php endif; ?>
                            </div>
                            <?php cbp_get_the_image(array('link_to_post' => false)); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            <?php
            wp_reset_query();
        }
    }

    

    

    

    

    

    

    

    

    

    

    

    

    

    

        

    

    

    

    

    

    
endif;
