<?php
if (!class_exists('CbpWidgetBox')):

    class CbpWidgetBox extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_box',
                    /* Name */ 'Box', array('description' => 'This is a Box brick', 'icon'        => 'fa fa-font fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements = parent::registerFormElements($elements);
            
            $elements['width']        = 'one half';
            $elements['use_bg_color'] = '0';
            $elements['bg_color']     = '';
            $elements['wrap']         = '1';
            $elements['use_animation'] = '0';
            $elements['animation_effect'] = 'fade-in';
            $elements['animation_delay'] = '0';
            $elements['padding']      = 'double-padded'; // overwrite parent padding

            return $elements;
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_bg_color'),
                'value'             => $instance['use_bg_color'],
                'description_title' => $this->translate('Use Background Color?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_bg_color')
            ));

            CbpWidgetFormElements::colorPicker(array(
                'name'              => $this->getIdString('bg_color'),
                'value'             => $instance['bg_color'],
                'description_title' => $this->translate('Background color'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_bg_color', 'data-parentstate' => '1')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('wrap'),
                'value'             => $instance['wrap'],
                'description_title' => $this->translate('Wrap?'),
                'description_body'  => $this->translate('If not wrapped it will add nothing. This is especially good for use when creating layout fullwidth "content" widget.'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_animation'),
                'value'             => $instance['use_animation'],
                'description_title' => $this->translate('Use Animation?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_animation')

            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'fade-in-from-left'   => $this->translate('fade-in-from-left'),
                    'fade-in-from-right'  => $this->translate('fade-in-from-right'),
                    'fade-in-from-bottom' => $this->translate('fade-in-from-bottom'),
                    'fade-in'             => $this->translate('fade-in'),
                    'grow-in'             => $this->translate('grow-in'),
                    'flip-in'             => $this->translate('flip-in'),
                ),
                'name'              => $this->getIdString('animation_effect'),
                'value'             => $instance['animation_effect'],
                'description_title' => $this->translate('Animation Effect'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_animation', 'data-parentstate' => '1')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('animation_delay'),
                'value'             => $instance['animation_delay'],
                'description_title' => $this->translate('Animation Delay'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_animation', 'data-parentstate' => '1')
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'bg_color'           => '',
                        'type'               => 'cbp_widget_box',
                        'use_bg_color'       => '0',
                        'width'              => 'one half',
                        'wrap'               => '1',
                        'use_animation'      => '0',
                        'animation_effect'   => 'fade-in',
                        'animation_delay'    => '0',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => 'double-padded',
                            ), $atts));

//            $class              = str_replace('-', ' ', $width);
            $width              = CbpWidgets::getCssClass($width);
            $padding            = CbpWidgets::getCssClass($padding);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $animationCssClass  = (int) $use_animation ? 'cbp-has-animation' : '';
            $animation_effect    = (int) $use_animation ? 'data-animation="' . $animation_effect . '"' : '';
            $animation_delay    = (int) $use_animation ? 'data-delay="' . $animation_delay . '"' : '';
            ?>

            <?php if ((int) $wrap): ?>
                <?php $inlineStyle = (int) $use_bg_color ? 'style="background-color:' . $bg_color . '"' : ''; ?>
                <div class="<?php echo $type; ?> <?php echo $width; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $animationCssClass; ?>" <?php echo $animation_effect; ?> <?php echo $animation_delay; ?> <?php echo $inlineStyle; ?>><?php echo $content; ?></div>
            <?php else: ?>
                <?php echo $content; ?>
            <?php endif; ?>
            <?php
        }
    }

    

    

    

    
endif;
