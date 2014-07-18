<?php
if (!class_exists('CbpWidgetGallery')):

    class CbpWidgetGallery extends CbpWidget
    {
        protected $_tags;

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_gallery',
                    /* Name */ 'Easy Gallery', array('description' => 'This is an Easy Gallery brick', 'icon'        => 'fa fa-camera fa-3x'));

            $this->setParseContentShortcodes(false);
        }

        public function registerFormElements($elements)
        {
            $elements['number_of_columns'] = 'one whole';
            $elements['equal_height']      = '1';
            $elements['content']           = '';
            $elements['tags']              = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

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
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => 'Yes',
                    '0'                 => 'No',
                ),
                'name'              => $this->getIdString('equal_height'),
                'value'             => $instance['equal_height'],
                'description_title' => $this->translate('Make Containers Equal Height'),
            ));
            CbpWidgetFormElements::subwidgetItems(array(
                'type'         => 'cbp_gallery_item',
                'subwidget_id' => 'cbp_subwidget_gallery_item',
                'use_tags'     => true,
                'tags'         => $instance['tags'],
                'value'        => $instance['content'],
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => '',
                        'custom_css_classes' => '',
                        'css_class'          => '',
                        'number_of_columns'  => 'one whole',
                        'equal_height'       => '1',
                        'tags'               => '',
                        'padding'            => '',
                            ), $atts));

            $shortcodes         = CbpWidgets::parseRawShortcode($content);
            $id                 = uniqid($this->getPrefix() . '-gallery-');
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $equalHeightClass   = $id . '-gallery-equal-height';
            $this->setTags($tags);
            $tags               = $this->getTags();
            $targetClass        = $id . '-mix-target';
            $filterClass        = $id . '-mix-filter';
            ?>
            <?php if ($tags): ?> 
                <div class="<?php echo $type; ?>_filter">
                    <span class="<?php echo $filterClass; ?> cbp_widget_link" data-filter="all"><?php echo $this->translate('All'); ?></span>
                    <?php foreach ($tags as $tag): ?>
                        <span class="<?php echo $filterClass; ?> cbp_widget_link" data-filter="<?php echo $tag[1]; ?>"><?php echo $tag[0]; ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div id="<?php echo $id; ?>" class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php foreach ($shortcodes as $key => $shortcode): ?>
                    <?php if ($shortcode['atts']['type'] == 'cbp_gallery_item' && isset($shortcode['atts']['img_src']) && $shortcode['atts']['img_src']): ?>
                        <?php $image = CbpWidgets::parseImageDetails($shortcode['atts']['img_src']); ?>
                        <div class="<?php echo $number_of_columns; ?> <?php echo $targetClass; ?> <?php echo $this->getFormatedTagsClasses($shortcode['atts']['tags']); ?> <?php echo CbpWidgets::getCssClass($shortcode['atts']['padding']); ?> <?php echo (int) $equal_height ? $equalHeightClass : ''; ?>">
                            <a class="cbp-row <?php echo $type; ?>_image" href="<?php echo $image['original_src']; ?>" data-lightbox="gallery-<?php echo $id; ?>" title="<?php echo $shortcode['atts']['caption']; ?>">
                                <img src="<?php echo $image['selected_src']; ?>" alt="<?php echo $shortcode['atts']['name']; ?>" />
                            </a>

                            <div class="cbp-row <?php echo $type; ?>_desc">
                                <?php echo $shortcode['content']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <script>
                
                var cbp_content_builder = cbp_content_builder || {};
                cbp_content_builder.data = cbp_content_builder.data || {};
                
                if (!cbp_content_builder.data.galleries) {
                    cbp_content_builder.data.galleries = [];
                }
                                                                                                                                                                                                                                                                                                                                                                                                                            
                cbp_content_builder.data.galleries.push({
                    id: '<?php echo $id; ?>',
                    options: {
                        targetSelector: '.<?php echo $targetClass; ?>', // needs dot infront
                        filterSelector: '.<?php echo $filterClass; ?>'  // needs dot infront
                    },
                    equalHeightClass: '<?php echo (int) $equal_height ? $equalHeightClass : 'null'; ?>'
                });      
                                                                                                                                                                                                                                                                                                                                                                                                                            
            </script>
            <?php
        }

        protected function parseTagsString($tagsString)
        {
            $tagsArr = array();
            if ($tagsString) {
                $parsedTagsArr = explode('|', $tagsString);
                foreach ($parsedTagsArr as $parsedTag) {
                    $tagsArr[] = explode(';', $parsedTag);
                }
            }
            return $tagsArr;
        }

        public function getTags()
        {
            return $this->_tags;
        }

        public function setTags($tagsString)
        {
            $this->_tags = $this->parseTagsString($tagsString);
        }

        protected function getFormatedTagsClasses($tagsString)
        {
            $parsedItemTagsArr = explode(',', $tagsString);
            $tagClasses        = '';

            foreach ($parsedItemTagsArr as $itemTag) {
                foreach ($this->getTags() as $tag) {
                    if (trim($itemTag) == $tag[0]) { // if it is equal to tag name, needs to be trimmed
                        $tagClasses .= ' ' . $tag[1]; // add tag url slug
                    }
                }
            }
            return $tagClasses;
        }
    }

    

    

    

    
  
endif;
