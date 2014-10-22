<?php
if (!class_exists('CbpWidgetEventList')):

    class CbpWidgetEventList extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_event_list',
                    /* Name */ 'Event List', array('description' => 'This is an Event List brick.', 'icon'        => 'fa fa-list-alt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['post_id']    = null;
            $elements['title']      = '';
            $elements['title_size'] = 'h2';
            $elements['start_date'] = '';
            $elements['end_date']   = '';

            $elements['show_author']      = '1';
            $elements['author_is_link']   = '1';
            $elements['show_author_icon'] = '1';
 
            $elements['limit'] 				= '10';
            $elements['notcategory'] 		= '0';
            $elements['category'] 			= '0';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('limit'),
                'value'             => $instance['limit'],
                'description_title' => $this->translate('Set number of events'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('start_date'),
                'value'             => $instance['start_date'],
                'description_title' => $this->translate('Set Start Date'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('end_date'),
                'value'             => $instance['end_date'],
                'description_title' => $this->translate('Set End Date'),
            ));
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
            CbpWidgetFormElements::selectEventCategory(array(
                'name'              => $this->getIdString('notcategory'),
                'value'             => $instance['notcategory'],
                'description_title' => $this->translate('Select Event Categories to Exclude'),
            ));
            CbpWidgetFormElements::selectEventCategory(array(
                'name'              => $this->getIdString('category'),
                'value'             => $instance['category'],
                'description_title' => $this->translate('Select Event Categories to Include'),
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
            			'limit' 				=> '10', 
            			'scope' 				=> 'future', 
            			'order' 				=> 'ASC', 
            			'format' 				=> '', 
            			'echo' 					=> 1 , 
            			'category' 				=> '0', 
            			'showperiod' 			=> '', 
            			'author' 				=> '', 
            			'contact_person' 		=> '', 
            			'paging' 				=> 0, 
            			'long_events' 			=> 0, 
            			'location_id' 			=> 0, 
            			'user_registered_only' 	=> 0, 
            			'show_ongoing' 			=> 1, 
            			'link_showperiod' 		=> 0, 
            			'notcategory' 			=> '0', 
            			'show_recurrent_events_once' => 0, 
            			'template_id' 			=> 1, 
            			'template_id_header' 	=> 0, 
            			'template_id_footer' 	=> 0,             			
                        'type'                 => '',
                        'css_class'            => '',
                        'custom_css_classes'   => '',
                        'title'                => '',
                        'title_size'           => 'h2',
                        'title_link_to_post'   => '1',
                        'start_date'           => '',
                        'end_date'             => '',
                        'padding'              => 'no padding'
                            ), $atts));  
                            
                $startDateFormat = date_format(date_create($start_date), 'Y-m-d') ;
                $endDateFormat = date_format(date_create($end_date), 'Y-m-d');
                if (!empty($start_date) && !empty($end_date)) : {
                		$scope = join(array($startDateFormat, '-', $endDateFormat)); }
                elseif (!empty($start_date) ): {
                    	$scope = join(array($startDateFormat, '-', 'future')); }
                elseif (!empty($end_date) ): {
                    	$scope = join(array('past', '-', $endDateFormat)); }
            	endif;
            	
				  
?>
    		<div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                    <?php if (!empty($title)): ?>
                        <<?php echo $title_size; ?>><?php echo $title; ?></<?php echo $title_size; ?>>
                    <?php endif; ?>   
 			
    			<table>
    		       	<?php eme_get_events_list($limit, 
    		       			$scope, $order, $format, $echo, $category, 
    		       			$showperiod, $long_events, $author, $contact_person, 
    		       			$paging, $location_id, $user_registered_only, $show_ongoing, 
    		       			$link_showperiod, $notcategory, $show_recurrent_events_once, 
    		       			$template_id, $template_id_header, $template_id_footer); ?>
					
				</table>  
			</div>          
			<?php
        }
    }

    

    

endif;
