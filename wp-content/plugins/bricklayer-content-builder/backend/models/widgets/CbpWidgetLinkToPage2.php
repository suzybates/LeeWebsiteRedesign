<?php
if (!class_exists('CbpWidgetLinkToPage2')):

    class CbpWidgetLinkToPage2 extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_link_to_page',
                    /* Name */ 'Link to Page2', array('description' => 'This is a Link to Page2 brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['page_id']            = null;
            $elements['title']              = '';
            $elements['title_size']         = 'h2';
            $elements['title_link_to_post'] = '1';

            $elements['show_featured_image']  = '0';
            $elements['thumbnail_dimensions'] = 'thumbnail';
            $elements['show_link_button']     = '0';
            $elements['link_text']            = 'read more';
            $elements['number_of_characters'] = 200;

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::selectPage(array(
                'name'              => $this->getIdString('page_id'),
                'value'             => $instance['page_id'],
                'description_title' => $this->translate('Select Page'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('title'),
                'value'             => $instance['title'],
                'description_title' => $this->translate('Custom Title'),
                'description_body'  => $this->translate('If this is not set post title will be used.'),
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
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('title_link_to_post'),
                'value'             => $instance['title_link_to_post'],
                'description_title' => $this->translate('Should Title Link to Post?'),
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
                'name'              => $this->getIdString('show_featured_image'),
                'value'             => $instance['show_featured_image'],
                'description_title' => $this->translate('Show Featured Image?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_featured_image')
            ));
            CbpWidgetFormElements::selectRegiseredImageSizes(array(
                'name'              => $this->getIdString('thumbnail_dimensions'),
                'value'             => $instance['thumbnail_dimensions'],
                'description_title' => $this->translate('Featured Image Dimensions'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'show_featured_image', 'data-parentstate' => '1')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_link_button'),
                'value'             => $instance['show_link_button'],
                'description_title' => $this->translate('Show Link Button?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'show_link_button')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('link_text'),
                'value'             => $instance['link_text'],
                'description_title' => $this->translate('Link Text'),
            ));
        }

        public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'title':
                case CBP_APP_PREFIX . 'link_text':
                    $attribute['value'] = sanitize_text_field($attribute['value']);
                    break;
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'                 => '',
                        'css_class'            => '',
                        'custom_css_classes'   => '',
                        'page_id'              => 1,
                        'title'                => '',
                        'title_size'           => 'h2',
                        'title_link_to_post'   => '1',
                        'show_featured_image'  => '0',
                        'thumbnail_dimensions' => 'thumbnail',
                        'number_of_characters' => 200,
                    	'show_link_button'     => '0',
                        'link_text'            => 'read more',
                        'padding'              => '',
                            ), $atts));

            global $post;
            $page               = get_post($page_id);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <<?php echo $title_size; ?>>
                <?php if ((int) $title_link_to_post): ?>
                    <a href="<?php echo get_permalink($page->ID); ?>"><?php echo!empty($title) ? $title : $page->post_title; ?></a>
                <?php else: ?>
                    <<?php echo $title_size; ?>><?php echo!empty($title) ? $title : $page->post_title; ?>
                <?php endif; ?>
                </<?php echo $title_size; ?>>
                <?php if ((int) $show_featured_image): ?> 

                    <?php $imageArgs = array('echo'    => false, 'size'    => $thumbnail_dimensions, 'post_id' => $page->ID); ?>
                    <?php $image    = cbp_get_the_image($imageArgs); ?>

                    <?php if (isset($image) && $image): ?>
                        <div class="<?php echo $this->getPrefix(); ?>-widget-page-image">
                            <?php echo $image; ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
                <div class="<?php echo $this->getPrefix(); ?>-widget-post-content">
                    <?php echo $content; ?>
                </div>
                <?php if ((int) $show_link_button): ?> 
                	<div class="<?php echo $this->getPrefix(); ?>-widget-page-link double-pad-top">
                    	<a class="cbp_widget_link" href="<?php echo get_permalink($page->ID); ?>"><?php echo $link_text; ?></a>
                	</div>
                <?php endif; ?>
            </div>

            <?php
        }
    }

    

    

    
endif;
