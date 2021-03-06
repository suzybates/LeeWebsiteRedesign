<?php

if (!class_exists('CbpSubwidgetTabsItem')):

    class CbpSubwidgetTabsItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_subwidget_tabs_item',
                    /* Name */ 'Tab', array('description' => 'This is a Tab sub-brick.', 'icon'        => 'fa fa-bolt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['name']    = '';
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
