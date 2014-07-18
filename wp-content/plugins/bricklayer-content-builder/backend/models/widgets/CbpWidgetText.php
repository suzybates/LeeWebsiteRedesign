<?php
if (!class_exists('CbpWidgetText')):

    class CbpWidgetText extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_text',
                    /* Name */ 'Text', array('description' => 'This is a Text brick', 'icon'        => 'fa fa-font fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['title']                    = '';
            $elements['content']                  = '';
            $elements['use_bg_color']             = '0';
            $elements['bg_color']                 = '';
            $elements['align_class']              = 'align-left';
            $elements['title_size']               = 'h2';
            $elements['title_link_to']            = '';
            $elements['title_open_in_new_window'] = '0';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('title'),
                'value'             => $instance['title'],
                'description_title' => $this->translate('Title'),
            ));

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

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('title_link_to'),
                'value'             => $instance['title_link_to'],
                'description_title' => $this->translate('Title Link To...'),
                'description_body'  => $this->translate('Should be entered as absolute url (e.i. http://www.google.com).'),
            ));
            
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('title_open_in_new_window'),
                'value'             => $instance['title_open_in_new_window'],
                'description_title' => $this->translate('Open in New Window?'),
            ));

            CbpWidgetFormElements::tinyMce(array(
                'name'              => $this->getIdString('content'),
                'value'             => $instance['content'],
                'description_title' => $this->translate('Content'),
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

            CbpWidgetFormElements::select(array(
                'options' => array(
                    'align-left'        => $this->translate('left'),
                    'align-center'      => $this->translate('center'),
                    'align-right'       => $this->translate('right'),
                    'justify'           => $this->translate('justify'),
                ),
                'name'              => $this->getIdString('align_class'),
                'value'             => $instance['align_class'],
                'description_title' => $this->translate('Align'),
            ));
        }

        public function widget($atts, $content)
        {
            $content = wpautop($content);

            extract(shortcode_atts(array(
                        'title'                    => '',
                        'bg_color'                 => '',
                        'type'                     => '',
                        'use_bg_color'             => '0',
                        'css_class'                => '',
                        'custom_css_classes'       => '',
                        'align_class'              => 'align-left',
                        'title_size'               => 'h2',
                        'title_link_to'            => '',
                        'title_open_in_new_window' => '0',
                        'padding'                  => '',
                            ), $atts));
            $this->resetInlineStyles();
            if ((int) $use_bg_color) {
                $this->setInlineStyle('background-color', $bg_color);
            }
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $targetBlank        = $title_open_in_new_window ? 'target="_blank"' : '';
            $aTagOpen           = !empty($title_link_to) ? '<a href="' . $title_link_to . '" ' . $targetBlank . '>' : '';
            $aTagClose          = !empty($title_link_to) ? '</a>' : '';
            ?>  

            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $align_class; ?> <?php echo $padding; ?>" <?php echo $this->getInlineStyles(); ?>>
                <?php if ($title): ?>
                    <?php echo $aTagOpen; ?>
                    <<?php echo $title_size; ?>><?php echo trim($title); ?></<?php echo $title_size; ?>>
                    <?php echo $aTagClose; ?>
                <?php endif; ?>
                <?php echo $content; ?>
            </div> 
            <?php
        }
    }

    

    

    
endif;
