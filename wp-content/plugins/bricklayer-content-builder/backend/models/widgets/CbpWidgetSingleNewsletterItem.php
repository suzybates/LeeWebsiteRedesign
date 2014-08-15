<?php
if (!class_exists('CbpWidgetSingleNewsletterItem')):

    class CbpWidgetSingleNewsletterItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_single_newsletter_item',
                    /* Name */ 'Newsletter Item Post', array('description' => 'This is a Single Newsletter Item brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['post_id']    = null;
            $elements['title']      = '';
            $elements['title_size'] = 'h4';

            $elements['show_post_date']      = '0';
            $elements['show_post_date_icon'] = '0';
            $elements['post_date_format']    = 'M j, Y';

            $elements['show_comment_count'] = '0';
            $elements['show_comment_icon']  = '0';

            $elements['show_tags']      = '0';
            $elements['tags_is_link']   = '0';
            $elements['show_tags_icon'] = '0';

            $elements['show_author']      = '0';
            $elements['author_is_link']   = '0';
            $elements['show_author_icon'] = '0';

            $elements['show_featured_image']  = '0';
            $elements['thumbnail_dimensions'] = 'thumbnail';

            $elements['link_text']            = 'read more';
            $elements['number_of_characters'] = 1000;
            $elements['title_link_to_post']   = '1';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::selectNewsletterPost(array(
                'name'              => $this->getIdString('post_id'),
                'value'             => $instance['post_id'],
                'description_title' => $this->translate('Select Post'),
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

        }

        public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'title':
                case CBP_APP_PREFIX . 'link_text':
                    $attribute['value'] = sanitize_text_field($attribute['value']);
                    break;
                case CBP_APP_PREFIX . 'number_of_characters':
                    if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                        $attribute['value'] = 1000;
                    }
                    break;
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
            			'post_type' => 'newsletter_item',
                        'type'                 => '',
                        'css_class'            => '',
                        'custom_css_classes'   => '',
                        'post_id'              => 1,
                        'title'                => '',
                        'title_size'           => 'h4',
                        'title_link_to_post'   => '1',
                        'show_featured_image'  => '0',
                        'thumbnail_dimensions' => 'thumbnail',
                        'padding'              => 'no-padding',
                        'display_description'  => ''
                            ), $atts));
                            
			$args = array(
                        'post_type'   => 'newsletter_item',
                        'numberposts' => $number_of_posts,
                    );
            $post               = get_post($post_id);
            $pod                = 'newsletter_item';
            $id                 = $post->ID;
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $newsletter_description = pods_field ( $pod, $id, 'newsletter_longer_description', true );  
            $pod_id = pods($pod, $id);
			$volunteer_item = $pod_id->field('related_volunteer_spot');

            
            if (empty($display_description)) {
            	$display_description =  $post_id;
            }
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <<?php echo $title_size; ?>>
                <?php if ((int) $title_link_to_post): ?>
                    <a href="<?php echo get_permalink($post->ID); ?>"><?php echo!empty($title) ? $title : $post->post_title; ?></a>
                <?php else: ?>
                    <<?php echo $title_size; ?>><?php echo!empty($title) ? $title : $post->post_title; ?>
                <?php endif; ?>
                </<?php echo $title_size; ?>>
                <?php if ((int) $show_featured_image): ?> 

                    <?php $imageArgs = array('echo' => false, 'size' => $thumbnail_dimensions); ?>
                    <?php $image = cbp_get_the_image($imageArgs); ?>
                    <?php if (isset($image) && $image): ?>
                        <div class="<?php echo $this->getPrefix(); ?>-widget-post-image">
                            <?php echo $image; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div><?php echo $newsletter_description ?></div>
                <div class="<?php echo $this->getPrefix(); ?>-widget-post-content">
                    <?php //echo $post->post_content; ?>
                    
                </div>
                <?php //var_dump($volunteer_item); ?>
                <?php if (!empty($volunteer_item)): ?>
				<?php foreach ($volunteer_item as $vol) { ?>
					<?php $vol_id = $vol["ID"]; ?>
					<?php $volunteer_spot_link = get_post_meta( $vol_id, 'volunteer_spot_link', true ); ?>
					<?php $volunteer_spot_title = get_the_title($vol_id); ?>
					<div>Volunteer: <?php echo $volunteer_spot_title; ?>- <a href="<?php echo $volunteer_spot_link;?>"><?php echo $volunteer_spot_link; ?></div>
				
				<?php } ?>
			<?php endif; ?>

            </div>

            <?php
        }
    }

    

    

endif;
