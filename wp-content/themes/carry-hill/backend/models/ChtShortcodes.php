<?php

/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtShortcodes
{

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->addShortcodes();
    }

    private function addShortcodes()
    {
        add_shortcode('column', array($this, 'columnShortcode'));
    }
    
    /**
     *  [column class="headline"]My Column[/column]
     */
    public function columnShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
                    'class' => 'caption',
                        ), $atts));

        return '<div class="' . esc_attr($class) . '">' . $content . '</div>';
    }
   
}
