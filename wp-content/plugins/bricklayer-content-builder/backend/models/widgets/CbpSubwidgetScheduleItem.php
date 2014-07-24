<?php

if (!class_exists('CbpSubwidgetScheduleItem')):

    class CbpSubwidgetScheduleItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_subwidget_schedule_item',
                    /* Name */ 'Schedule Item', array('description' => 'This is an Schedule Item sub-brick.', 'icon'        => 'fa fa-bolt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['name']    = '';
            $elements['starttime'] ='';
            $elements['endtime'] ='';
            $elements['activity'] ='';
            $elements['content'] = '';

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
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('starttime'),
                'value'             => $instance['starttime'],
                'description_title' => $this->translate('Start Time'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('endtime'),
                'value'             => $instance['endtime'],
                'description_title' => $this->translate('End Time'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('activity'),
                'value'             => $instance['activity'],
                'description_title' => $this->translate('Class or Activity'),
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
