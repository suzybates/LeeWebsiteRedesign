<?php

if (!class_exists('CbpSubwidgetGalleryItem')):

    class CbpSubwidgetGalleryItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_subwidget_gallery_item',
                    /* Name */ 'Gallery Item', array('description' => 'This is a Gallery Item sub-brick.', 'icon'        => 'fa fa-bolt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['name']                 = '';
            $elements['caption']              = '';
            $elements['tags']                 = '';
            $elements['img_src']              = '';
            $elements['thumbnail_dimensions'] = '';
            $elements['content']              = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('name'),
                'value'             => $instance['name'],
                'description_title' => $this->translate('Name'),
            ));
            CbpWidgetFormElements::mediaUpload(array(
                'name'              => $this->getIdString('img_src'),
                'value'             => $instance['img_src'],
                'description_title' => $this->translate('Image'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('caption'),
                'value'             => $instance['caption'],
                'description_title' => $this->translate('Caption'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('tags'),
                'value'             => $instance['tags'],
                'description_title' => $this->translate('Tags'),
                'description_body'  => $this->translate('Please use comma separated list of tags.'),
            ));
            CbpWidgetFormElements::tinyMce(array(
                'name'              => $this->getIdString('content'),
                'value'             => $instance['content'],
                'description_title' => $this->translate('Content'),
            ));
        }

        public function widget($atts, $content)
        {
            
        }
    }

    

    
endif;
