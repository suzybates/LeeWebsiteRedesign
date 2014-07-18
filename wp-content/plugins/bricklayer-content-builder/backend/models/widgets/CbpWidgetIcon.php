<?php
if (!class_exists('CbpWidgetIcon')):

    class CbpWidgetIcon extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_icon',
                    /* Name */ 'Icon', array('description' => 'This is an Icon brick.', 'icon'        => 'fa fa-star fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['icon']                    = 'fa-glass';
            $elements['html_element']            = 'div';
            $elements['icon_size']               = '';
            $elements['align_class']             = 'align-center';
            $elements['link_to']                 = '';
            $elements['custom_link']             = '';
            $elements['open_link_in_new_window'] = '0';
            $elements['float']                   = '';
            $elements['use_bg_color']            = '0';
            $elements['bg_color']                = '';
            $elements['bg_color_hover']          = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::iconSelect(array(
                'name'              => $this->getIdString('icon'),
                'value'             => $instance['icon'],
                'description_title' => $this->translate('Icon'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    ''                  => $this->translate('Default'),
                    'fa-2x'             => $this->translate('2x'),
                    'fa-3x'             => $this->translate('3x'),
                    'fa-4x'             => $this->translate('4x')
                ),
                'name'              => $this->getIdString('icon_size'),
                'value'             => $instance['icon_size'],
                'description_title' => $this->translate('Icon Size'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'div'               => $this->translate('Row (div)'),
                    'span'              => $this->translate('Float (span)'),
                ),
                'name'              => $this->getIdString('html_element'),
                'value'             => $instance['html_element'],
                'description_title' => $this->translate('Div or span'),
                'description_body'  => $this->translate('Should it be in it\'s own row or should it float?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'html_element')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'align-left'        => $this->translate('left'),
                    'align-center'      => $this->translate('center'),
                    'align-right'       => $this->translate('right'),
                ),
                'name'              => $this->getIdString('align_class'),
                'value'             => $instance['align_class'],
                'description_title' => $this->translate('Align'),
                'description_body'  => $this->translate('Only if div is selected.'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'html_element', 'data-parentstate' => 'div')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'pull-left'         => $this->translate('left'),
                    'pull-right'        => $this->translate('right'),
                ),
                'name'              => $this->getIdString('float'),
                'value'             => $instance['float'],
                'description_title' => $this->translate('Float direction'),
                'description_body'  => $this->translate('Only if span is selected.'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'html_element', 'data-parentstate' => 'span')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    ''                  => $this->translate('no link'),
                    'home_page'         => $this->translate('Home Page'),
                    'custom_link'       => $this->translate('Custom Link'),
                ),
                'name'              => $this->getIdString('link_to'),
                'value'             => $instance['link_to'],
                'description_title' => $this->translate('Link To'),
                'description_body'  => $this->translate('If custom link is selected it will use the url from the Custom Link field.'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'link_to')
            ));

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('custom_link'),
                'value'             => $instance['custom_link'],
                'description_title' => $this->translate('Custom Link'),
                'description_body'  => $this->translate('Please use absolute url (i.e. http://codex.wordpress.org/)'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'link_to', 'data-parentstate' => 'custom_link')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No'),
                ),
                'name'              => $this->getIdString('open_link_in_new_window'),
                'value'             => $instance['open_link_in_new_window'],
                'description_title' => $this->translate('Open Link in New Window?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'link_to', 'data-parentstate' => 'custom_link')
            ));
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
                'description_title' => $this->translate('Background Color'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_bg_color', 'data-parentstate' => '1')
            ));
            CbpWidgetFormElements::colorPicker(array(
                'name'              => $this->getIdString('bg_color_hover'),
                'value'             => $instance['bg_color_hover'],
                'description_title' => $this->translate('Background Hover Color'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_bg_color', 'data-parentstate' => '1')
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'                    => 'cbp_widget_icon',
                        'html_element'            => 'div',
                        'bg_color'                => '',
                        'use_bg_color'            => 'false',
                        'icon'                    => '',
                        'icon_size'               => '',
                        'css_class'               => '',
                        'custom_css_classes'      => '',
                        'align_class'             => 'align-center',
                        'css_class'               => '',
                        'link_to'                 => '',
                        'custom_link'             => '',
                        'open_link_in_new_window' => '0',
                        'padding'                 => 'double-padded',
                        'float'                   => '',
                        'use_bg_color'            => '0',
                        'bg_color'                => '',
                        'bg_color_hover'          => '',
                            ), $atts));
            $css_class                = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes       = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding                  = CbpWidgets::getCssClass($padding);
            $id                       = uniqid($this->getPrefix() . '-icon-widget-');
            ?>
            <?php if (!empty($icon)): ?>

                <?php if ($html_element == 'div'): ?>
                    <?php $class = 'class="' . CbpWidgets::getDefaultWidgetCssClass() . ' ' . $type . $css_class . $custom_css_classes . ' ' . $align_class . ' ' . $padding . '"'; ?>
                <?php else: ?>
                    <?php $class = 'class="' . CbpWidgets::getDefaultWidgetCssClass() . ' ' . $type . $css_class . $custom_css_classes . ' ' . $padding . ' ' . $float . '"'; ?>
                <?php endif; ?>

                <<?php echo $html_element; ?> <?php echo $class; ?>>

                <?php if (!empty($link_to)): ?>
                    <?php if ($link_to == 'home_page'): ?>
                        <?php $href = home_url(); ?>
                    <?php elseif ($link_to == 'custom_link'): ?>
                        <?php $href            = !empty($custom_link) ? $custom_link : '#'; ?>
                    <?php endif; ?>
                    <?php $openInNewWindow = (int) $open_link_in_new_window ? 'target="_blank"' : ''; ?>
                    <a <?php echo $openInNewWindow; ?> href="<?php echo $href; ?>">
                        <i id="<?php echo $id; ?>" class="fa <?php echo $icon; ?> <?php echo $icon_size; ?>"></i>
                    </a>
                <?php else: ?>
                    <i id="<?php echo $id; ?>" class="fa <?php echo $icon; ?> <?php echo $icon_size; ?>"></i>
                <?php endif; ?>

                </<?php echo $html_element; ?>>
            <?php endif; ?>

            <?php if ((int) $use_bg_color): ?>
                <style type="text/css">
                    #<?php echo $id; ?> { 
                        color: <?php echo $bg_color; ?>; 
                        -webkit-transition-property: color;
                        -moz-transition-property: color;
                        -o-transition-property: color;
                        transition-property: color;
                        -webkit-transition-duration: 0.3s;
                        -moz-transition-duration: 0.3s;
                        -o-transition-duration: 0.3s;
                        transition-duration: 0.3s;
                    }
                    #<?php echo $id; ?>:hover { 
                        color: <?php echo $bg_color_hover; ?>;
                        -webkit-transition-property: color;
                        -moz-transition-property: color;
                        -o-transition-property: color;
                        transition-property: color;
                        -webkit-transition-duration: 0.3s;
                        -moz-transition-duration: 0.3s;
                        -o-transition-duration: 0.3s;
                        transition-duration: 0.3s;
                    }
                </style>
            <?php endif; ?>
            <?php
        }
    }

    

    

    

    

    

    

    
endif;
