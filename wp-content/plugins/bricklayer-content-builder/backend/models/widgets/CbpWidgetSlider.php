<?php
if (!class_exists('CbpWidgetSlider')):

    class CbpWidgetSlider extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_slider',
                    /* Name */ 'Slider', array('description' => 'This is a Slider brick', 'icon'        => 'fa fa-laptop fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            // these are left to be true and false because this is what is going to be printed as javascript true and false
            $elements['auto_play']       = 'false';
            $elements['show_bullets']    = 'true';
            $elements['show_controls']   = 'true';
            $elements['transition']      = 'horizontal';
            $elements['speed']           = '1000';
            $elements['adaptive_height'] = 'true';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'horizontal'        => $this->translate('horizontal'),
                    'vertical'          => $this->translate('vertical'),
                    'fade'              => $this->translate('fade'),
                ),
                'name'              => $this->getIdString('transition'),
                'value'             => $instance['transition'],
                'description_title' => $this->translate('Transition'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'true'              => $this->translate('Yes'),
                    'false'             => $this->translate('No')
                ),
                'name'              => $this->getIdString('auto_play'),
                'value'             => $instance['auto_play'],
                'description_title' => $this->translate('Auto Play?'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'true'              => $this->translate('Yes'),
                    'false'             => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_controls'),
                'value'             => $instance['show_controls'],
                'description_title' => $this->translate('Show Controls?'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'true'              => $this->translate('Yes'),
                    'false'             => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_bullets'),
                'value'             => $instance['show_bullets'],
                'description_title' => $this->translate('Show Bullets?'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'true'              => $this->translate('Yes'),
                    'false'             => $this->translate('No')
                ),
                'name'              => $this->getIdString('adaptive_height'),
                'value'             => $instance['adaptive_height'],
                'description_title' => $this->translate('Adaptive Height?'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('speed'),
                'value'             => $instance['speed'],
                'description_title' => $this->translate('Speed'),
                'description_body'  => $this->translate('Time between slides in miliseconds. Enter number only!'),
            ));
            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_slider_item',
                'subwidget_id' => 'cbp_subwidget_slider_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_slider',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'auto_play'          => 'false',
                        'show_bullets'       => 'true',
                        'show_controls'      => 'true',
                        'transition'         => 'horizontal',
                        'speed'              => '1000',
                        'adaptive_height'    => 'true',
                        'padding'            => '',
                            ), $atts));


            $shortcodes         = CbpWidgets::parseRawShortcode($content);
            $id                 = uniqid($this->getPrefix() . '-slider-');
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>
            <ul id="<?php echo $id; ?>" class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php foreach ($shortcodes as $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_slider_item'): ?>
                        <li>
                            <?php if ($shortcode['atts']['item_type'] == 'video' && !empty($shortcode['atts']['video_id'])): ?>
                                <?php $videoWidth  = !empty($shortcode['atts']['video_width']) ? 'width="' . $shortcode['atts']['video_width'] . '"' : ''; ?>
                                <?php $videoHeight = !empty($shortcode['atts']['video_height']) ? 'height="' . $shortcode['atts']['video_height'] . '"' : ''; ?>

                                <?php if ($shortcode['atts']['video_type'] == 'youtube'): ?>
                                    <iframe <?php echo $videoWidth; ?> <?php echo $videoHeight; ?> src="//www.youtube.com/embed/<?php echo $shortcode['atts']['video_id']; ?>" frameborder="0" allowfullscreen></iframe>
                                <?php endif; ?>
                                <?php if ($shortcode['atts']['video_type'] == 'vimeo'): ?>
                                    <iframe <?php echo $videoWidth; ?> <?php echo $videoHeight; ?> src="//player.vimeo.com/video/<?php echo $shortcode['atts']['video_id']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> 
                                <?php endif; ?>

                            <?php elseif ($shortcode['atts']['item_type'] == 'image' && !empty($shortcode['atts']['img_src'])): ?>
                                <?php $image = CbpWidgets::parseImageDetails($shortcode['atts']['img_src']); ?>
                                <img src="<?php echo $image['selected_src']; ?>" alt="<?php echo $shortcode['atts']['name']; ?>" title="<?php echo strip_tags($shortcode['content'], '<a><b><h1><h2><h3><h4><h5><h6>'); ?>"/>
                            <?php elseif ($shortcode['atts']['item_type'] == 'text'): ?>
                                <?php echo do_shortcode($shortcode['content']); ?>
                            <?php endif; ?>
                        </li>

                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <script>
                
                var cbp_content_builder = cbp_content_builder || {};
                cbp_content_builder.data = cbp_content_builder.data || {};
                
                if (!cbp_content_builder.data.sliders) {
                    cbp_content_builder.data.sliders = [];
                }
                                                                                                                                                                                                                                                                                
                cbp_content_builder.data.sliders.push({
                    id: '<?php echo $id; ?>',
                    options: {
                        mode: '<?php echo $transition; ?>', 
                        adaptiveHeight: <?php echo $adaptive_height; ?>,
                        video: true,
                        captions: true,
                        auto: <?php echo $auto_play; ?>,
                        controls: <?php echo $show_controls; ?>,
                        pager: <?php echo $show_bullets; ?>,
                        speed: <?php echo $speed; ?>
                    }
                });      
                                                                                                                                                                                                                                                                                
            </script>
            <?php
        }
    }

    

    

    

    

    
endif;
