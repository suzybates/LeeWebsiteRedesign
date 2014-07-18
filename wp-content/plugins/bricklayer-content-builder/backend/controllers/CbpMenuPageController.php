<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpMenuPageController extends CbpController
{

    protected $_type    = 'menu-page';
    protected $_scripts = array('js/spectrum.js', 'js/menu-page.js');
    protected $_scriptDependencies = array('jquery', 'media-upload');
    protected $_styles = array('css/menu-page.css', 'css/spectrum.css');
    protected $_styleDependencies = array('thickbox');
    protected $_ajaxCallback = 'cbpMenuPageAjax';
    protected $_viewFolder   = 'menu-page';
    protected $_title        = CBP_APP_NAME;
    protected $_icon         = 'dashicons-editor-insertmore';

    public function init()
    {
        $skinsPath = CBP_BACKEND_ROOT . '/public/css/bricklayer-skins/';

        $skins = array_map('basename', glob($skinsPath . '*', GLOB_ONLYDIR));
        if (!in_array('custom', $skins)) {
            $skins[] = 'custom';
        }

        $this->defaultOptions();
        $this->view->formElement = new CbpFormElement();
        $this->view->skins       = $skins;
    }

    public function cbpMenuPageAjax()
    {
        $reset = isset($_POST['reset']) ? $_POST['reset'] : false;

        parse_str($_POST['form'], $form);

        if ($form) {
            foreach ($form as $key => $value) {
                update_option($key, $this->sanitize($key, $value));
            }
        } elseif ($reset) {
            foreach ($this->default_options_array as $key => $value) {
                update_option($key, $value);
            }
        }

        die();
    }

    private function defaultOptions()
    {
        $this->default_options_array = array(
            CBP_APP_PREFIX . 'global_theme_override'                     => false,
            CBP_APP_PREFIX . 'global_container_width'                    => 1200,
            CBP_APP_PREFIX . 'global_container_padding_top'              => 0,
            CBP_APP_PREFIX . 'global_container_padding_right'            => 0,
            CBP_APP_PREFIX . 'global_container_padding_bottom'           => 0,
            CBP_APP_PREFIX . 'global_container_padding_left'             => 0,
            CBP_APP_PREFIX . 'global_use_background_color'               => false,
            CBP_APP_PREFIX . 'global_background_color'                   => '',
            CBP_APP_PREFIX . 'global_background_image'                   => '',
            CBP_APP_PREFIX . 'use_form_builder'                          => false,
            CBP_APP_PREFIX . 'global_load_font_awesome'                  => true,
            CBP_APP_PREFIX . 'recaptcha_use'                             => false,
            CBP_APP_PREFIX . 'rechaptcha_public_key'                     => '',
            CBP_APP_PREFIX . 'rechaptcha_private_key'                    => '',
            CBP_APP_PREFIX . 'use_content_builder_with_pages'            => true,
            CBP_APP_PREFIX . 'use_content_builder_with_posts'            => false,
            CBP_APP_PREFIX . 'use_content_builder_layouts_and_templates' => true,
            CBP_APP_PREFIX . 'custom_thumbnail_sizes'                    => '',
            CBP_APP_PREFIX . 'custom_css'                                => '',
            CBP_APP_PREFIX . 'use_scroll_to_top'                         => true,
            CBP_APP_PREFIX . 'enabled_custom_post_types'                 => '',
            CBP_APP_PREFIX . 'bricklayer_builder_skin'                   => 'default',
            CBP_APP_PREFIX . 'template_links'                            => '',
            CBP_APP_PREFIX . 'use_layout_global_settings_override'       => true,
        );

        foreach ($this->default_options_array as $key => $value) {
            add_option($key, $value);
        }
    }

    private function sanitize($option, $value)
    {
        $sanitizer = new CbpSanitizer();

        switch ($option) {
            case CBP_APP_PREFIX . 'global_theme_override':
            case CBP_APP_PREFIX . 'global_use_background_color':
            case CBP_APP_PREFIX . 'global_load_font_awesome':
            case CBP_APP_PREFIX . 'recaptcha_use':
            case CBP_APP_PREFIX . 'use_content_builder_with_pages':
            case CBP_APP_PREFIX . 'use_content_builder_with_posts':
            case CBP_APP_PREFIX . 'use_form_builder':
            case CBP_APP_PREFIX . 'use_content_builder_layouts_and_templates':
            case CBP_APP_PREFIX . 'use_scroll_to_top':
            case CBP_APP_PREFIX . 'use_layout_global_settings_override':

                $sanitizedValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;

            case CBP_APP_PREFIX . 'container_width':
            case CBP_APP_PREFIX . 'global_container_padding_top':
            case CBP_APP_PREFIX . 'global_container_padding_right':
            case CBP_APP_PREFIX . 'global_container_padding_bottom':
            case CBP_APP_PREFIX . 'global_container_padding_left':

                $sanitizedValue = (int) $value;
                if (empty($sanitizedValue)) {
                    $sanitizedValue = $this->default_options_array[$option];
                }
                if ($sanitizedValue < 0) {
                    $sanitizedValue = abs($value);
                }
                break;
            case CBP_APP_PREFIX . 'custom_thumbnail_sizes':

                $pattern        = '/[^a-zA-Z0-9\-\|\:]/';
                $replacement    = '';
                $sanitizedValue = preg_replace($pattern, $replacement, $value);
                break;
            case CBP_APP_PREFIX . 'custom_css':

                // this is because of jquery serialize() escaping;
                $sanitizedValue = stripslashes($value);
                break;
            default:
                $sanitizedValue = $value;
                break;
        }
        if (is_array($sanitizedValue)) {
            return $sanitizedValue;
        } else {
            return trim($sanitizedValue); // trim converts to string
        }
    }

}
