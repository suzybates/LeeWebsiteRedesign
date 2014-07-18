<?php
if (!class_exists('CbpWidgetImage')):

    class CbpWidgetImage extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_image',
                    /* Name */ 'Image', array('description' => 'This is an Image brick', 'icon'        => 'fa fa-picture-o fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['img_src']                 = '';
            $elements['alt_text']                = 'image';
            $elements['link_to']                 = '';
            $elements['custom_link']             = '';
            $elements['open_link_in_new_window'] = '0';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::mediaUpload(array(
                'name'              => $this->getIdString('img_src'),
                'value'             => $instance['img_src'],
                'description_title' => $this->translate('Image'),
            ));

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('alt_text'),
                'value'             => $instance['alt_text'],
                'description_title' => $this->translate('Alt Text'),
                'description_body'  => $this->translate('This is important for screen readers.'),
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
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'                    => '',
                        'custom_css_classes'      => '',
                        'css_class'               => '',
                        'img_src'                 => '',
                        'alt_text'                => 'image',
                        'link_to'                 => '',
                        'custom_link'             => '',
                        'open_link_in_new_window' => '0',
                        'padding'                 => '',
                            ), $atts));
            $css_class                = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes       = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $image                    = CbpWidgets::parseImageDetails($img_src);
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">

                <?php if (!empty($link_to)): ?>
                    <?php if ($link_to == 'home_page'): ?>
                        <?php $href = home_url(); ?>
                    <?php elseif ($link_to == 'custom_link'): ?>
                        <?php $href            = !empty($custom_link) ? $custom_link : '#'; ?>
                    <?php endif; ?>
                    <?php $openInNewWindow = (int) $open_link_in_new_window ? 'target="_blank"' : ''; ?>
                    <a <?php echo $openInNewWindow; ?> href="<?php echo $href; ?>">
                        <img src="<?php echo $image['selected_src']; ?>" alt="<?php echo $alt_text; ?>" />
                    </a>
                <?php else: ?>
                    <img src="<?php echo $image['selected_src']; ?>" alt="<?php echo $alt_text; ?>" />
                <?php endif; ?>
            </div>

            <?php
        }
    }

    

    

    

    
endif;
