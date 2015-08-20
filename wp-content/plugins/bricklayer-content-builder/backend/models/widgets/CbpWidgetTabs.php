<?php
if (!class_exists('CbpWidgetTabs')):

    class CbpWidgetTabs extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_tabs',
                    /* Name */ 'Tabs', array('description' => 'This is a Tabs brick', 'icon'        => 'fa fa-list fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            $elements['content'] = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_tabs_item',
                'subwidget_id' => 'cbp_subwidget_tabs_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_tabs',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));
            $shortcodes          = CbpWidgets::parseRawShortcode($content);
            $css_class           = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes  = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding             = CbpWidgets::getCssClass($padding);
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?> tab-container">
                <ul class="etabs">
                    <?php foreach ($shortcodes as $key => $shortcode): ?>
                        <?php if ($shortcode['atts']['type'] == 'cbp_tabs_item'): ?>
                            <li class="tab"><a href="#tab<?php echo $key; ?>"><?php echo $shortcode['atts']['name']; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php foreach ($shortcodes as $key => $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_tabs_item'): ?>
                        <div id="tab<?php echo $key; ?>">
                            <?php echo do_shortcode($shortcode['content']); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php
        }
    }

    

    

    
endif;
