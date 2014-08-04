<?php

class ChpWidgetContent extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_content',
                /* Name */ 'Content', array('description' => 'This is Content widget. This is just a placeholder for the page content.', 'icon'        => 'fa fa-list-alt fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['width'] = 'one half';
        $elements['wrap']  = '1';

        return parent::registerFormElements($elements);
    }

    public function form($instance)
    {
        parent::form($instance);

        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('wrap'),
            'value'             => $instance['wrap'],
            'description_title' => $this->translate('Wrap?'),
            'description_body'  => $this->translate('If not wrapped it will add nothing. This is especially good for use when creating layout fullwidht "content" widget.'),
        ));
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'               => 'cbp_widget_box',
                    'wrap'               => '0',
                    'css_class'          => '',
                    'custom_css_classes' => '',
                    'padding'            => 'double-padded',
                        ), $atts));

        $css_class          = !empty($css_class) ? ' ' . $css_class : '';
        $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
        $padding            = CbpWidgets::getCssClass($padding);



        if (function_exists('eme_is_events_page') && function_exists('eme_events_page_content') && eme_is_events_page()) {

            if ((int) CbpUtils::getSettingAttribute('row', 'wrap')) {
                echo eme_events_page_content();
            } else { // if not wrapped
                echo '<div class="' . CbpWidgets::getCssClass('row') . ' ' . CbpUtils::getSettingAttribute('row', 'css_class') . ' ' . CbpUtils::getSettingAttribute('row', 'custom_css_classes') . '">';
                echo '<div class="' . CbpWidgets::getCssClass('container') . ' ' . CbpUtils::getSettingAttribute('box', 'css_class') . ' ' . CbpUtils::getSettingAttribute('box', 'custom_css_classes') . ' ' . CbpUtils::getSettingAttribute('box', 'padding') . '">';
                echo eme_events_page_content();
                echo '</div>';
                echo '</div>';
            }
        } else {



            $content = '';

            global $wp_query;

            $postObj = $wp_query->get_queried_object();


            if (property_exists($postObj, 'post_content')) {

                $content = $postObj->post_content;
                ?>

                <?php if ((int) $wrap): ?>
                    <div class="<?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?>">
                        <?php echo do_shortcode(apply_filters('cbp_content_widget', $content)); ?>
                    </div>
                <?php else: ?>
                    <?php echo do_shortcode(apply_filters('cbp_content_widget', $content)); ?>
                <?php endif; ?>
                <?php
                // this hook makes it possible for the theme or another pugin to filter the content
                // bofore it is printed. This is useful for single post pages or category pages...
                // where the content is formated in a certain way or have additions like comments...
//            echo do_shortcode(apply_filters('cbp_content_widget', $content));
            }
            wp_reset_query();
        }
    }
}

CbpWidgets::registerWidget('ChpWidgetContent');
