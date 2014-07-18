<?php
if (!class_exists('CbpWidgetMenu')):

    class CbpWidgetMenu extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_menu',
                    /* Name */ 'Menu', array('description' => 'This is a Menu brick', 'icon'        => 'fa fa-align-justify fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['menu']                     = 'primary-menu';
            $elements['position']                 = 'right';
            $elements['depth']                    = '3';
            $elements['use_responsive_menu']      = '0';
            $elements['screen_width_brake_point'] = '767';
            $elements['responsive_menu_title']    = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::select(array(
                'options'           => get_registered_nav_menus(),
                'name'              => $this->getIdString('menu'),
                'value'             => $instance['menu'],
                'description_title' => $this->translate('Menu'),
            ));

            CbpWidgetFormElements::select(array(
                'options' => array(
                    ''                  => $this->translate('Left'),
                    'pull-right'        => $this->translate('Right')
                ),
                'name'              => $this->getIdString('position'),
                'value'             => $instance['position'],
                'description_title' => $this->translate('Position'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('depth'),
                'value'             => $instance['depth'],
                'description_title' => $this->translate('Depth'),
                'description_body'  => $this->translate('The depth of the menu. Please enter a number.'),
            ));
            CbpWidgetFormElements::select(array(
                'options' => array(
                    '1'                 => $this->translate('Yes'),
                    '0'                 => $this->translate('No')
                ),
                'name'              => $this->getIdString('use_responsive_menu'),
                'value'             => $instance['use_responsive_menu'],
                'description_title' => $this->translate('Use Responsive Menu?'),
                'attribs'           => array('data-type' => 'triggerparent', 'data-name' => 'use_responsive_menu')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('screen_width_brake_point'),
                'value'             => $instance['screen_width_brake_point'],
                'description_title' => $this->translate('Screen Width Brake Point'),
                'description_body'  => $this->translate('This is the width in pixels. Please enter only number. Bellow this brake point this menu becomes hidden and responsive menu is displayed instead.'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_responsive_menu', 'data-parentstate' => '1')
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('responsive_menu_title'),
                'value'             => $instance['responsive_menu_title'],
                'description_title' => $this->translate('Responsive Menu Title'),
                'attribs'           => array('data-type'        => 'triggerchild', 'data-parent'      => 'use_responsive_menu', 'data-parentstate' => '1')
            ));
        }

        public function sanitize(&$attribute)
        {
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'screen_width_brake_point':
                    if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                        $attribute['value'] = 767;
                    }
                    break;
                case CBP_APP_PREFIX . 'depth':
                    if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                        $attribute['value'] = 3;
                    }
                    break;
            }

            return parent::sanitize($attribute);
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'                     => 'cbp_widget_menu',
                        'css_class'                => '',
                        'custom_css_classes'       => '',
                        'menu'                     => 'primary-menu',
                        'position'                 => 'pull-right',
                        'depth'                    => 3,
                        'use_responsive_menu'      => '0',
                        'screen_width_brake_point' => '767',
                        'responsive_menu_title'    => '',
                        'padding'                  => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            $id                 = uniqid($this->getPrefix() . '-menu-');
            ?>

            <?php
            $defaults           = array(
                'theme_location' => $menu,
                'menu_class'     => 'sf-menu',
                'depth'          => $depth
            );
            ?>
            <?php $class           = $position . ' ' . $type; ?>

            <div id="<?php echo $id; ?>" class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $class; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php wp_nav_menu($defaults); ?>
            </div>
            <?php if ((int) $use_responsive_menu): ?> 
                <style>
                    @media screen and (max-width:<?php echo $screen_width_brake_point . 'px'; ?>) { 
                        #<?php echo $id; ?> { width: 100%; } 
                        #<?php echo $id; ?> ul { display: none; } 
                        #<?php echo $id; ?>-respmenu { display: block; } 
                    }
                </style>
                <script>
                    
                    var cbp_content_builder = cbp_content_builder || {};
                    cbp_content_builder.data = cbp_content_builder.data || {};
                    
                    if (!cbp_content_builder.data.respmenus) {
                        cbp_content_builder.data.respmenus = [];
                    }
                                                                                                                                                                                                                                                                                                            
                    cbp_content_builder.data.respmenus.push({
                        id: '<?php echo $id; ?>',
                        options: {
                            id: '<?php echo $id; ?>-respmenu',
                            header: '<?php echo $responsive_menu_title; ?>',
                            submenuToggle: {
                                className: 'cbp-respmenu-more',
                                html: '+'
                            }
                        }
                    });    
                                                                                                                                                                
                </script>
            <?php endif; ?>

            <?php
        }
    }

    

    

    

    

    

    
endif;
