<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
//add_action('widgets_init', 'cht_register_theme_widgets');
//
//function cht_register_theme_widgets()
//{
//    /* 1  */ register_widget('ChtText');
//}
//
//class ChtText extends ChtWidgetHelper
//{
//
//    public function __construct()
//    {
//        $this->_attrs = array(
//            'title'     => $this->translate('New Title'),
//            'text'      => null,
//            'signature' => null,
//            'width'     => 'fourth',
//            'color'     => '#f2f2f2'
//        );
//
//        parent::__construct(
//                /* Base ID */'glider_text_widget',
//                /* Name */ 'Glider - Text', array('description' => 'Text'));
//    }
//
//    public function form($instance)
//    {
//        $instance = $this->getMergedAttrs($instance);
//
//        $this->formElementText('title', $instance['title']);
//
//        $widths = array('fourth', 'third', 'half', 'full');
//        $this->formElementSelect('width', $widths, $instance['width']);
//
//        $this->formElementTextarea('text', $instance['text']);
//        $this->formElementText('signature', $instance['signature']);
//        $this->colorPicker(array(
//            'name'        => 'color',
//            'value'       => $instance['color'] ? $instance['color'] : '#f2f2f2', // this is needed because of the bug
//            'description' => $this->translate('Background color'),
//        ));
//    }
//
//    public function widget($args, $instance)
//    {
//        extract($args);
//        extract($this->walk($instance, 'filterWidgetTitle'));
//
//        $output = '<div class="glider-widget ' . $width . '">';
//        $output .= '<div class="title">' . $title . '</div>';
//        $output .= '<hr />';
//        $output .= '<div class="well" style="background-color:' . esc_attr($color) . ';">' . $text . '</div>';
//        $output .= '<div class="signature">' . $signature . '</div>';
//        $output .= '</div>';
//
//        echo $output;
//    }
//}
//
