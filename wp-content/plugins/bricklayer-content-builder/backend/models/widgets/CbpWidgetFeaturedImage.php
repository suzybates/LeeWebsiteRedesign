<?php
if (!class_exists('CbpWidgetFeaturedImage')):

    class CbpWidgetFeaturedImage extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_featured_image',
                    /* Name */ 'Featured Image', array('description' => 'This is a Featured Image brick.', 'icon'        => 'fa fa-picture-o fa-3x'));
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
                        'type'               => 'cbp_widget_featured_image',
                        'img_size'           => '',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?>">
                <?php cbp_get_the_image(array('size'         => $img_size, 'link_to_post' => false)); ?>
            </div>
            <?php
        }
    }

endif;
