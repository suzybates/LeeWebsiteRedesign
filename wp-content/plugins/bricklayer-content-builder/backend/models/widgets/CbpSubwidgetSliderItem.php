<?php

if (!class_exists('CbpSubwidgetSliderItem')):

    class CbpSubwidgetSliderItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_subwidget_slider_item',
                    /* Name */ 'Slider Item', array('description' => 'This is a Slider Item sub-brick.', 'icon'        => 'fa fa-bolt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['name']                 = '';
            $elements['item_type']            = '';
            $elements['video_type']           = '';
            $elements['video_id']             = '';
            $elements['video_width']          = '';
            $elements['video_height']         = '';
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
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'image'             => $this->translate('Image'),
                    'video'             => $this->translate('Video'),
                    'text'              => $this->translate('Text'),
                ),
                'name'              => $this->getIdString('item_type'),
                'value'             => $instance['item_type'],
                'description_title' => $this->translate('Item Type'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'item_type'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'youtube'           => $this->translate('Youtube'),
                    'vimeo'             => $this->translate('Vimeo'),
                ),
                'name'              => $this->getIdString('video_type'),
                'value'             => $instance['video_type'],
                'description_title' => $this->translate('Video Type'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'video'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_id'),
                'value'             => $instance['video_id'],
                'description_title' => $this->translate('Video ID'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'video'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_width'),
                'value'             => $instance['video_width'],
                'description_title' => $this->translate('Video Width'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'video'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('video_height'),
                'value'             => $instance['video_height'],
                'description_title' => $this->translate('Video Height'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'video'),
            ));
            CbpWidgetFormElements::mediaUpload(array(
                'name'              => $this->getIdString('img_src'),
                'value'             => $instance['img_src'],
                'description_title' => $this->translate('Image'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'image'),
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
