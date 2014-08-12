<?php
if (!class_exists('CbpWidgetAISDMenu')):

    class CbpWidgetAISDMenu extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_AISD_menu',
                    /* Name */ 'AISD Menu Month', array('description' => 'This is a Month of AISD brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['post_id']    = null;
            $elements['title']      = '';
            $elements['title_size'] = 'h2';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

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
        }

        public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'title':
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'title'                => '',
                        'title_size'           => 'h2',
                        'title_link_to_post'   => '1',
                        'padding'              => 'no-padding',
                            ), $atts));
                            
			$args = array(
                        'post_type'   => 'newsletter_item',
                        'numberposts' => $number_of_posts,
                    );
            $post               = get_post($post_id);
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            
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
                
                <?php $userGuid = "61f82d28-c9f3-4bec-be8f-cd1cc0b7cab7"; ?>
				<?php $apiKey = "AccmyJLuXl4XR8V1/ubdW+B4Bwtfr6cxuHDKNNI/jXFHPFqQI2Q6RxMtTQ4TK59Vyu1zfoN/YTeU6Me8l4+oyg=="; ?>

				<?php $menu_array = aisd_menu_query("babd048c-5a05-47ee-ba41-582923a73ef6", array(
				  "webpage/url" => "http://www.austinisd.org/nutritionfoodservices/menu-information?level=elementary&language=English",
					), $userGuid, $apiKey, false); ?>
					<?php //echo var_dump($menu_array->results[3]); ?>
				<table>
				<?php foreach ($menu_array->results as $menu_item):  ?>
							<tr>
								<td><?php echo $menu_item->day; ?> </td>
							   <td><?php echo $menu_item->menu_item; ?></td>
							</tr>
    				
					<?php endforeach; ?>
				</table>
            </div>

            <?php
        }
    }

    

    

endif;
