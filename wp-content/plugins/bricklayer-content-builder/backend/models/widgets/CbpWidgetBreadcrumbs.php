<?php
if (!class_exists('CbpWidgetBreadcrumbs')):

    class CbpWidgetBreadcrumbs extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_breadcrumbs',
                    /* Name */ 'Bread crumbs', array('description' => 'This is a Breadcrumbs brick.', 'icon'        => 'fa fa-angle-right fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['separator'] = '';
            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('separator'),
                'value'             => $instance['separator'],
                'description_title' => $this->translate('Separator'),
                'description_body'  => $this->translate('If left empty default slash separator will be shown.'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_breadcrumbs',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'separator'          => '',
                        'padding'            => '',
                            ), $atts));

            $padding            = CbpWidgets::getCssClass($padding);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php
                if (function_exists('breadcrumb_trail')) {

                    $options = array(
                        'show_browse'   => false,
                        'post_taxonomy' => array(
                        // 'post'  => 'post_tag',
                        )
                    );

                    if (!empty($separator)) {
                        $options['separator'] = $separator;
                    }

                    breadcrumb_trail($options);
                }
                ?>
            </div>
            <?php
        }
    }

    

    

    

    

    

    

    

    
endif;
