<?php

class ChpWidgetPageTitle extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_page_title',
                /* Name */ 'Page Title', array('description' => 'This is Page Title widget.', 'icon'        => 'fa fa-bold fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['css_class']        = '';
        $elements['use_custom_title'] = '0';
        $elements['custom_title']     = '';
        $elements['icon']             = 'fa-glass';
        $elements['show_icon']        = '1';
        $elements['show_separator']   = '1';
        $elements['show_breadcrumbs'] = '1';

        return $elements;
    }

    public function form($instance)
    {
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('css_class'),
            'value'             => $instance['css_class'],
            'description_title' => $this->translate('CSS Class'),
            'description_body'  => $this->translate('This class/classes will be set to the container element of this text block in case you need to add extra styling.'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('use_custom_title'),
            'value'             => $instance['use_custom_title'],
            'description_title' => $this->translate('Use Custom Title?'),
            'description_body'  => $this->translate('If is set to "No" original page title will be used.'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('custom_title'),
            'value'             => $instance['custom_title'],
            'description_title' => $this->translate('Custom Title'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_icon'),
            'value'             => $instance['show_icon'],
            'description_title' => $this->translate('Show Icon?'),
        ));
        CbpWidgetFormElements::iconSelect(array(
            'name'              => $this->getIdString('icon'),
            'value'             => $instance['icon'],
            'description_title' => $this->translate('Icon'),
            'description_body'  => $this->translate(''),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_separator'),
            'value'             => $instance['show_separator'],
            'description_title' => $this->translate('Show Separator?'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('show_breadcrumbs'),
            'value'             => $instance['show_breadcrumbs'],
            'description_title' => $this->translate('Show Breadcrumbs?'),
        ));
        ?>

        <?php
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'             => '',
                    'css_class'        => '',
                    'use_custom_title' => '0',
                    'custom_title'     => '',
                    'icon'             => '',
                    'show_icon'        => '1',
                    'show_separator'   => '1',
                    'show_breadcrumbs' => '1',
                        ), $atts));
        $css_class         = !empty($css_class) ? ' ' . $css_class : '';
        global $post;
        $iconName          = isset($post->ID) ? ChtUtils::getMeta($post->ID, 'page_icon') : 'fa-exclamation';
        $isEvent           = function_exists('eme_is_events_page') && function_exists('eme_html_title') && eme_is_events_page();
        ?>

        <div class="<?php echo $type; ?><?php echo $css_class; ?> ">
            <?php if (!empty($icon)): ?>
                <div class="one eighth">
                    <div class="chp-page-title-icon">            
                        <i class="fa <?php echo $iconName ? $iconName : $icon; ?> fa-4x"></i>
                    </div>
                </div>
            <?php endif; ?>
            <div class="seven eighths">

                <?php if ((int) $use_custom_title && !empty($custom_title)): ?>
                    <h1><?php echo $custom_title; ?></h1>
                <?php else: ?>
                    <?php if ($isEvent): ?>
                        <h1><?php echo eme_html_title(''); ?></h1>
                    <?php else: ?>
                        <?php $queriedObject = get_queried_object(); ?>
                        <?php if ($queriedObject && isset($queriedObject->name) && $queriedObject->name): ?>
                            <h1><?php echo $queriedObject->name; ?></h1>
                        <?php elseif (is_day()): ?>
                            <h1>
                                <?php printf(__('Daily Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date() . '</span>'); ?>
                            </h1>
                        <?php elseif (is_month()): ?>
                            <h1>
                                <?php printf(__('Monthly Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', CHT_APP_TEXT_DOMAIN)) . '</span>'); ?>
                            </h1>
                        <?php elseif (is_year()): ?>
                            <h1>
                                <?php printf(__('Yearly Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date(_x('Y', 'yearly archives date format', CHT_APP_TEXT_DOMAIN)) . '</span>'); ?>
                            </h1>
                        <?php elseif (isset($post->post_title) && $post->post_title): ?>
                            <h1><?php echo $post->post_title; ?></h1>
                        <?php else: ?>
                            <h1><?php echo $this->translate('404 Not Found'); ?></h1>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ((int) $show_separator): ?>
                    <hr />
                <?php endif; ?>
                <?php if ((int) $show_breadcrumbs): ?>
                    <?php if ($isEvent): ?>
                        <div class="breadcrumb-trail breadcrumbs" itemprop="breadcrumb">
                            <span class="trail-begin"><a href="<?php echo home_url(); ?>" title="Content Builder"><?php echo $this->translate('Home'); ?></a></span>
                            <span class="sep">/</span>
                            <?php $eventPageTitle = eme_html_title(''); ?>
                            <?php if ($eventPageTitle != 'Events'): ?>
                                <span class="trail-middle"><?php eme_get_events_page(0, 1, $this->translate('Events')); ?></span>
                                <span class="sep">/</span>
                            <?php endif; ?>
                            <span class="trail-end"><?php echo eme_html_title(''); ?></span>
                        </div>
                    <?php else: ?>
                        <?php
                        if (function_exists('breadcrumb_trail'))
                            breadcrumb_trail(array('show_browse'   => false, 'post_taxonomy' => array(
                                    // 'post'  => 'post_tag',
                                    'portfolio' => 'portfolio_category',
                                    )));
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

CbpWidgets::registerWidget('ChpWidgetPageTitle');