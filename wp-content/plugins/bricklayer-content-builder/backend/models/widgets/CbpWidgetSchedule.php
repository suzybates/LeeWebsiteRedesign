<?php
if (!class_exists('CbpWidgetSchedule')):

    class CbpWidgetSchedule extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_schedule',
                    /* Name */ 'Schedule', array('description' => 'This is an Schedule brick', 'icon'        => 'fa fa-align-right fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            $elements['content'] = '';
            $elements['title']   = '';
            $elements['title_size'] = 'h2';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('title'),
                'value'             => $instance['title'],
                'description_title' => $this->translate('Custom Title'),
                'description_body'  => $this->translate(''),
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

            parent::form($instance);

            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_schedule_item',
                'subwidget_id' => 'cbp_subwidget_schedule_item',
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_schedule',
                        'title'                => '',
                        'title_size'           => 'h2',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));
            $shortcodes          = CbpWidgets::parseRawShortcode($content);
            $padding             = CbpWidgets::getCssClass($padding);
            $css_class           = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes  = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            ?>

            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                
                	<?php if ((int) $title_link_to_post): ?>
                    	<<?php echo $title_size; ?>><a href="<?php echo get_permalink($page->ID); ?>"><?php echo!empty($title) ? $title : $page->post_title; ?></a></<?php echo $title_size; ?>>
                	<?php else: ?>
                    	<<?php echo $title_size; ?>><?php echo !empty($title) ? $title : $page->post_title; ?></<?php echo $title_size; ?>>
                	<?php endif; ?>
                
                
                <?php foreach ($shortcodes as $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_schedule_item'): ?>
                    	<h5>
							<span margin-bottom: 4px;><?php echo $shortcode['atts']['starttime']; ?> - <?php echo $shortcode['atts']['endtime']; ?></span>
							<span margin-left: 20px;><?php echo $shortcode['atts']['activity']; ?> </span>
						</h5>
						<?php $schedule_content=$shortcode['content']; ?>
						<?php if (!empty($schedule_content)) : ?>
							<div margin-left: 15px;><?php echo $schedule_content ?></div>
						<?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php
        }
    }

    

    
   
endif;
