<?php

if (!class_exists('CbpWidgets')):

    /**
     *
     * @since 1.0.0
     */
    class CbpWidgets
    {
        /**
         * Action and filter constants
         *
         * @since  1.2.0
         */
        const ACTION_REGISTER_WIDGET     = 'cbp_action_register_widget';
        const ACTION_TABS                = 'cbp_action_tabs';
        const FILTER_TABS                = 'cbp_filter_tabs';
        const FILTER_CSS_CLASS_MAP       = 'cbp_filter_css_class_map';
        const FILTER_DISALLOW_WIDGETS    = 'cbp_filter_disallow';
        const DEFAULT_WIDGET_CSS_CLASS   = 'cbp_brick';
        
        /**
         * TODO: remove
         */
        protected static $_cssClassesfilterName     = 'cbp_map_css_classes';
        protected static $_registerWidgetActionName = 'cbp_action_register_widget';
        protected static $_tabsActionName           = 'cbp_action_tabs';
        protected static $_tabsFilterName           = 'cbp_filter_tabs';
        protected static $_defaultWidgetCssClass    = 'cbp_brick';

        /**
         * Width classes map
         *
         * @since  1.0.0
         * @access private
         * @var    array
         */
        private static $_widthClasses = array(
            'one-sixth'     => '1/6',
            'one-fifth'     => '1/5',
            'one-fourth'    => '1/4',
            'one-third'     => '1/3',
            'one-half'      => '1/2',
            'two-thirds'    => '2/3',
            'three-fourths' => '3/4',
            'one-whole'     => '1/1',
        );
        
        /**
         * CSS classes map
         *
         * @since  1.0.0
         * @access private
         * @var    array
         */
        private static $_mappedCssClasses;
        
        /**
         * Registered tabs
         *
         * @since  1.2.0
         * @access private
         * @var    array
         */
        private static $_tabs          = array();
        
        /**
         * Registered widget class names
         *
         * @since  1.0.0
         * @access private
         * @var    array
         */
        private static $_registeredWidgetClassNames = array();
        
        /**
         * Registered widgets
         *
         * @since  1.0.0
         * @access private
         * @var    array
         */
        private static $_registeredWidgets = array();
        
        /**
         * Registered widget objects
         *
         * @since  1.0.0
         * @access private
         * @var    array
         */
        private static $_registeredWidgetObjects = array();
        
        /**
         * Plugin prefix
         *
         * @since  1.0.0
         * @access private
         * @var    string
         */
        private static $_prefix = 'cbp';

        /**
         * Sets up all widgets and actions.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public static function run()
        {
            self::addActions();
            self::registerDefaultTabs();
            self::registerDefaultWidgets();
            self::doRegisterCbpWidgetHook();
            self::mapCssClasses();
            self::initAjax();

            foreach (self::$_registeredWidgetClassNames as $widgetClassName) {
                if (class_exists($widgetClassName)) {
                    $widget = new $widgetClassName();

                    self::$_registeredWidgets[$widget->getId()] = array(
                        'name'         => $widget->getName(),
                        'icon'         => $widget->getSettings('icon'),
                        'formElements' => $widget->getFormElements());

                    self::$_registeredWidgetObjects[$widget->getId()] = $widget;
                }
            }

            do_action('cbp_widgets_after_run');
            self::stable_uasort(self::$_registeredWidgetObjects, array(__CLASS__, 'compareWidgetPriority'));
        }
        
        /**
         * Keeps the order when two members compare as equal
         *
         * @since  1.4.0
         * @access protected
         * @return void
         */
        protected static function stable_uasort(&$array, $cmp_function = 'strcmp') 
        {
            if(count($array) < 2) {
                return;
            }
            $halfway = count($array) / 2;
            $array1 = array_slice($array, 0, $halfway, TRUE);
            $array2 = array_slice($array, $halfway, NULL, TRUE);

            self::stable_uasort($array1, $cmp_function);
            self::stable_uasort($array2, $cmp_function);
            if(call_user_func($cmp_function, end($array1), reset($array2)) < 1) {
                $array = $array1 + $array2;
                return;
            }
            $array = array();
            reset($array1);
            reset($array2);
            while(current($array1) && current($array2)) {
                if(call_user_func($cmp_function, current($array1), current($array2)) < 1) {
                    $array[key($array1)] = current($array1);
                    next($array1);
                } else {
                    $array[key($array2)] = current($array2);
                    next($array2);
                }
            }
            while(current($array1)) {
                $array[key($array1)] = current($array1);
                next($array1);
            }
            while(current($array2)) {
                $array[key($array2)] = current($array2);
                next($array2);
            }
            return;
        }
        
        /**
	 * Adding actions
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
        protected static function addActions()
        {
            /**
             * Adds a filter for not allowing widgets to tabs panel
             */
            add_filter(self::FILTER_DISALLOW_WIDGETS, array(__CLASS__, 'disallowToWidgetPanel'));
        }

        /**
	 * Filter callback: Disallow Widgets
         * A list of widget ids to be disallowed from widget panel
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
        public static function disallowToWidgetPanel($disallowedWidgets)
        {
            $disallowedWidgets[] = 'cbp_subwidget_gallery_item';
            $disallowedWidgets[] = 'cbp_subwidget_accordion_item';
            $disallowedWidgets[] = 'cbp_subwidget_schedule_item';
            $disallowedWidgets[] = 'cbp_subwidget_slider_item';
            $disallowedWidgets[] = 'cbp_subwidget_tabs_item';
            $disallowedWidgets[] = 'cbp_subwidget_meta_item';

            return $disallowedWidgets;
        }

        /**
         * Executes an action after default widgets are registered.
         * This is meant to be used for registering additional widgets
         * by other plugins or themes
         * 
	 * @since  1.0.0
	 * @access protected
	 * @return void
         */
        protected static function doRegisterCbpWidgetHook()
        {
            do_action(self::ACTION_REGISTER_WIDGET);
            /**
             * @since 1.0.0
             * @deprecated 1.2.0
             */
            do_action('register_cbp_widget');
        }

        /**
         * Registers default widgets
         * 
	 * @since  1.0.0
	 * @access protected
	 * @return void
         */
        protected static function registerDefaultWidgets()
        {
            CbpWidgets::registerWidget('CbpWidgetRow');
            CbpWidgets::registerWidget('CbpWidgetBox');
            CbpWidgets::registerWidget('CbpWidgetContent');
            CbpWidgets::registerWidget('CbpWidgetText');
            CbpWidgets::registerWidget('CbpWidgetPageTitle');
            CbpWidgets::registerWidget('CbpWidgetImage');
            CbpWidgets::registerWidget('CbpWidgetMenu');
            CbpWidgets::registerWidget('CbpWidgetAccordion');
            CbpWidgets::registerWidget('CbpWidgetSchedule');
            CbpWidgets::registerWidget('CbpWidgetTabs');
            CbpWidgets::registerWidget('CbpWidgetGallery');
            CbpWidgets::registerWidget('CbpWidgetMap');
            CbpWidgets::registerWidget('CbpWidgetSlider');
            CbpWidgets::registerWidget('CbpWidgetVideo');
            CbpWidgets::registerWidget('CbpWidgetSinglePost');
            CbpWidgets::registerWidget('CbpWidgetPostList');
            CbpWidgets::registerWidget('CbpWidgetIcon');
            CbpWidgets::registerWidget('CbpWidgetWpSidebar');
            CbpWidgets::registerWidget('CbpWidgetWpWidget'); // this one can be unregistered with a filter
            CbpWidgets::registerWidget('CbpWidgetDivider');
            CbpWidgets::registerWidget('CbpWidgetLinkToPage');
            CbpWidgets::registerWidget('CbpWidgetLinkToPage2');
            CbpWidgets::registerWidget('CbpWidgetButton');
            CbpWidgets::registerWidget('CbpWidgetBreadcrumbs');
            CbpWidgets::registerWidget('CbpWidgetShortcode');

            if (CbpUtils::getOption('use_content_builder_layouts_and_templates')) {
                CbpWidgets::registerWidget('CbpWidgetFeaturedImage');
                CbpWidgets::registerWidget('CbpWidgetComments');
                CbpWidgets::registerWidget('CbpWidgetMeta');
                CbpWidgets::registerWidget('CbpWidgetTemplate');
//                CbpWidgets::registerWidget('CbpWidgetCategory');
//                CbpWidgets::registerWidget('CbpWidgetPost');
                CbpWidgets::registerWidget('CbpWidgetTheLoop');
                CbpWidgets::registerWidget('CbpWidgetPostContent');
                CbpWidgets::registerWidget('CbpWidgetPostTitle');
                CbpWidgets::registerWidget('CbpWidgetNewsletterTitle');
                CbpWidgets::registerWidget('CbpWidgetNewsletterList');
            }

            CbpWidgets::registerWidget('CbpSubwidgetGalleryItem');
            CbpWidgets::registerWidget(' ');
            CbpWidgets::registerWidget('CbpSubwidgetScheduleItem');
            CbpWidgets::registerWidget('CbpSubwidgetSliderItem');
            CbpWidgets::registerWidget('CbpSubwidgetTabsItem');
            CbpWidgets::registerWidget('CbpSubwidgetMetaItem');
        }

        /**
         * Registered tabs getter
         * 
	 * @since  1.2.0
	 * @access public
	 * @return array
         */
        public static function getTabs()
        {
            return self::$_tabs;
        }

        /**
         * Registers default tabs. Also, triggers an action and filters registered tabs
         * for additional tabs to be registered by other plugins or themes
         * 
	 * @since  1.2.0
	 * @access protected
	 * @return void
         */
        protected static function registerDefaultTabs()
        {
            self::registerTab(array(
                'id'       => 'cbp_tab_boxes',
                'type'     => 'boxes',
                'title'    => 'Boxes',
                'priority' => 300,
            ));
            self::registerTab(array(
                'id'       => 'cbp_tab_widgets',
                'type'     => 'widgets',
                'title'    => 'Bricks',
                'priority' => 200,
            ));
            self::registerTab(array(
                'id'       => 'cbp_tab_help',
                'type'     => 'default',
                'title'    => 'Help',
                'priority' => 50,
                'html'     => '<p>1. When saving page visual editor must be selected to ensure that all paragraph tags are properly closed.</p>',
            ));

            do_action(self::ACTION_TABS);

            uasort(self::$_tabs, array(__CLASS__, 'compareTabPriority'));

            self::$_tabs = apply_filters(self::$_tabsFilterName, self::$_tabs);
        }

        /**
         * Registers a tab
         * 
	 * @since  1.2.0
	 * @access public
	 * @return void
         */
        public static function registerTab(array $settings)
        {
            $settings = shortcode_atts(array(
                'id'       => false,
                'title'    => false,
                'type'     => 'default', // boxes || widgets || default 
                'priority' => 100,
                'html'     => '',
                    ), $settings);

            if ($settings['id'] && $settings['title']) {
                if (!array_key_exists($settings['id'], self::$_tabs)) {
                    self::$_tabs[$settings['id']] = $settings;
                } else {
                    throw new Exception('Duplicate Tab ID');
                }
            }
        }

        /**
         * Unregisters a tab
         * 
	 * @since  1.2.0
	 * @access public
	 * @return void
         */
        public static function unregisterTab($id)
        {
            if (array_key_exists($id, self::$_tabs)) {
                unset(self::$_tabs[$id]);
            }
        }

        /**
         * Tab sorting comparator callback
         * 
	 * @since  1.2.0
	 * @access protected
	 * @return int
         */
        protected static function compareTabPriority($tab1, $tab2)
        {
            if ($tab1['priority'] == $tab2['priority']) {
                return 0;
            }
            // higher number comes first
            return ($tab1['priority'] < $tab2['priority']) ? 1 : -1;
        }

        /**
         * Widget sorting comparator callback
         * 
	 * @since  1.2.0
	 * @access protected
	 * @return int
         */
        protected static function compareWidgetPriority($widget1, $widget2)
        {
            $widget1SettingsDisplay = $widget1->getSettings('display');
            $widget2SettingsDisplay = $widget2->getSettings('display');

            $widget1Priority = isset($widget1SettingsDisplay['backend']) && isset($widget1SettingsDisplay['backend']['priority']) ? $widget1SettingsDisplay['backend']['priority'] : false;
            $widget2Priority = isset($widget2SettingsDisplay['backend']) && isset($widget2SettingsDisplay['backend']['priority']) ? $widget2SettingsDisplay['backend']['priority'] : false;

            if (!$widget1Priority || !$widget2Priority || ($widget1Priority == $widget2Priority)) {
                return 0;
            }
            // higher number comes first
            return ($widget1Priority < $widget2Priority) ? 1 : -1;
        }

        /**
         * CSS map setter.
         * Maps default css classes, filters it and assigns it
         * to $_mappedCssClasses
         * 
	 * @since  1.0.0
	 * @access private
	 * @return void
         */
        private static function mapCssClasses()
        {
            $cssClasses = array(
                'row'                 => self::getPrefix() . '-row',
                'container'           => self::getPrefix() . '-container',
                'cbp_brick'           => 'cbp_brick',
                'one-sixth'           => 'one sixth',
                'one-fifth'           => 'one fifth',
                'one-fourth'          => 'one fourth',
                'one-third'           => 'one third',
                'one-half'            => 'one half',
                'two-thirds'          => 'two thirds',
                'three-fourths'       => 'three fourths',
                'one-whole'           => 'one whole',
                'padded'              => 'padded',
                'pad-top'             => 'pad-top',
                'pad-right'           => 'pad-right',
                'pad-bottom'          => 'pad-bottom',
                'pad-left'            => 'pad-left',
                'pad-top-bottom'      => 'pad-top pad-bottom',
                'pad-left-right'      => 'pad-left pad-right',
                'half-padded'         => 'half-padded',
                'half-pad-top'        => 'half-pad-top',
                'half-pad-right'      => 'half-pad-right',
                'half-pad-bottom'     => 'half-pad-bottom',
                'half-pad-left'       => 'half-pad-left',
                'half-pad-top-bottom' => 'half-pad-top half-pad-bottom',
                'half-pad-left-right' => 'half-pad-left half-pad-right',
                'double-padded'       => 'double-padded',
                'double-pad-top'      => 'double-pad-top',
                'double-pad-right'    => 'double-pad-right',
                'double-pad-bottom'   => 'double-pad-bottom',
                'double-pad-left'     => 'double-pad-left',
                'double-top-botton'   => 'double-pad-top double-pad-bottom',
                'double-left-right'   => 'double-pad-left double-pad-right',
                'triple-padded'       => 'triple-padded',
                'triple-pad-top'      => 'triple-pad-top',
                'triple-pad-right'    => 'triple-pad-right',
                'triple-pad-bottom'   => 'triple-pad-right',
                'triple-pad-left'     => 'triple-pad-left',
                'triple-top-bottom'   => 'triple-pad-top triple-pad-bottom',
                'triple-left-right'   => 'triple-pad-left triple-pad-right',
            );


            $cssClasses = apply_filters(self::$_cssClassesfilterName, $cssClasses);

            self::$_mappedCssClasses = $cssClasses;
        }

        public static function getMappedCssClasses()
        {
            return self::$_mappedCssClasses;
        }

        public static function getCssClass($className)
        {
            return isset(self::$_mappedCssClasses[$className]) ? self::$_mappedCssClasses[$className] : $className;
        }

        public static function getDefaultWidgetCssClass()
        {
            return self::getCssClass(self::$_defaultWidgetCssClass);
        }

        private static function initAjax()
        {
            $ajax = new CbpAjax();
            $ajax->setAjaxCallback(array(__CLASS__, 'cbpAjaxForm'));
            $ajax->run();

            $ajax = new CbpAjax();
            $ajax->setAjaxCallback(array(__CLASS__, 'cbpAjaxSanitize'));
            $ajax->run();
        }

        public static function cbpAjaxForm()
        {
            $type        = $_POST['type'];
            $widgetAttrs = $_POST['widgetAttrs'];

            self::getForm($type, $widgetAttrs);

            die();
        }

        public static function cbpAjaxSanitize()
        {
            $type        = $_POST['type'];
            $widgetAttrs = $_POST['widgetAttrs'];

            $registeredWidgets = self::getRegisteredWidgetObjects();

            if (isset($registeredWidgets[$type]) && $registeredWidgets[$type]) {

                $widget = $registeredWidgets[$type];

                foreach ($widgetAttrs as &$widgetAttr) { // assign by reference
                    $widget->sanitize($widgetAttr);
                }
                unset($widgetAttr); // break the reference with the last element
                echo json_encode($widgetAttrs);
            }

            die();
        }

        private static function getForm($type, $widgetAttrs)
        {
            $registeredWidgets = self::getRegisteredWidgetObjects();

            if (isset($registeredWidgets[$type]) && $registeredWidgets[$type]) {

                $widget = $registeredWidgets[$type];

                                
                $widgetAttrs = self::filterWidgetAttrs($widgetAttrs);

                ob_start();
                $widget->form(array_merge($widget->getFormElements(), $widgetAttrs));
                $form = ob_get_contents();
                ob_end_clean();

                echo json_encode(array(
                    'title'       => $widget->getName(),
                    'description' => $widget->getSettings('description'),
                    'form'        => $form,
                ));
            } else {
                echo json_encode(array(
                    'title'       => 'No Widget',
                    'description' => CbpTranslate::translateString('Widget by that name is not registered!'),
                ));
            }
        }
        
        public static function filterWidgetAttrs($widgetAttrs) 
        {
            $filtered = array();
            foreach ($widgetAttrs as $key => $widgetAttr) {
                if ($key != 'content') {
                    $widgetAttr = html_entity_decode(stripslashes($widgetAttr));
                }
                $filtered[$key] = $widgetAttr;
            }
            
            return $filtered;
        }

        public static function getPrefix()
        {
            return self::$_prefix;
        }

        public static function registerWidget($widgetClassName)
        {
            self::$_registeredWidgetClassNames[] = $widgetClassName;
        }

        public static function unregisterWidget($widgetClassName)
        {
            $key = array_search($widgetClassName, self::$_registeredWidgetClassNames);
            if ($key) {
                unset(self::$_registeredWidgetClassNames[$key]);
            }
        }

        public static function getRegisteredWidgets()
        {
            return self::$_registeredWidgets;
        }

        public static function getRegisteredWidgetObjects()
        {
            return self::$_registeredWidgetObjects;
        }

        public static function getWidthClasses()
        {
            return self::$_widthClasses;
        }

        public static function getWidthClassesKeys()
        {
            return array_keys(self::$_widthClasses);
        }

        public static function cleanAttributes($dirty)
        {

            if (is_string($dirty)) {
                self::clean($dirty);
            }
            if (is_array($dirty)) {

                array_walk($dirty, array(__CLASS__, 'clean'));
            }

            return $dirty;
        }

        public static function clean(&$item) // also supports second argument as $key
        {
            $firstClosingPTag = substr($item, 0, 4);
            $lastOpeningPTag  = substr($item, -3);

            if ($firstClosingPTag == '</p>') {
                $item = substr($item, 4);
            }

            if ($lastOpeningPTag == '<p>') {
                $item = substr($item, 0, -3);
            }
        }

        public static function strip($content)
        {
            $array = array(
                '<p>['         => '[',
                '<p><span>['   => '[',
                ']</p>'        => ']',
                ']</span></p>' => ']',
                ']<br />'      => ']'
            );
            $content       = strtr($content, $array);
            return $content;
        }

        public static function parseRawShortcode($content)
        {
            $pattern = get_shortcode_regex();
            preg_match_all("/$pattern/s", $content, $matches);

            $shortcodes = array();
            foreach ($matches[0] as $key => $match) {
                preg_match_all("/$pattern/s", $match, $m);
                $shortcodes[$key]['shortcode'] = $m[0][0];
                $shortcodes[$key]['tag']       = $m[2][0];
                $shortcodes[$key]['atts']      = shortcode_parse_atts($m[3][0]);
                $shortcodes[$key]['content']   = wpautop($m[5][0]);
            }

            return $shortcodes;
        }

        public static function parseImageDetails($detailsString)
        {
            $details = explode('|', $detailsString);

            $count = count($details);
            
            // count 3 is for legacy support
            if ($count == 3 || $count == 4) {

                $formatedDetails = array();
                $formatedDetails['attachment_id'] = (int) $details[0];
                $formatedDetails['original_src']  = $details[1];
                $formatedDetails['selected_src']  = $details[2];
                
                if ($count == 4) {
                    $formatedDetails['registered_key']  = $details[3];
                }

                return $formatedDetails;
            }
            return '';
        }
    }

    add_action('init', 'CbpWidgets::run');

endif;