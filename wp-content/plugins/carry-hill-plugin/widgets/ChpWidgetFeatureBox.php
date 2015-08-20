<?php

class ChpWidgetFeatureBox extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_feature_box',
                /* Name */ 'Feature Box', array('description' => 'This is Feature Box widget.', 'icon'        => 'fa fa-list-alt fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements = parent::registerFormElements($elements);

        $elements['title']         = '';
        $elements['title_link_to'] = '';
        $elements['icon_is_link']  = '0';
        $elements['icon']          = 'icon-glass';
        $elements['content']       = '';

        return $elements;
    }

    public function form($instance)
    {
        parent::form($instance);

        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('title'),
            'value'             => $instance['title'],
            'description_title' => $this->translate('Title'),
        ));

        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('title_link_to'),
            'value'             => $instance['title_link_to'],
            'description_title' => $this->translate('Title Link To...'),
            'description_body'  => $this->translate('Should be entered as absolute url (e.i. http://www.google.com).'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('icon_is_link'),
            'value'             => $instance['icon_is_link'],
            'description_title' => $this->translate('Make Icon a Link?'),
            'description_body'  => $this->translate('Only if link is set.'),
        ));

        CbpWidgetFormElements::iconSelect(array(
            'name'              => $this->getIdString('icon'),
            'value'             => $instance['icon'],
            'description_title' => $this->translate('Icon'),
            'description_body'  => $this->translate(''),
        ));

        CbpWidgetFormElements::tinyMce(array(
            'name'              => $this->getIdString('content'),
            'value'             => $instance['content'],
            'description_title' => $this->translate('Content'),
        ));
        ?>

        <?php
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'               => '',
                    'css_class'          => '',
                    'custom_css_classes' => '',
                    'title'              => '',
                    'title_link_to'      => '',
                    'icon_is_link'       => '0',
                    'icon'               => '',
                    'padding'                  => '',
            
                        ), $atts));
        $css_class           = !empty($css_class) ? ' ' . $css_class : '';
        $custom_css_classes  = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
        $padding            = CbpWidgets::getCssClass($padding);
        ?>

        <div class="<?php echo CbpWidgets::getCssClass('row'); ?> <?php echo $type; ?> <?php echo $css_class; ?><?php echo $custom_css_classes; ?> <?php echo $padding; ?>">        

            <div class="two fifths mobile ch-feature-box-icon-wrapper">
                <?php if (!empty($title_link_to) && (int) $icon_is_link): ?>
                    <a href="<?php echo $title_link_to; ?>">
                        <span class="ch-feature-box-icon">
                            <i class="fa <?php echo $icon; ?> fa-3x"></i>
                        </span>
                    </a>
                <?php else: ?>
                    <span class="ch-feature-box-icon">                    
                        <i class="fa <?php echo $icon; ?> fa-3x"></i>
                    </span>
                <?php endif; ?>
            </div>

            <div class="one half mobile align-left ch-feature-box-text-wrapper">

                <?php if (!empty($title)): ?>

                    <?php if (!empty($title_link_to)): ?>
                        <a href="<?php echo $title_link_to; ?>">
                            <h2><?php echo $title; ?></h2>
                        </a>
                    <?php else: ?>
                        <h2><?php echo $title; ?></h2>
                    <?php endif; ?>
                <?php endif; ?>
                <?php echo $content; ?>
            </div> 
        </div>

        <?php
    }
}

CbpWidgets::registerWidget('ChpWidgetFeatureBox');