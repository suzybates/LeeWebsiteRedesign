<?php

if (!class_exists('CbpWidgetWpSidebar')):

    class CbpWidgetWpSidebar extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_wp_sidebar',
                    /* Name */ 'WP Sidebar', array('description' => 'WP Sidebar brick', 'icon'        => 'fa fa-columns fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['sidebar']           = '';
            $elements['number_of_columns'] = 'one third';
            $elements['title_size']        = 'h2';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            $sidebars = CbpFront::getSidebars();

            $options = array();
            foreach ($sidebars as $key => $value) {
                $options[$key] = $value['name'];
            }

            CbpWidgetFormElements::select(array(
                'options'           => $options,
                'name'              => $this->getIdString('sidebar'),
                'value'             => $instance['sidebar'],
                'description_title' => $this->translate('Sidebar'),
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
                    'one whole'         => '1',
                    'one half'          => '2',
                    'one third'         => '3',
                    'one fourth'        => '4',
                ),
                'name'              => $this->getIdString('number_of_columns'),
                'value'             => $instance['number_of_columns'],
                'description_title' => $this->translate('Number of Columns'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_wp_sidebar',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'sidebar'            => '',
                        'number_of_columns'  => 'one third',
                        'title_size'         => 'h2',
                        'padding'            => '',
                            ), $atts));
            global $wp_registered_sidebars;

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);

            $wp_registered_sidebars[$sidebar]['before_widget'] = '<div class="widget ' . CbpWidgets::getDefaultWidgetCssClass() . ' ' . $type . $custom_css_classes . $css_class . ' ' . $padding . $number_of_columns . '">';
            $wp_registered_sidebars[$sidebar]['after_widget']  = '</div>';
            $wp_registered_sidebars[$sidebar]['before_title']  = '<' . $title_size . '>';
            $wp_registered_sidebars[$sidebar]['after_title']   = '</' . $title_size . '>';

            if (!empty($sidebar)) {
                dynamic_sidebar($sidebar);
            }
        }
    }

endif;
