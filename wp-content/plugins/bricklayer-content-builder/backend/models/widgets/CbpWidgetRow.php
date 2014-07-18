<?php
if (!class_exists('CbpWidgetRow')):

    class CbpWidgetRow extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_row',
                    /* Name */ 'Row', array('description' => 'This is a Row brick', 'icon'        => 'fa fa-font fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['fullwidth']    = '0';
            $elements['use_bg_color'] = '0';
            $elements['bg_color']     = '';
            $elements['bg_image']     = '';
            $elements['use_parallax'] = '0';
            $elements['speed_factor'] = '0.5';
            $elements['wrap']         = '1';

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
                'name'              => $this->getIdString('fullwidth'),
                'value'             => $instance['fullwidth'],
                'description_title' => $this->translate('Full Width?'),
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
                'description_title' => $this->translate('Background color'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_bg_color', 'data-parentstate' => '1')
            ));

            CbpWidgetFormElements::mediaUpload(array(
                'name'              => $this->getIdString('bg_image'),
                'value'             => $instance['bg_image'],
                'description_title' => $this->translate('Background Image'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_parallax'),
                'value'             => $instance['use_parallax'],
                'description_title' => $this->translate('Use Parallax Effect?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_parallax')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('1'),
                    '0.9'               => $this->translate('0.9'),
                    '0.8'               => $this->translate('0.8'),
                    '0.7'               => $this->translate('0.7'),
                    '0.6'               => $this->translate('0.6'),
                    '0.5'               => $this->translate('0.5'),
                    '0.4'               => $this->translate('0.4'),
                    '0.3'               => $this->translate('0.3'),
                    '0.2'               => $this->translate('0.2'),
                    '0.1'               => $this->translate('0.1'),
                ),
                'name'              => $this->getIdString('speed_factor'),
                'value'             => $instance['speed_factor'],
                'description_title' => $this->translate('Speed Factor'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_parallax', 'data-parentstate' => '1')
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('wrap'),
                'value'             => $instance['wrap'],
                'description_title' => $this->translate('Wrap?'),
                'description_body'  => $this->translate('If not wrapped it will add nothing. This is especially good for use when creating layout "content" widget.'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'bg_color'           => '',
                        'bg_image'           => '',
                        'fullwidth'          => '0',
                        'type'               => 'cbp_widget_row',
                        'use_bg_color'       => '0',
                        'wrap'               => '1',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'use_parallax'       => '0',
                        'speed_factor'       => '0.5',
                        'padding'            => '',
                            ), $atts));


            $this->resetInlineStyles();
            if ((int) ($use_bg_color)) {
                $this->setInlineStyle('background-color', $bg_color);
            }
            if (!empty($bg_image)) {
                $image              = CbpWidgets::parseImageDetails($bg_image);
                $this->setInlineStyle('background-image', "url('" . $image['selected_src'] . "')");
            }
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $id                 = uniqid($this->getPrefix() . '-row-');

            if ((int) $use_parallax) {
                $css_class .= ' ' . CBP_APP_PREFIX . 'row_fixed_bg';
            }

            $cssClasses = CbpWidgets::getMappedCssClasses();
            ?>
            <?php if ((int) $wrap): ?>
                <?php if ((int) $fullwidth): ?>
                    <div <?php echo $use_parallax ? 'id="' . $id . '"' : ''; ?> class="<?php echo CbpWidgets::getCssClass('row'); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>" <?php echo $this->getInlineStyles(); ?>>
                        <div class="<?php echo CbpWidgets::getCssClass('container'); ?>"><?php echo $content; ?></div>
                    </div>
                <?php else: ?>
                    <div <?php echo (int) $use_parallax ? 'id="' . $id . '"' : ''; ?> class="<?php echo CbpWidgets::getCssClass('container'); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>" <?php echo $this->getInlineStyles(); ?>>
                        <div class="<?php echo CbpWidgets::getCssClass('row'); ?>"><?php echo $content; ?></div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php echo $content; ?>
            <?php endif; ?>


            <?php if ((int) $use_parallax): ?> 
                <script>
                    
                    var cbp_content_builder = cbp_content_builder || {};
                    cbp_content_builder.data = cbp_content_builder.data || {};
                    
                    if (!cbp_content_builder.data.parallaxItems) {
                        cbp_content_builder.data.parallaxItems = [];
                    }
                                                                                                                                                                                                                                                                                                                
                    cbp_content_builder.data.parallaxItems.push({
                        id: '<?php echo $id; ?>',
                        speedFactor: '<?php echo $speed_factor; ?>'
                    });      
                                                                                                                                                                                                                                                                                                                
                </script>

            <?php endif; ?>

            <?php
        }
    }

    

    

    
endif;
