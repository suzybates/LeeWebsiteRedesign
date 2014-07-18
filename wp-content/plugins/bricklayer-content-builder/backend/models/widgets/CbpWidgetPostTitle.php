<?php
if (!class_exists('CbpWidgetPostTitle')):

    class CbpWidgetPostTitle extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_post_title',
                    /* Name */ 'Post Title', array('description' => 'This is a Post Title brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements = parent::registerFormElements($elements);

            $elements['title_size']    = 'h2';
            $elements['title_is_link'] = '1';

            return $elements;
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'h1'                => $this->translate('H1'),
                    'h2'                => $this->translate('H2'),
                    'h3'                => $this->translate('H3'),
                    'h4'                => $this->translate('H4'),
                    'h5'                => $this->translate('H5'),
                    'h6'                => $this->translate('H6'),
                ),
                'name'              => $this->getIdString('title_size'),
                'value'             => $instance['title_size'],
                'description_title' => $this->translate('Title Size'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('title_is_link'),
                'value'             => $instance['title_is_link'],
                'description_title' => $this->translate('Link to post?'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_post_title',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                        'title_size'         => 'h2',
                        'title_is_link'      => '1',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $aTagOpen           = (int) $title_is_link ? '<a href="' . get_permalink() . '">' : '';
            $aTagClose          = (int) $title_is_link ? '</a>' : '';
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php echo $aTagOpen; ?>
                <<?php echo $title_size; ?>><?php the_title(); ?></<?php echo $title_size; ?>>
                <?php echo $aTagClose; ?>
            </div>
            <?php
        }
    }

    

    

endif;
