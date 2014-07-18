<?php

if (!class_exists('CbpWidgetWpWidget')):

    class CbpWidgetWpWidget extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_wp_widget',
                    /* Name */ 'WP Widget', array('description' => 'Here you can select one widget that is currently in Content Builder Sidebar.', 'icon'        => 'fa fa-cogs fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['widget_id']  = '';
            $elements['title_size'] = 'h2';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {

            parent::form($instance);

            global $wp_registered_widgets;

            $sidebars_widgets = wp_get_sidebars_widgets();
            $widgets          = array();
            foreach ((array) $sidebars_widgets[CBP_APP_PREFIX . 'sidebar'] as $id) {
                $widgets[$id] = $wp_registered_widgets[$id]['name'];
            }

            CbpWidgetFormElements::select(array(
                'options'           => $widgets,
                'name'              => $this->getIdString('widget_id'),
                'value'             => $instance['widget_id'],
                'description_title' => $this->translate('Widget'),
                'description_body'  => $this->translate('Select widget.'),
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
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_wp_widget',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'widget_id'          => '',
                        'title_size'         => 'h2',
                        'padding'            => '',
                            ), $atts));
            global $wp_registered_sidebars, $wp_registered_widgets;

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);

            if (!empty($widget_id)) {
 
                if (isset($wp_registered_widgets[$widget_id])) {

                    $widget = $wp_registered_widgets[$widget_id];

                    $params = array_merge(
                            array(array_merge($wp_registered_sidebars[CBP_APP_PREFIX . 'sidebar'], array('widget_id'                 => $widget_id, 'widget_name'               => $widget['name']))), (array) $widget['params']
                    );
                    $params[0]['before_widget'] = '<div class="widget ' . CbpWidgets::getDefaultWidgetCssClass() . ' ' . $type . $custom_css_classes . $css_class . ' ' . $padding . '">';
                    $params[0]['before_title']  = '<' . $title_size . '>';
                    $params[0]['after_title']   = '</' . $title_size . '>';
                    $params[0]['after_widget']  = '</div>';

                    if (is_callable($widget['callback'])) {
                        call_user_func_array($widget['callback'], $params);
                    }
                } else {
                    echo '<div class="cbp-missing-widget">'.$this->translate('Please, add a widget to "Bricklayer Sidebar".').'</div>';
                }
            }
        }
    }

    

    

    
endif;
