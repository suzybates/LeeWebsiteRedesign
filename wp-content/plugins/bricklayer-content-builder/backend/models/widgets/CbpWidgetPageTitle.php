<?php
if (!class_exists('CbpWidgetPageTitle')):

    class CbpWidgetPageTitle extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_page_title',
                    /* Name */ 'Page Title', array('description' => 'This is a Page Title brick.', 'icon'        => 'fa fa-bold fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['use_custom_title'] = '0';
            $elements['custom_title']     = '';
            $elements['icon']             = 'fa-glass';
            $elements['show_icon']        = '1';
            $elements['show_separator']   = '1';
            $elements['show_breadcrumbs'] = '1';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_custom_title'),
                'value'             => $instance['use_custom_title'],
                'description_title' => $this->translate('Use Custom Title?'),
                'description_body'  => $this->translate('If is set to "No" original page title will be used.'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_custom_title')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('custom_title'),
                'value'             => $instance['custom_title'],
                'description_title' => $this->translate('Custom Title'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_custom_title', 'data-parentstate' => '1')
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
                        'use_custom_title'   => '0',
                        'custom_title'       => '',
                        'padding'            => '',
                            ), $atts));
            $css_class           = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes  = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding             = CbpWidgets::getCssClass($padding);
            global $post;
            ?>
            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $css_class; ?><?php echo $custom_css_classes; ?> <?php echo $padding; ?>">
                <?php if ((int) $use_custom_title && !empty($custom_title)): ?>
                    <h1><?php echo $custom_title; ?></h1>
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
            </div>
            <?php
        }
    }

    

    
endif;