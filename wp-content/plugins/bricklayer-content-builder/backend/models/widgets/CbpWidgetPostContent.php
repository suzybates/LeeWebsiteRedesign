<?php
if (!class_exists('CbpWidgetPostContent')):

    class CbpWidgetPostContent extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_post_content',
                    /* Name */ 'Post Content', array('description' => 'This is a Post Content brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_post_content',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php the_content(); ?>
            </div>
            <?php
        }
        
    }

endif;
