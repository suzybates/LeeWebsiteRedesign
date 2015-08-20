<?php
if (!class_exists('CbpWidget')):

    /**
     * @author Parmenides <krivinarius@gmail.com>
     */
    class CbpWidget
    {
        protected $_prefix;
        protected $_id;
        protected $_name;
        protected $_settings = array(
            'display' => array(
                'backend' => array(
                    'tab'          => 'cbp_tab_widgets',
                    'priority'     => 100,
                )
            )
        );
        protected $_formElements = array();
        protected $_inlineStyles = array();
        protected $_parseContentShortcodes = true;

        public function __construct($id, $name, $settings = array())
        {
            $this->_prefix   = CbpWidgets::getPrefix();
            $this->_id       = $id;
            $this->_name     = $name;
            $settings = array_replace_recursive($this->_settings, $settings);

            $this->_settings = apply_filters('cbp_filter_widget_settings', $settings, $this->_id);
            
            // set by instance
            // done this way to provide a way for registering form elements
            // to resemble wp filter
            $this->_formElements = $this->registerFormElements($this->_formElements);
        }

        protected function getIdString($id)
        {
            return $this->getPrefix() . '_' . $id;
        }

        public function getPrefix()
        {
            return $this->_prefix;
        }

        protected function getScripts(array $scripts)
        {
            $out = '';

            foreach ($scripts as $script) {
                $out .= file_get_contents($script);
            }
            ?>
            <script type="text/javascript">
            <?php echo $out; ?>
            </script>
            <?php
        }

        public function registerFormElements($elements)
        {
            $elements['css_class']           = '';
            $elements['custom_css_classes']  = '';
            $elements['padding']             = '';
            $elements['display_description'] = '';

            return $elements;
        }

        protected function form($instance)
        {
            if ($this->_id != 'cbp_widget_row' && $this->_id != 'cbp_widget_box' && isset($instance['display_description'])) {

                CbpWidgetFormElements::text(array(
                    'name'              => $this->getIdString('display_description'),
                    'value'             => $instance['display_description'],
                    'description_title' => $this->translate('Display Description'),
                    'description_body'  => $this->translate('This is description that is displayed on a widget. This is not used on front end. Text will be cutoff after 55 characters.'),
                ));
            }

            $customCssClasses = apply_filters('cbp_register_custom_css_classes', $this->getId());

            if ($customCssClasses && is_array($customCssClasses) && isset($instance['custom_css_classes'])) {
                CbpWidgetFormElements::select(array(
                    'options'           => $customCssClasses,
                    'name'              => $this->getIdString('custom_css_classes'),
                    'value'             => $instance['custom_css_classes'],
                    'description_title' => $this->translate('Select Styling'),
                    'description_body'  => $this->translate('Types of widget styling.'),
                ));
            }

            $displayCssClass = apply_filters('cbp_widget_display_css_class', true, $this->getId());

            if ($displayCssClass && isset($instance['css_class'])) {
                CbpWidgetFormElements::text(array(
                    'name'              => $this->getIdString('css_class'),
                    'value'             => $instance['css_class'],
                    'description_title' => $this->translate('CSS Class'),
                    'description_body'  => $this->translate('This class/classes will be set to the container element of this brick in case you need to add extra styling.'),
                ));
            }

            $paddingClasses = array(
                ''                    => 'no padding',
                'padded'              => 'padded',
                'pad-top'             => 'pad-top',
                'pad-right'           => 'pad-right',
                'pad-bottom'          => 'pad-bottom',
                'pad-left'            => 'pad-left',
                'pad-top-bottom'      => 'pad-top-bottom',
                'pad-left-right'      => 'pad-left-right',
                'half-padded'         => 'half-padded',
                'half-pad-top'        => 'half-pad-top',
                'half-pad-right'      => 'half-pad-right',
                'half-pad-bottom'     => 'half-bottom',
                'half-pad-left'       => 'half-pad-left',
                'half-pad-top-bottom' => 'half-pad-top-bottom',
                'half-pad-left-right' => 'half-pad-left-right',
                'double-padded'       => 'double-padded',
                'double-pad-top'      => 'double-pad-top',
                'double-pad-right'    => 'double-pad-right',
                'double-pad-bottom'   => 'double-pad-bottom',
                'double-pad-left'     => 'double-pad-left',
                'double-top-bottom'   => 'double-pad-top double-pad-bottom',
                'double-left-right'   => 'double-pad-left double-pad-right',
                'triple-padded'       => 'triple-padded',
                'triple-pad-top'      => 'triple-pad-top',
                'triple-pad-right'    => 'triple-pad-right',
                'triple-pad-bottom'   => 'triple-pad-bottom',
                'triple-pad-left'     => 'triple-pad-left',
                'triple-top-bottom'   => 'triple-pad-top triple-pad-bottom',
                'triple-left-right'   => 'triple-pad-left triple-pad-right',
            );


            $paddingClasses = apply_filters('cbp_widget_filter_padding_classes', $paddingClasses, $this->getId());

            if ($paddingClasses && is_array($paddingClasses) && isset($instance['padding'])) {
                // these classes get filtered through CbpWidget::getCssClass()
                CbpWidgetFormElements::select(array(
                    'options'           => $paddingClasses,
                    'name'              => $this->getIdString('padding'),
                    'value'             => $instance['padding'],
                    'description_title' => $this->translate('Padding'),
                ));
            }
        }

        public function widget($atts, $content)
        {
            die('function CbpWidget::widget() must be over-ridden in a sub-class.');
        }

        public function getFormElements()
        {
            return $this->_formElements;
        }

        public function getId()
        {
            return $this->_id;
        }

        public function getName()
        {
            return $this->_name;
        }

        public function setParseContentShortcodes($value)
        {
            $this->_parseContentShortcodes = (bool) $value;
        }

        public function getParseContentShortcodes()
        {
            return $this->_parseContentShortcodes;
        }

        public function getSettings($setting)
        {
            if (isset($this->_settings[$setting])) {
                return $this->_settings[$setting];
            }
            return '';
        }

        public function setSettings($name, $value)
        {
            return $this->_settings[$name] = $value;
        }

        public function sanitize(&$attribute)
        {
            
            if ($attribute['name'] != CBP_APP_PREFIX . 'content') {
                $attribute['value'] = stripslashes($attribute['value']);
                $attribute['value'] = htmlspecialchars($attribute['value']);
            }
            
            switch ($attribute['name']) {
                case CBP_APP_PREFIX . 'css_class':
                case CBP_APP_PREFIX . 'custom_css_classes':
                    $attribute['value'] = sanitize_html_classes($attribute['value']);
                    break;

                case CBP_APP_PREFIX . 'content':
                    $attribute['value'] = stripslashes($attribute['value']);
                    break;
            }

            return $attribute;
        }

        protected function resetInlineStyles()
        {
            $this->_inlineStyles = array();
        }

        protected function setInlineStyle($property, $value)
        {
            $this->_inlineStyles[$property] = $value;
        }

        protected function getInlineStyles()
        {
            $output = '';
            if (count($this->_inlineStyles)) {

                $output = 'style="';

                foreach ($this->_inlineStyles as $property => $value) {

                    if (!empty($value)) {
                        $output .= $property . ':' . $value . ';';
                    }
                }

                $output .= '"';
            }

            return $output;
        }

        public function translate($string)
        {
            return CbpTranslate::translateString($string);
        }
    }

endif;