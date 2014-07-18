<?php
if (!class_exists('CbpWidgetDivider')):

    class CbpWidgetDivider extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_divider',
                    /* Name */ 'Divider', array('description' => 'This is a Divider brick.', 'icon'        => 'fa fa-minus fa-3x'));
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
                        'type'               => 'cbp_widget_divider',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));

            $padding            = CbpWidgets::getCssClass($padding);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            ?>
            <hr class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
            <?php
        }
    }

    

    

    

    
endif;
