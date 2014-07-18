<?php
if (!class_exists('CbpWidgetVideo')):

    class CbpWidgetVideo extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_video',
                    /* Name */ 'Video', array('description' => 'This is a Video brick', 'icon'        => 'fa fa-film fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['video_type']   = '';
            $elements['video_id']     = '';
            $elements['video_width']  = '';
            $elements['video_height'] = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'youtube'           => $this->translate('Youtube'),
                    'vimeo'             => $this->translate('Vimeo'),
                ),
                'name'              => $this->getIdString('video_type'),
                'value'             => $instance['video_type'],
                'description_title' => $this->translate('Video Type'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_id'),
                'value'             => $instance['video_id'],
                'description_title' => $this->translate('Video Id'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_width'),
                'value'             => $instance['video_width'],
                'description_title' => $this->translate('Video Width'),
                'description_body'  => $this->translate('Set only if you need exact dimensions.'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_height'),
                'value'             => $instance['video_height'],
                'description_title' => $this->translate('Video Height'),
                'description_body'  => $this->translate('Set only if you need exact dimensions.'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_video',
                        'custom_css_classes' => '',
                        'css_class'          => '',
                        'video_type'         => '',
                        'video_id'           => '',
                        'video_width'        => '',
                        'video_height'       => '',
                        'padding'            => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>

            <?php $videoWidth         = !empty($video_width) ? 'width="' . $video_width . '"' : ''; ?>
            <?php $videoHeight        = !empty($video_height) ? 'height="' . $video_height . '"' : ''; ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">

                <?php if ($video_type == 'youtube'): ?>
                    <iframe <?php echo $videoWidth; ?> <?php echo $videoHeight; ?> src="//www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allowfullscreen></iframe>
                <?php endif; ?>
                <?php if ($video_type == 'vimeo'): ?>
                    <iframe <?php echo $videoWidth; ?> <?php echo $videoHeight; ?> src="//player.vimeo.com/video/<?php echo $video_id; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> 
                <?php endif; ?>
            </div>
            <?php
        }
    }

    

    

    
endif;
