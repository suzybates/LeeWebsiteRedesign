<?php
if (!class_exists('CbpWidgetTemplate')):

    class CbpWidgetTemplate extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_template',
                    /* Name */ 'Template', array('description' => 'This is a Template brick.', 'icon'        => 'fa fa-th-large fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['template_id'] = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);

            CbpWidgetFormElements::selectTemplate(array(
                'name'              => $this->getIdString('template_id'),
                'value'             => $instance['template_id'],
                'description_title' => $this->translate('Select Template'),
            ));
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_template',
                        'template_id'        => '',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => 'double-padded',
                            ), $atts));

            $templatePost = get_post($template_id);

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>

            <div class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?>">
                <?php echo do_shortcode($templatePost->post_content); ?>
            </div>


            <?php
//            $pattern = $this->getShortcodeRegex(array('cbp_widget_row'));
//            preg_match_all("/$pattern/s", $templatePost->post_content, $matches);
////            Zend_Debug::dump($matches);
//            $pattern = get_shortcode_regex();
//            $r = preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $matches[0]);
//            $shortcodes = CbpWidgets::parseRawShortcode($matches[0]);
//            Zend_Debug::dump($matches[0]);

            wp_reset_query();
        }
//        private function getShortcodeRegex($tagnames)
//        {
//            
//            $tagregexp = join('|', array_map('preg_quote', $tagnames));
//
//            // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
//            // Also, see shortcode_unautop() and shortcode.js.
//            return
//                    '\\['                              // Opening bracket
//                    . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
//                    . "($tagregexp)"                     // 2: Shortcode name
//                    . '(?![\\w-])'                       // Not followed by word character or hyphen
//                    . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
//                    . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
//                    . '(?:'
//                    . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
//                    . '[^\\]\\/]*'               // Not a closing bracket or forward slash
//                    . ')*?'
//                    . ')'
//                    . '(?:'
//                    . '(\\/)'                        // 4: Self closing tag ...
//                    . '\\]'                          // ... and closing bracket
//                    . '|'
//                    . '\\]'                          // Closing bracket
//                    . '(?:'
//                    . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
//                    . '[^\\[]*+'             // Not an opening bracket
//                    . '(?:'
//                    . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
//                    . '[^\\[]*+'         // Not an opening bracket
//                    . ')*+'
//                    . ')'
//                    . '\\[\\/\\2\\]'             // Closing shortcode tag
//                    . ')?'
//                    . ')'
//                    . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
//        }
    }

    


endif;
