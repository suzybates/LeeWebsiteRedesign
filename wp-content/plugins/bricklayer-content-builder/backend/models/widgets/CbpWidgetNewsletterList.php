<?php
if (!class_exists('CbpWidgetNewsletterList')):

    class CbpWidgetNewsletterList extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_newsletter_list',
                    /* Name */ 'Newsletter List', array('description' => 'This is a Newsletter List brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['title']              = '';
            $elements['title_size']         = 'h2';
            $elements['title_link_to_post'] = '1';
            
            $elements['order_by'] = 'date';
            $elements['order']    = 'DESC';

            $elements['use_pagination'] = '0';
            $elements['posts_per_page'] = 10;

            $elements['show_post_date']      = '1';
            $elements['show_post_date_icon'] = '1';
            $elements['post_date_format']    = 'M j, Y';

            $elements['show_featured_image']  = '1';
            $elements['thumbnail_dimensions'] = 'thumbnail';

            $elements['number_of_columns']    = 'one whole';
            $elements['number_of_characters'] = 200;

            $elements['use_button_link'] = '1';
            $elements['link_text']       = 'read more';
            
            $elements['limit_by_newsletter_item_dates']       =  '0';

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
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('title_link_to_post'),
                'value'             => $instance['title_link_to_post'],
                'description_title' => $this->translate('Should Title Link to Newsletter?'),
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
                'name'              => $this->getIdString('post_title_size'),
                'value'             => $instance['post_title_size'],
                'description_title' => $this->translate('Newsletter Title Size'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'date'              => $this->translate('Date'),
                    'title'             => $this->translate('Title'),
                    'title'             => $this->translate('Title'),
                ),
                'name'              => $this->getIdString('order_by'),
                'value'             => $instance['order_by'],
                'description_title' => $this->translate('Order By'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'ASC'               => $this->translate('Ascending'),
                    'DESC'              => $this->translate('Descending'),
                ),
                'name'              => $this->getIdString('order'),
                'value'             => $instance['order'],
                'description_title' => $this->translate('Order'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_pagination'),
                'value'             => $instance['use_pagination'],
                'description_title' => $this->translate('Use Pagination?'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('posts_per_page'),
                'value'             => $instance['posts_per_page'],
                'description_title' => $this->translate('Newsletters Per Page'),
                'description_body'  => $this->translate('Enter number.'),
            ));
            
            
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'one whole'         => 1,
                    'one half'          => 2,
                    'one third'         => 3,
                    'one fourth'        => 4,
                ),
                'name'              => $this->getIdString('number_of_columns'),
                'value'             => $instance['number_of_columns'],
                'description_title' => $this->translate('Number Of Columns'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('number_of_characters'),
                'value'             => $instance['number_of_characters'],
                'description_title' => $this->translate('Number Of Characters'),
                'description_body'  => $this->translate('Enter number.'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No'),
                ),
                'name'              => $this->getIdString('use_button_link'),
                'value'             => $instance['use_button_link'],
                'description_title' => $this->translate('Use Button Link?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_button_link')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('link_text'),
                'value'             => $instance['link_text'],
                'description_title' => $this->translate('Link Text'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_button_link', 'data-parentstate' => '1')
            ));
            
        }

       public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'title':
                case CBP_APP_PREFIX . 'link_text':
                    $attribute['value'] = sanitize_text_field($attribute['value']);
                    break;
                case CBP_APP_PREFIX . 'posts_per_page':
                    if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                        $attribute['value'] = 10;
                    }
                    break;
                case CBP_APP_PREFIX . 'number_of_characters':
                    if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                        $attribute['value'] = 200;
                    }
                    break;
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {

            extract(shortcode_atts(array(
                        'type'                 => '',
                        'custom_css_classes'   => '',
                        'css_class'            => '',
                        'title'                => '',
                        'title_size'           => 'h2',
                        'title_link_to_post'   => '1',
                        'post_title_size'      => 'h3',
                        'order_by'             => 'newsletter_date',
                        'order'                => 'DESC',
                        'use_pagination'       => '0',
                        'posts_per_page'       => 10,
                        'number_of_columns'    => 'one whole',
                        'number_of_characters' => 200,
                        'use_button_link'      => '1',
                        'link_text'            => 'read more',
                        'padding'              => '',
                        'limit_by_newsletter_item_dates' => '0',
                            ), $atts));

            global $paged;
            global $post;
            $params = array(
    			'limit' => $posts_per_page, 
    			// Be sure to sanitize ANY strings going here
    			'where'=>"approved_for_publication.meta_value = 'Approved'" ,
    			'paged'             => $paged,
                'orderby'           => 'newsletter_date '. $order
			);
           
            $mypod = pods('newsletter', $params);
            
            //echo $mypod->display('post_date');
            
            /*query_posts(array(
                'post_type' => 'newsletter',
                //'posts_per_page'    => $posts_per_page,
                'paged'             => $paged,
                'orderby'           => $order_by,
                'order'             => $order
            ));
			*/            
            
            $padding            = CbpWidgets::getCssClass($padding);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            
            ?>
            
                <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                    <?php if (!empty($title)): ?>
                        <<?php echo $title_size; ?>><?php echo $title; ?></<?php echo $title_size; ?>>
                    <?php endif; ?>
                    <?php while ($mypod->fetch() ) : ?>
                        <div class="<?php echo $number_of_columns; ?> double-pad-right double-pad-bottom <?php echo $this->getPrefix(); ?>-widget-post-list-item">
                            <?php //var_dump( $mypod) ?>
                            <<?php echo $post_title_size; ?>>
                            	<a href="<?php echo $mypod->display('permalink'); ?>">
                            	<?php echo $mypod->display('post_title'); ?></a>
                            </<?php echo $post_title_size; ?>>
                        </div>
                    <?php //endwhile; ?>
                    <?php endwhile; ?>
                </div>
            
            <?php //wp_reset_query(); ?>
            <?php //if ((int) $use_pagination): ?> 
                <?php //CbpUtils::pagination(); ?>
            <?php //endif; ?>
            <?php
        }
    }
  
endif;

//    	}
//    }
  
//endif;
//?>
