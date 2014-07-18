<?php

if (!class_exists('CbpSubwidgetMetaItem')):

    class CbpSubwidgetMetaItem extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_subwidget_meta_item',
                    /* Name */ 'Meta Item', array('description' => 'This is a Meta Item sub-brick.', 'icon'        => 'fa fa-bolt fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['name']              = '';
            $elements['item_type']         = '';
            $elements['date_format']       = '';
            $elements['show_date_icon']    = '0';
            $elements['show_tags_icon']    = '0';
            $elements['show_author_icon']  = '0';
            $elements['show_comment_icon'] = '0';
            $elements['tags_is_link']      = '1';
            $elements['category_is_link']  = '1';
            $elements['author_is_link']    = '1';
            $elements['author_pretext']    = 'by';

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
                    'date'              => 'Date',
                    'category'          => 'Category',
                    'tags'              => 'Tags',
                    'author'            => 'Author',
                    'comment_count'     => 'Comment Count',
                ),
                'name'              => $this->getIdString('item_type'),
                'value'             => $instance['item_type'],
                'description_title' => $this->translate('Select Type'),
                'description_body'  => $this->translate('Types of meta data.'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'item_type'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    'M j, Y'            => date('M j, Y'),
                    'j M, Y'            => date('j M, Y'),
                    'F j, Y'            => date('F j, Y'),
                    'j F, Y'            => date('j F, Y'),
                ),
                'name'              => $this->getIdString('date_format'),
                'value'             => $instance['date_format'],
                'description_title' => $this->translate('Date Format'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'date')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_date_icon'),
                'value'             => $instance['show_date_icon'],
                'description_title' => $this->translate('Show Date Icon?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'date')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_tags_icon'),
                'value'             => $instance['show_tags_icon'],
                'description_title' => $this->translate('Show Tags Icon?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'tags')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_author_icon'),
                'value'             => $instance['show_author_icon'],
                'description_title' => $this->translate('Show Author Icon?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'author')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('show_comment_icon'),
                'value'             => $instance['show_comment_icon'],
                'description_title' => $this->translate('Show Comment Icon?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'comment_count')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('tags_is_link'),
                'value'             => $instance['tags_is_link'],
                'description_title' => $this->translate('Tags is Link?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'tags')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('author_is_link'),
                'value'             => $instance['author_is_link'],
                'description_title' => $this->translate('Author is Link?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'author')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('author_pretext'),
                'value'             => $instance['author_pretext'],
                'description_title' => $this->translate('Author Pretext'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'author')
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('category_is_link'),
                'value'             => $instance['category_is_link'],
                'description_title' => $this->translate('Category is Link?'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'item_type', 'data-parentstate' => 'category')
            ));
        }

        public function widget($atts, $content)
        {
            
        }
    }

    

endif;
