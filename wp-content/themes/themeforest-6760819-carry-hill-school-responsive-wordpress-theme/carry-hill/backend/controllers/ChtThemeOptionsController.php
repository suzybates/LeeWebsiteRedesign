<?php

/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtThemeOptionsController extends ChtController
{
    protected $_type    = 'menu-page';
    protected $_scripts = array('js/spectrum.js', 'js/theme-options.js', 'js/gwfonts.min.js');
    protected $_scriptDependencies = array('jquery', 'jquery-ui-tabs', 'media-upload');
    protected $_styles = array('css/theme-options.css', 'css/spectrum.css', 'css/font-awesome.min.css');
    protected $_styleDependencies = array('thickbox');
    protected $_ajaxCallback = 'themeOptionsAjax';
    protected $_viewFolder   = 'theme-options';
    protected $_title        = 'Theme Options';
    private $default_options_array;

    public function init()
    {
        $this->defaultOptions();

        $tabsArr = array(
            array('filename' => 'general', 'title'    => 'General', 'icon'     => 'fa fa-cog'),
            array('filename' => 'styling', 'title'    => 'Styling', 'icon'     => 'fa fa-adjust'),
//            array('filename' => 'blog', 'title'    => 'Blog', 'icon'     => 'fa fa-forward'),
//            array('filename' => 'portfolio', 'title'    => 'Portfolio', 'icon'     => 'fa fa-camera-retro'),
//            array('filename' => 'additional', 'title'    => 'Additional', 'icon'     => 'fa fa-check')
        );

        $tabsModel = new ChtTabs();
        $tabs      = $tabsModel->getTabs($tabsArr, CHT_THEME_OPTIONS_TABS_DIR);

        $colorSchemesArr = array(
            array('name'   => 'default', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/default-turquoise.png'),
            array('name'   => 'green-orange', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/green-orange.png'),
            array('name'   => 'green-brown', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/green-brown.png'),
            array('name'   => 'green-blue', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/green-blue.png'),
            array('name'   => 'light-orange', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/light-orange.png'),
            array('name'   => 'light-blue-orange', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/light-blue-orange.png'),
            array('name'   => 'orange-yellow', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/orange-yellow.png'),
            array('name'   => 'orange-blue', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/orange-blue.png'),
            array('name'   => 'violet-brown', 'imgSrc' => CHT_BACKEND_URI . '/public/img/thumbnails/violet-brown.png'),
        );

        $webFonts = new ChtWebFonts();

        $this->view->tabs         = $tabs;
        $this->view->option_name  = $this->getPrettyName();
        $this->view->formElement  = new ChtFormElement();
        $this->view->webFonts     = $webFonts->getFonts();
        $this->view->colorSchemes = $colorSchemesArr;
    }

    public function themeOptionsAjax()
    {
        $save_type = $_POST['type'];
        //----------------------------------------------------------------------
        if ($save_type == 'upload') {

            $data             = $_POST['data']; // Acts as the name
            $filename         = $_FILES[$data];
            $filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

            $override['test_form'] = false;
            $override['action']    = 'wp_handle_upload';
            $uploaded_file         = wp_handle_upload($filename, $override);

            //--entering new path to database
            update_option($data, $uploaded_file['url']);

            $image_id = substr($data, 7);

            if (!empty($uploaded_file['error'])) {
                echo 'Upload Error: ' . $uploaded_file['error'];
            } else {
                echo $uploaded_file['url'];
            }
        }
        //----------------------------------------------------------------------
        elseif ($save_type == 'image_reset') {

            $data = $_POST['data'];
            update_option($data, '');
        }
        //----------------------------------------------------------------------
        elseif ($save_type == 'options') {

            parse_str($_POST['data'], $arr);

            foreach ($arr as $key => $value) {
                update_option($key, $this->sanitize($key, $value));
            }
        }
        //----------------------------------------------------------------------
        elseif ($save_type == 'reset') {

            foreach ($this->default_options_array as $key => $value) {
                update_option($key, $value);
            }
        }
        die(); // this is required to return a proper result
    }

    private function defaultOptions()
    {
        $this->default_options_array = array(
            // General
            CHT_APP_PREFIX . 'favicon'                         => '',
            CHT_APP_PREFIX . 'logo'                            => '',
            CHT_APP_PREFIX . 'header_menu_sticky_use'          => 'false',
            CHT_APP_PREFIX . 'footer_text'                     => 'copyright carry hill ' . date('Y'),
            CHT_APP_PREFIX . 'google_analytics_code'           => '',
            // Styling
            CHT_APP_PREFIX . 'css'                             => 'default',
            CHT_APP_PREFIX . 'custom_css'                      => '',
            CHT_APP_PREFIX . 'default_color_preset_values'     => 'fdfdfd,f2f2f2,ff6316,272a33,bdab2f,9e9e9e,d6003e,1a80b6,7a1632,91b027,ab8b65,e9a825,00b1d3,e10707',
            CHT_APP_PREFIX . 'user_color_preset_values'        => '',
            // Header menu
            CHT_APP_PREFIX . 'header_menu_text_color'          => '#F7F6F4',
            CHT_APP_PREFIX . 'header_menu_active_bg_color'     => '#F0EBE0',
            CHT_APP_PREFIX . 'header_menu_active_text_color'   => '#35b2b2',
            CHT_APP_PREFIX . 'header_menu_hover_bg_color'      => '#F7F6F5',
            CHT_APP_PREFIX . 'header_menu_hover_text_color'    => '#35B2B2',
            CHT_APP_PREFIX . 'header_menu_shadow_color'        => '#389AA0',
            CHT_APP_PREFIX . 'header_menu_font_size'           => 'default',
            // Headings
            CHT_APP_PREFIX . 'headings_font_size_h1'           => 'default',
            CHT_APP_PREFIX . 'headings_font_size_h2'           => 'default',
            CHT_APP_PREFIX . 'headings_font_size_h3'           => 'default',
            CHT_APP_PREFIX . 'headings_font_size_h4'           => 'default',
            CHT_APP_PREFIX . 'headings_font_size_h5'           => 'default',
            CHT_APP_PREFIX . 'headings_font_size_h6'           => 'default',
            CHT_APP_PREFIX . 'headings_font_family'            => 'default',
            CHT_APP_PREFIX . 'headings_text_color'             => '#6e6e6e',
            CHT_APP_PREFIX . 'headings_hover_color'            => '#5d5d5d',
            CHT_APP_PREFIX . 'subtitle_text_color'             => '#35b0b0',
            CHT_APP_PREFIX . 'subtitle_font_family'            => 'default',
            // Slider Titles
            CHT_APP_PREFIX . 'slider_title_big_font_size'      => 'default',
            CHT_APP_PREFIX . 'slider_title_big_text_color'     => '#f7f1e5',
            CHT_APP_PREFIX . 'slider_title_big_text_shadow'    => '#3e989d',
            CHT_APP_PREFIX . 'slider_title_italic_text_color'  => '#2d8085',
            CHT_APP_PREFIX . 'slider_title_italic_text_shadow' => '#70c8c8',
            CHT_APP_PREFIX . 'slider_border_title_text_color'  => '#2d8085',
            CHT_APP_PREFIX . 'slider_border_title_text_shadow' => '#70c8c8',
            // Body
            CHT_APP_PREFIX . 'body_bg_color'                   => '#35b2b2',
            CHT_APP_PREFIX . 'body_font_family'                => 'default',
            CHT_APP_PREFIX . 'body_font_size'                  => 'default',
            CHT_APP_PREFIX . 'body_text_color'                 => '#A19D9D',
            // Buttons
            CHT_APP_PREFIX . 'button_font_size'                => 'default',
            CHT_APP_PREFIX . 'button_bg_color'                 => '#35b2b2',
            CHT_APP_PREFIX . 'button_bg_hover_color'           => '#4FCBCB',
            CHT_APP_PREFIX . 'button_text_color'               => '#F7F6F4',
            CHT_APP_PREFIX . 'button_text_hover_color'         => '#F7F6F4',
            CHT_APP_PREFIX . 'button_text_shadow_color'        => '#2B8F8E',
            CHT_APP_PREFIX . 'button_text_shadow_hover_color'  => '#2B8F8E',
            CHT_APP_PREFIX . 'button_border_shadow_color'      => '#FFF',
            // Feature Box
            CHT_APP_PREFIX . 'feature_box_icon_color'          => '#F4F4F4',
            CHT_APP_PREFIX . 'feature_box_icon_hover_color'    => '#F4F4F4',
            CHT_APP_PREFIX . 'feature_box_circle_color'        => '#E47823',
            CHT_APP_PREFIX . 'feature_box_circle_hover_color'  => '#e15a00',
            // Footer
            CHT_APP_PREFIX . 'footer_text_color'               => '#F7F6F4',
            // Links
            CHT_APP_PREFIX . 'link_color'                      => '#35B0B0',
            CHT_APP_PREFIX . 'link_hover_color'                => '#BAB7B7',
            // Page Title
            CHT_APP_PREFIX . 'page_title_use_icon'             => 'true',
                // Blog and Portfolio
//            CHT_APP_PREFIX . 'single_post_use_content_builder'        => false,
//            CHT_APP_PREFIX . 'single_post_layout'                     => '',
//            CHT_APP_PREFIX . 'portfolio_use_content_builder_single'   => false,
//            CHT_APP_PREFIX . 'portfolio_layout_single_post'           => '',
//            CHT_APP_PREFIX . 'portfolio_use_content_builder_category' => false,
//            CHT_APP_PREFIX . 'portfolio_layout_category'              => '',
                // Aditional
                // Recaptcha
//            CHT_APP_PREFIX . 'recaptcha_use'                   => 'true',
//            CHT_APP_PREFIX . 'rechaptcha_public_key'           => '',
//            CHT_APP_PREFIX . 'rechaptcha_private_key'          => ''
        );

        foreach ($this->default_options_array as $key => $value) {
            add_option($key, $value);
        }
    }

    private function sanitize($option, $value)
    {
        $sanitizer = new ChtSanitizer();

        switch ($option) {
            case CHT_APP_PREFIX . 'blog_title':

                $sanitizedValue = wp_strip_all_tags($value, true);
                break;

            case CHT_APP_PREFIX . 'slider_timing':
                //case CHT_APP_PREFIX . 'blog_posts_per_page':

                $sanitizedValue = (int) $value;
                if (empty($sanitizedValue))
                    $sanitizedValue = $this->default_options_array[$option];
                if ($sanitizedValue < 0)
                    $sanitizedValue = abs($value);
                break;

            case CHT_APP_PREFIX . 'google_analytics_code':
            case CHT_APP_PREFIX . 'footer_text':

                // this is because of jquery serialize() escaping;
                // 3 backslashes are needed to escape the + sign as well
//                $sanitizedValue = preg_replace('/\\\+/', '', $value);
                $sanitizedValue = stripslashes($value);
                break;

            case CHT_APP_PREFIX . 'headings_font_size_h1':
            case CHT_APP_PREFIX . 'headings_font_size_h2':
            case CHT_APP_PREFIX . 'headings_font_size_h3':
            case CHT_APP_PREFIX . 'headings_font_size_h4':
            case CHT_APP_PREFIX . 'headings_font_size_h5':
            case CHT_APP_PREFIX . 'headings_font_size_h6':
            case CHT_APP_PREFIX . 'headings_font_size_small_screens':
            case CHT_APP_PREFIX . 'body_font_size':
            case CHT_APP_PREFIX . 'body_font_size_small_screens':
            case CHT_APP_PREFIX . 'top_bar_font_size':

                $sanitizedValue = $sanitizer->numOnly($value);
                $sanitizedValue = abs($sanitizedValue);
                if ($sanitizedValue === 0) {
                    $sanitizedValue = 'default';
                }
                break;

            default:
                $sanitizedValue = $value;
                break;
        }
        return trim($sanitizedValue);
    }
}
