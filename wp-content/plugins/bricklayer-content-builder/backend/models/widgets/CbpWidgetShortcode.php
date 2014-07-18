<?php
if (!class_exists('CbpWidgetShortcode')):

    class CbpWidgetShortcode extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_shortcode',
                    /* Name */ 'Shortcode', array('description' => 'This is a Shortcode/HTML brick.', 'icon'        => 'fa fa-terminal fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements = parent::registerFormElements($elements);

            $elements['content'] = '';

            return $elements;
        }

        public function form($instance)
        {
            parent::form($instance);
            
            CbpWidgetFormElements::textarea(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('content'),
                'value'             => $instance['content'],
                'description_title' => $this->translate('Shortcode'),
                'description_body'  => $this->translate('Paste your shortcode here.'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_shortcode',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>

            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?>">
            <?php echo $content; ?>
            </div>

            <?php
        }
    }

    

endif;
