<?php
if (!class_exists('CbpWidgetButton')):

    class CbpWidgetButton extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_button',
                    /* Name */ 'Button', array('description' => 'This is a Button brick.', 'icon'        => 'fa fa-plus-square fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements = parent::registerFormElements($elements);
            
            $elements['label']            = 'Click';
            $elements['link']             = '';
            $elements['is_external_link'] = '0';
            $elements['position']         = 'right';

            return $elements;
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('label'),
                'value'             => $instance['label'],
                'description_title' => $this->translate('Label'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('link'),
                'value'             => $instance['link'],
                'description_title' => $this->translate('Link'),
                'description_body'  => $this->translate('Please use absolute url.'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No'),
                ),
                'name'              => $this->getIdString('is_external_link'),
                'value'             => $instance['is_external_link'],
                'description_title' => $this->translate('External Link?'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    ''                  => $this->translate('Left'),
                    'pull-right'        => $this->translate('Right')
                ),
                'name'              => $this->getIdString('position'),
                'value'             => $instance['position'],
                'description_title' => $this->translate('Position'),
            ));
        }

        public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'label':
                    $attribute['value'] = sanitize_text_field($attribute['value']);
                    break;
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_button',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'label'              => 'Click',
                        'link'               => '',
                        'is_external_link'   => '0',
                        'position'           => '',
                        'padding'            => '',
                            ), $atts));

            $external           = (int) $is_external_link ? ' target="_blank"' : '';
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>
            <?php if (!empty($link) && !empty($label)): ?> 
                <a class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> cbp_widget_link <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?> <?php echo $position; ?>" href="<?php echo $link; ?>"<?php echo $external; ?>><?php echo $label; ?></a>
            <?php endif; ?>

            <?php
        }
    }

endif;
