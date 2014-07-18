<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpShortcodes
{

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->addShortcodes();
        add_filter('the_content', array($this, 'theContentFilter'));
    }

    private function addShortcodes()
    {
        add_shortcode('cbp', array($this, 'cbpShortcode'));
        add_shortcode('cbp_widget_row', array($this, 'cbpRowShortcode'));
        add_shortcode('cbp_widget_box', array($this, 'cbpBoxShortcode'));
        add_shortcode('cbp_widget_element', array($this, 'cbpContentElementShortcode'));

        // used only to get them registered so we get the regex
        add_shortcode('cbp_accordion_item', array($this, 'cbpAccordionItem'));
        add_shortcode('cbp_tab_item', array($this, 'cbpTabItem'));
        add_shortcode('cbp_gallery_item', array($this, 'cbpGalleryItem'));
        add_shortcode('cbp_slider_item', array($this, 'cbpSliderItem'));
        add_shortcode('cbp_widget_element_item', array($this, 'cbpWidgetElementItem'));
        add_shortcode('bricklayer_template', array($this, 'bricklayerTemplate'));
    }

    public function theContentFilter($content)
    {
        // array of custom shortcodes requiring the fix
        $block = join('|', array(
            'cbp',
            'cbp_widget_row',
            'cbp_widget_box',
            'cbp_widget_element',
            'cbp_accordion_item',
            'cbp_tab_item',
            'cbp_gallery_item',
            'cbp_slider_item',
            'cbp_widget_element_item',
            'bricklayer_template',
                ));

        // opening tag
        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

        // closing tag
        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);

        return $rep;
    }

    public function getWidgetHtml($type, $atts, $content)
    {
        $registeredWidgets = CbpWidgets::getRegisteredWidgetObjects();

        if (isset($registeredWidgets[$type]) && $registeredWidgets[$type]) {

            $widget = $registeredWidgets[$type];

            ob_start();

            if (!$widget->getParseContentShortcodes()) { // let's parse them ourselfs in widget method
                $content = CbpWidgets::cleanAttributes($content);
            } else {
                $content = do_shortcode(CbpWidgets::cleanAttributes($content));
            }
            $atts = CbpWidgets::filterWidgetAttrs($atts);
            $widget->widget(CbpWidgets::cleanAttributes($atts), $content);
            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }
    }

    /**
     *  [cbp]the rest of shortcodes[/cbp]
     */
    public function cbpShortcode($atts, $content = null)
    {
        $content = do_shortcode($content);
        return $content;
    }

    /**
     *  [cbp_widget_row]the rest of shortcodes[/cbp_widget_row]
     */
    public function cbpRowShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
                    'type' => 'cbp_widget_row',
                        ), $atts));

        global $cbp_settings;

        $cbp_settings['row'] = $atts;

        return $this->getWidgetHtml($type, $atts, $content);
    }

    /**
     *  [cbp_widget_box]the rest of shortcodes[/cbp_widget_box]
     */
    public function cbpBoxShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
                    'width' => 'one half',
                    'type'  => 'cbp_widget_box',
                        ), $atts));

        global $cbp_settings;

        $cbp_settings['box'] = $atts;

        return $this->getWidgetHtml($type, $atts, $content);
    }

    /**
     *  [cbp_widget_element]the rest of shortcodes[/cbp_widget_element]
     */
    public function cbpContentElementShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
                    'type' => 'cbp_widget_text',
                        ), $atts));

        return $this->getWidgetHtml($type, $atts, $content);
    }

    /**
     *  [cbp_accordion_item]content[/cbp_accordion_item]
     */
    public function cbpAccordionItem($atts, $content = null)
    {
        // for now empty
        // this is here just to get the tag registered
    }

    /**
     *  [cbp_tab_item]content[/cbp_tab_item]
     */
    public function cbpTabItem($atts, $content = null)
    {
        // for now empty
        // this is here just to get the tag registered
    }

    /**
     *  [cbp_gallery_item]content[/cbp_gallery_item]
     */
    public function cbpGalleryItem($atts, $content = null)
    {
        // for now empty
        // this is here just to get the tag registered
    }

    /**
     *  [cbp_slider_item]content[/cbp_slider_item]
     */
    public function cbpSliderItem($atts, $content = null)
    {
        // for now empty
        // this is here just to get the tag registered
    }

    /**
     *  [cbp_widget_element_item]content[/cbp_widget_element_item]
     */
    public function cbpWidgetElementItem($atts, $content = null)
    {
        // for now empty
        // this is here just to get the tag registered
    }
    
    /**
     *  [bricklayer_template id="1"]
     */
    public function bricklayerTemplate($atts, $content = null)
    {
        extract(shortcode_atts(array(
                    'id' => 0,
                        ), $atts));
        if ($id) {
            $layout = get_post($id);
            
            if ($layout && $layout->post_type == 'templates') {
                echo do_shortcode(str_replace(array('<p>[', ']</p>'), array('[', ']'), $layout->post_content));
            }
        }
    }
}
