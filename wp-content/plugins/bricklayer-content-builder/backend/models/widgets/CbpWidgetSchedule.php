<?php
if (!class_exists('CbpWidgetSchedule')):

    class CbpWidgetSchedule extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_schedule',
                    /* Name */ 'Schedule', array('description' => 'This is an Schedule brick', 'icon'        => 'fa fa-align-right fa-3x'));

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
                'type'         => 'cbp_schedule_item',
                'subwidget_id' => 'cbp_subwidget_schedule_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_schedule',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));
            $shortcodes          = CbpWidgets::parseRawShortcode($content);
            $padding             = CbpWidgets::getCssClass($padding);
            $css_class           = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes  = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php foreach ($shortcodes as $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_schedule_item'): ?>
                        <h3 class="<?php echo $this->getPrefix() ?>-schedule-item-name">
                        <?php echo $shortcode['atts']['starttime']; ?>
                        -
                        <?php echo $shortcode['atts']['endtime']; ?>
                        <?php echo $shortcode['atts']['activity']; ?></h3>
                        <div class="<?php echo $this->getPrefix() ?>-schedule-item-content">
                            <?php echo $shortcode['content']; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php
        }
    }

    

    
   
endif;
