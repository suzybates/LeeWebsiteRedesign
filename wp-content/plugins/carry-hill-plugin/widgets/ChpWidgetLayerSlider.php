<?php

class ChpWidgetLayerSlider extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_layer_slider',
                /* Name */ 'Layer Slider', array('description' => 'This is Layer Slider widget.', 'icon'        => 'fa fa-files-o fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements = parent::registerFormElements($elements);
        
        $elements['slider_id'] = '';
        
        unset($elements['padding']);
        unset($elements['custom_css_classes']);

        return $elements;
    }

    public function form($instance)
    {
        parent::form($instance);

        $sliders = lsSliders();
        $options = array();

        $options[''] = 'no slider';
        foreach ($sliders as $slider) {
            $options[$slider['id']] = $slider['name'];
        }

        CbpWidgetFormElements::select(array(
            'options'           => $options,
            'name'              => $this->getIdString('slider_id'),
            'value'             => $instance['slider_id'],
            'description_title' => $this->translate('Layer Slider'),
            'description_body'  => $this->translate('Select slider.'),
        ));
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'      => '',
                    'css_class' => '',
            'custom_css_classes' => '',
                    'slider_id' => '',
                        ), $atts));

        if (!empty($slider_id) && function_exists('layerslider_init')) {
            echo layerslider_init(array('id' => $slider_id));
        }
    }
}

CbpWidgets::registerWidget('ChpWidgetLayerSlider');