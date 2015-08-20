<?php
if (!class_exists('CbpWidgetSliderOwl')):

    class CbpWidgetSliderOwl extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_slider_owl',
                    /* Name */ 'Owl Slider', array('description' => 'This is a Owl Slider brick', 'icon' => 'fa fa-eye fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            // these are left to be true and false because this is what is going to be printed as javascript true and false
            $elements['layout']             = 'default';
            $elements['auto_play']          = 'false';
            $elements['number_of_images']   = 3;
            $elements['show_bullets']       = 'true';
            $elements['show_controls']      = 'true';
            $elements['transition']         = 'horizontal';
            $elements['speed']              = '1000';
            $elements['adaptive_height']    = 'true';
            $elements['taxonomy_namespace'] = '';
            $elements['category_id']        = '';

            $elements['show_featured_image']  = '1';
            $elements['thumbnail_dimensions'] = 'thumbnail';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'default' => $this->translate('Default'),
                ),
                'name'              => $this->getIdString('layout'),
                'value'             => $instance['layout'],
                'description_title' => $this->translate('Element Layout'),
            ));
            CbpWidgetFormElements::filterTaxonomy(array(
                'name'              => $this->getIdString('taxonomy_filter'),
                'values'            => array(
                    $this->getIdString('taxonomy_namespace') => $instance['taxonomy_namespace'],
                    $this->getIdString('category_id')        => $instance['category_id'],
                ),
                'description_title' => $this->translate('Select Posts'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('number_of_images'),
                'value'             => $instance['number_of_images'],
                'description_title' => $this->translate('Number of images per slide.'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'horizontal' => $this->translate('horizontal'),
                    'vertical'   => $this->translate('vertical'),
                    'fade'       => $this->translate('fade'),
                ),
                'name'              => $this->getIdString('transition'),
                'value'             => $instance['transition'],
                'description_title' => $this->translate('Transition'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'true'  => $this->translate('Yes'),
                    'false' => $this->translate('No')
                ),
                'name'              => $this->getIdString('auto_play'),
                'value'             => $instance['auto_play'],
                'description_title' => $this->translate('Auto Play?'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'true'  => $this->translate('Yes'),
                    'false' => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_controls'),
                'value'             => $instance['show_controls'],
                'description_title' => $this->translate('Show Controls?'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'true'  => $this->translate('Yes'),
                    'false' => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_bullets'),
                'value'             => $instance['show_bullets'],
                'description_title' => $this->translate('Show Bullets?'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    'true'  => $this->translate('Yes'),
                    'false' => $this->translate('No')
                ),
                'name'              => $this->getIdString('adaptive_height'),
                'value'             => $instance['adaptive_height'],
                'description_title' => $this->translate('Adaptive Height?'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('speed'),
                'value'             => $instance['speed'],
                'description_title' => $this->translate('Speed'),
                'description_body'  => $this->translate('Time between slides in miliseconds. Enter number only!'),
            ));
            CbpWidgetFormElements::select(array(
                'options'           => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
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
                'attribs'           => array('data-type' => 'triggerchild', 'data-parent' => 'show_featured_image', 'data-parentstate' => '1')
            ));
            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_slider_item',
                'subwidget_id' => 'cbp_subwidget_slider_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                'type'                 => 'cbp_widget_slider',
                'css_class'            => '',
                'custom_css_classes'   => '',
                'layout'               => 'default',
                'auto_play'            => 'false',
                'number_of_images'     => 3,
                'show_bullets'         => 'true',
                'show_controls'        => 'true',
                'transition'           => 'horizontal',
                'speed'                => '1000',
                'adaptive_height'      => 'true',
                'padding'              => '',
                'taxonomy_namespace'   => '',
                'category_id'          => '',
                'show_featured_image'  => '1',
                'thumbnail_dimensions' => 'thumbnail',
                            ), $atts));


            $shortcodes         = CbpWidgets::parseRawShortcode($content);
            $id                 = uniqid($this->getPrefix() . '-slider-');
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);

            // need to find the post type so we need taxonomy
            $taxonomy  = get_taxonomy($taxonomy_namespace);
            $post_type = $taxonomy->object_type[0];

            $posts = get_posts(array(
                'post_type' => $post_type,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy_namespace,
                        'field'    => 'id',
                        'terms'    => array($category_id))
                ),
            ));

            $filename = __DIR__ . '/partials/slider-owl/' . $layout . '.php';
            ?>

            <div id="<?php echo $id; ?>" class="owl-carousel <?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php foreach ($posts as $post): ?>
                    <?php if (is_file($filename)): ?>
                        <?php include $filename; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <script>

                var cbp_content_builder = cbp_content_builder || {};
                cbp_content_builder.data = cbp_content_builder.data || {};

                if (!cbp_content_builder.data.sliders_owl) {
                    cbp_content_builder.data.sliders_owl = [];
                }

                cbp_content_builder.data.sliders_owl.push({
                    id: '<?php echo $id; ?>',
                    options: {
                        items: <?php echo (int) $number_of_images; ?>,
                        itemsCustom: false,
                        itemsDesktop: [1199, 4],
                        itemsDesktopSmall: [980, 3],
                        itemsTablet: [768, 2],
                        itemsTabletSmall: false,
                        itemsMobile: [479, 1],
                        singleItem: false,
                        itemsScaleUp: false,
                        //Basic Speeds
                        slideSpeed: <?php echo $speed; ?>,
                        paginationSpeed: 800,
                        rewindSpeed: 1000,
                        //Autoplay
                        autoPlay: <?php echo $auto_play; ?>,
                        stopOnHover: false,
                        // Navigation
                        navigation: <?php echo $show_controls; ?>,
                        navigationText: ["prev", "next"],
                        rewindNav: true,
                        scrollPerPage: false,
                        //Pagination
                        pagination: true,
                        paginationNumbers: <?php echo $show_bullets; ?>,
                        // Responsive
                        responsive: true,
                        responsiveRefreshRate: 200,
                        responsiveBaseWidth: window,
                        // CSS Styles
                        baseClass: "owl-carousel",
                        theme: "owl-theme",
                        //Lazy load
                        lazyLoad: true,
                        lazyFollow: true,
                        lazyEffect: "fade",
                        //Auto height
                        autoHeight: <?php echo $adaptive_height; ?>
                    }
                });

            </script>
            <?php
        }

    }

endif;
