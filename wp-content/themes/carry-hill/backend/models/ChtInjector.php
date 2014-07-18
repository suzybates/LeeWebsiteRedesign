<?php

/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtInjector
{
    private $_sanitizer;

    public function __construct()
    {
        $this->_sanitizer = new ChtSanitizer();
    }

    public function inject($what, $where = array())
    {
        foreach ($where as $value) {
            $hook = $value == 'backend' ? 'admin_head' : 'wp_head';
            add_action($hook, array($this, $what . 'Callback'));
        }
    }

    public function faviconCallback()
    {
        $path = ChtUtils::getOption('favicon');
        if (!empty($path)) {
            echo '<link rel="shortcut icon" href="' . $path . '"/>' . "\n";
        }
    }

    public function googleAnalyticsCallback()
    {
        $googleAnalyticsCode = ChtUtils::getOption('google_analytics_code');
        if ($googleAnalyticsCode)
            echo $googleAnalyticsCode . "\n";
    }

    public function customCssCallback()
    {
        $customCss = ChtUtils::getOption('custom_css');
        if ($customCss)
            echo '<style>' . $customCss . '</style>' . "\n";
    }

    public function googleWebFontsCallback()
    {
        $fonts = $this->getGWFontsString();
        if ($fonts) {
            echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . urlencode($fonts) . '" />' . "\n";
        }
    }

    public function adminBarCompensateCallback()
    {
        ?>
        <?php if (is_user_logged_in()): ?>
            <style>.ch-header {top: 28px !important;}</style>
        <?php endif; ?>
        <?php
    }

    public function internalStyleSheetCallback()
    {
        global $post;
        
        $bodyBgColor    = $this->getCssOption('body_bg_color');
        $bodyFontFamily = $this->getCssOption('body_font_family');
        $bodyFontSize   = $this->getCssOption('body_font_size');
        $bodyTextColor  = $this->getCssOption('body_text_color');
        
        $headerMenuTextColor       = $this->getCssOption('header_menu_text_color');
        $headerMenuActiveBgColor   = $this->getCssOption('header_menu_active_bg_color');
        $headerMenuActiveTextColor = $this->getCssOption('header_menu_active_text_color');
        $headerMenuHoverBgColor    = $this->getCssOption('header_menu_hover_bg_color');
        $headerMenuHoverTextColor  = $this->getCssOption('header_menu_hover_text_color');
        $headerMenuShadowColor     = $this->getCssOption('header_menu_shadow_color');
        $headerMenuFontSize        = $this->getCssOption('header_menu_font_size');
        
        $headingsFontSizeH1 = $this->getCssOption('headings_font_size_h1');
        $headingsFontSizeH2 = $this->getCssOption('headings_font_size_h2');
        $headingsFontSizeH3 = $this->getCssOption('headings_font_size_h3');
        $headingsFontSizeH4 = $this->getCssOption('headings_font_size_h4');
        $headingsFontSizeH5 = $this->getCssOption('headings_font_size_h5');
        $headingsFontSizeH6 = $this->getCssOption('headings_font_size_h6');
        
        $headingsFontFamily = $this->getCssOption('headings_font_family');
        $headingsTextColor  = $this->getCssOption('headings_text_color');
        $headingsHoverColor = $this->getCssOption('headings_hover_color');
        $subtitleTextColor  = $this->getCssOption('subtitle_text_color');
        $subtitleFontFamily = $this->getCssOption('subtitle_font_family');
        
        $sliderTitleBigFontSize       = $this->getCssOption('slider_title_big_font_size');
        $sliderTitleBigTextColor      = $this->getCssOption('slider_title_big_text_color');
        $sliderTitleBigTextShadow     = $this->getCssOption('slider_title_big_text_shadow');
        $sliderTitleItalicTextColor   = $this->getCssOption('slider_title_italic_text_color');
        $sliderTitleItalicTextShadow  = $this->getCssOption('slider_title_italic_text_shadow');
        $sliderBorderTitleTextColor   = $this->getCssOption('slider_border_title_text_color');
        $sliderBorderTitleFontShadow  = $this->getCssOption('slider_border_title_text_shadow');

        $buttonBgColor         = $this->getCssOption('button_bg_color');
        $buttonBgHoverColor    = $this->getCssOption('button_bg_hover_color');
        $buttonTextColor       = $this->getCssOption('button_text_color');
        $buttonTextShadowColor = $this->getCssOption('button_text_shadow_color');
        
        
        $featureBoxIconColor        = $this->getCssOption('feature_box_icon_color');
        $featureBoxIconHoverColor   = $this->getCssOption('feature_box_icon_hover_color');
        $featureBoxCircleColor      = $this->getCssOption('feature_box_circle_color');
        $featureBoxCircleHoverColor = $this->getCssOption('feature_box_circle_hover_color');
        
        $footerTextColor = $this->getCssOption('footer_text_color');
        
        $linkColor      = $this->getCssOption('link_color');
        $linkHoverColor = $this->getCssOption('link_hover_color');
        
        $templateName = $post ? ChtFront::getTemplate($post->ID) : null;
        echo '<style>';
        ?>
            /* =========================================== */
            /* <?php echo ChtUtils::getOption('css'); ?> */
            /* =========================================== */
            
            /* Body */
            body { 
                <?php if ($bodyFontFamily): ?>
                    font-family: <?php echo $bodyFontFamily; ?>, serif; 
                <?php endif; ?>
                <?php if ($bodyFontSize): ?>
                    font-size: <?php echo (int) $bodyFontSize; ?>px;
                <?php endif; ?>
                <?php if ($bodyTextColor): ?>
                    color: <?php echo $bodyTextColor; ?>;
                <?php endif; ?>
                <?php if ($bodyBgColor): ?>
                    background-color: <?php echo $bodyBgColor; ?>;
                <?php endif; ?>
            }
            
            /* Boxed */
            .ch-main-wrap {
                max-width: <?php echo get_option('cbp_global_container_width')?>px;
                margin: 0 auto;
                <?php if ($bodyBgColor): ?>
                    background-color: <?php echo $bodyBgColor; ?>;
                <?php endif; ?>
            
            }
            .ch-main-wrap > .cbp-row {
            
                <?php if (get_option('cbp_global_container_padding_top')): ?>
                    padding-top: <?php echo get_option('cbp_global_container_padding_top')?>px;
                <?php endif; ?>
                <?php if (get_option('cbp_global_container_padding_right')): ?>
                    padding-right: <?php echo get_option('cbp_global_container_padding_right')?>px;
                <?php endif; ?>
                <?php if (get_option('cbp_global_container_padding_bottom')): ?>
                    padding-bottom: <?php echo get_option('cbp_global_container_padding_bottom')?>px;
                <?php endif; ?>
                <?php if (get_option('cbp_global_container_padding_left')): ?>
                    padding-left: <?php echo get_option('cbp_global_container_padding_left')?>px;
                <?php endif; ?>
            
            }
            <?php if (get_option('cbp_global_container_padding_left')): ?>
                .ch-main-wrap .ch-content-bottom-edge,
                .ch-main-wrap .ch-content-top-edge {
                    margin-left: -<?php echo get_option('cbp_global_container_padding_left')?>px;
                }
            <?php endif; ?>
            
            
            /* Links */
            <?php if ($linkColor): ?>
                a { color: <?php echo $linkColor; ?>; }
            <?php endif; ?>
            <?php if ($linkHoverColor): ?>
                a:hover { color: <?php echo $linkHoverColor; ?>; }
            <?php endif; ?>

            /* Header Menu 
            ================================= */
            /* Font Size */
            <?php if ($headerMenuFontSize): ?>
                 .sf-menu.ch-menu-main a {
                    font-size: <?php echo $headerMenuFontSize; ?>px;
                 }
            <?php endif; ?>
            /* Menu Text Color */
            <?php if ($headerMenuTextColor): ?>
                .sf-menu.ch-menu-main a {
                    color: <?php echo $headerMenuTextColor; ?>;
                    <?php if ($headingsFontFamily): ?>
                        font-family: <?php echo $headingsFontFamily; ?>, serif;
                    <?php endif; ?>
                }
            <?php endif; ?>
            /* Menu Active Background Color */
                .sf-menu.ch-menu-main .current-menu-item {
                    <?php if ($headerMenuActiveBgColor): ?>
                        background-color: <?php echo $headerMenuActiveBgColor; ?>;
                    <?php endif; ?>
                    <?php if ($headerMenuShadowColor): ?>
                        -webkit-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        -moz-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    <?php endif; ?>
                }
                <?php if ($headerMenuShadowColor): ?>
                    .sf-menu.ch-menu-main ul li,
                    .sf-menu.ch-menu-main ul ul li {
                        -webkit-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        -moz-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    }
                <?php endif; ?>
            /* Menu Active Text Color */
            <?php if ($headerMenuActiveTextColor): ?>
                .sf-menu.ch-menu-main .current-menu-item a {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                }
                .sf-menu.ch-menu-main li:hover a,
                .sf-menu.ch-menu-main li.sfHover a {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                }
            <?php endif; ?>
            /* Menu Hover Background Color */
                .sf-menu.ch-menu-main li:hover, .sf-menu.ch-menu-main li.sfHover {
                    <?php if ($headerMenuHoverBgColor): ?>
                        background-color: <?php echo $headerMenuHoverBgColor; ?>;
                    <?php endif; ?>
                    <?php if ($headerMenuShadowColor): ?>
                        -webkit-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        -moz-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                        box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    <?php endif; ?>
                }
            /* Menu Hover Text Color */
            <?php if ($headerMenuHoverTextColor): ?>
                .sf-menu.ch-menu-main li:hover a, .sf-menu.ch-menu-main li.sfHover a {
                    color: <?php echo $headerMenuHoverTextColor; ?>;
                }
            <?php endif; ?>
            /* Responsive menu */
            <?php if ($headerMenuTextColor): ?>
                .respmenu {
                    border-top: 1px <?php echo $headerMenuTextColor; ?> dotted;
                }
                .respmenu li a {
                    color: <?php echo $headerMenuTextColor; ?>;
                }
                .respmenu li a:hover {
                    color: <?php echo $headerMenuTextColor; ?>;
                }
                .cbp-respmenu-more {
                    color: <?php echo $headerMenuTextColor; ?>;
                }
                
            <?php endif; ?>
            <?php if ($headerMenuShadowColor): ?>
                li.respmenu_current > a {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                    -webkit-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    -moz-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                }
                .respmenu-open {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                    -webkit-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    -moz-box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                    box-shadow: 4px 4px 3px <?php echo $headerMenuShadowColor; ?>;
                }
            <?php endif; ?>
            <?php if ($headerMenuActiveTextColor): ?>
                li.respmenu_current > a:hover {
                   color: <?php echo $headerMenuActiveTextColor; ?>; 
                }
                li.respmenu_current .cbp-respmenu-more {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                }
                li.respmenu_current:hover .cbp-respmenu-more {
                    color: <?php echo $headerMenuActiveTextColor; ?>;
                }
                .respmenu-open hr,
                .respmenu-open:hover hr { border: 2px solid <?php echo $headerMenuActiveTextColor; ?>; }
            <?php endif; ?>

            /* Sticky Menu */
            <?php if ($bodyBgColor): ?>
                .ch-sticky-header .ch-header {                
                    background-color: <?php echo $bodyBgColor; ?>;
                }
            <?php endif; ?>    
                
                
            <?php if ($bodyTextColor): ?>
                .ch-middle-bar .cbp_widget_box li {
                    color: <?php echo $bodyTextColor; ?>;
                }
            <?php endif; ?>
            
                  
            /* Headings */
            <?php if ($headingsFontFamily): ?>
                h1, h2, h3, h4, h5, h6 { font-family: <?php echo $headingsFontFamily; ?>, serif; }
            <?php endif; ?>
            <?php if ($headingsTextColor): ?>
                h1, h2, h3, h4, h5, h6,
                h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: <?php echo $headingsTextColor; ?>; }
            <?php endif; ?>
            <?php if ($headingsHoverColor): ?>
                h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover { color: <?php echo $headingsHoverColor; ?>; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH1): ?>
                h1 { font-size: <?php echo (int) $headingsFontSizeH1; ?>px; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH2): ?>
                h2 { font-size: <?php echo (int) $headingsFontSizeH2; ?>px; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH3): ?>
                h3 { font-size: <?php echo (int) $headingsFontSizeH3; ?>px; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH4): ?>
                h4 { font-size: <?php echo (int) $headingsFontSizeH4; ?>px; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH5): ?>
                h5 { font-size: <?php echo (int) $headingsFontSizeH5; ?>px; }
            <?php endif; ?>
            <?php if ($headingsFontSizeH6): ?>
                h6 { font-size: <?php echo (int) $headingsFontSizeH6; ?>px; }
            <?php endif; ?>
                
            /* Subtitle Text Color */
            <?php if ($subtitleTextColor): ?>
                .ch-border-title, 
                .ch-icon-bullet-text .ch-icon-bullet-text-icon, 
                .widget a,
                .chp_widget_post_list .ch-border-title,
                .widget .ch-border-title,
                .chp_widget_post_list .ch-border-title a,
                .widget .ch-border-title a { 
                    color: <?php echo $subtitleTextColor; ?>; 
                }
            <?php endif; ?>
            <?php if ($subtitleFontFamily): ?>
                .ch-border-title { font-family: <?php echo $subtitleFontFamily; ?>, serif; }
            <?php endif; ?>

            /* Slider Titles */
            .ch-slider-title-big {
                <?php if ($sliderTitleBigFontSize): ?>
                    font-size: <?php echo $sliderTitleBigFontSize; ?>px;
                <?php endif; ?>
                <?php if ($sliderTitleBigTextColor): ?>
                    color: <?php echo $sliderTitleBigTextColor; ?>;
                <?php endif; ?>
                <?php if ($sliderTitleBigTextShadow): ?>
                    text-shadow: 0 2px  <?php echo $sliderTitleBigTextShadow; ?>;
                <?php endif; ?>
            }
            .ch-slider-title, .ch-slider-title-italic {
                <?php if ($sliderTitleBigTextColor): ?>
                    color: <?php echo $sliderTitleItalicTextColor; ?>;
                <?php endif; ?>
                <?php if ($sliderTitleItalicTextShadow): ?>
                    text-shadow: 0 2px <?php echo $sliderTitleItalicTextShadow; ?>;
                <?php endif; ?>
            }
            .ch-slider-border-title {
                <?php if ($sliderBorderTitleTextColor): ?>
                    color: <?php echo $sliderBorderTitleTextColor; ?>;
                <?php endif; ?>
                <?php if ($sliderBorderTitleFontShadow): ?>
                    text-shadow: 0 2px <?php echo $sliderBorderTitleFontShadow; ?>;
                <?php endif; ?>
            }
                
            /* Buttons */
            button, input[type="submit"], 
            input[type="button"], 
            input[type="reset"],
            .cbp_widget_link,
            a.cbp_widget_link {
                <?php if ($buttonBgColor): ?>
                    background-color: <?php echo $buttonBgColor; ?>;
                <?php endif; ?>
                <?php if ($buttonTextColor): ?>
                    color: <?php echo $buttonTextColor; ?>;
                <?php endif; ?>
                <?php if ($buttonTextShadowColor): ?>
                    text-shadow: 0px 1px <?php echo $buttonTextShadowColor; ?>;
                <?php endif; ?>
                font-size: 13px;
                <?php if ($headingsFontFamily): ?>
                    font-family: <?php echo $headingsFontFamily; ?>, serif;
                <?php endif; ?>
                box-shadow: 4px 5px 1px #FFF;

            }
            button:hover, 
            input[type="submit"]:hover,
            input[type="button"]:hover,
            input[type="reset"]:hover,
            .cbp_widget_link:hover,
            button:focus, 
            input[type="submit"]:focus,
            input[type="button"]:focus,
            input[type="reset"]:focus,
            a.cbp_widget_link:focus {
                <?php if ($buttonBgHoverColor): ?>
                    background-color: <?php echo $buttonBgHoverColor; ?>;
                <?php endif; ?>
            }
            
            /* Calendar */
            <?php if ($buttonBgColor): ?>
                .calendar-widget-title,
                table.eme-calendar-table .eventful {
                    background-color: <?php echo $buttonBgColor; ?>;
                }
                table.eme-calendar-table .eventless-today {
                    border-color: <?php echo $buttonBgColor; ?>;
                }
                .eme-calendar-full .fullcalendar .eventful {
                    background-color: <?php echo $buttonBgColor; ?>;
                }
            <?php endif; ?>
                    
            /* Feature Box 
            ============================== */
            /* Icon Color */
            <?php if ($featureBoxIconColor): ?>
                .chp_widget_feature_box .ch-feature-box-icon {
                    color: <?php echo $featureBoxIconColor; ?>;
                }
            <?php endif; ?>
            /* Icon Hover Color */
            <?php if ($featureBoxIconHoverColor): ?>
                .chp_widget_feature_box .ch-feature-box-icon:hover {
                    color: <?php echo $featureBoxIconHoverColor; ?>;
                }
            <?php endif; ?>
            /* Circle Color */
            <?php if ($featureBoxCircleColor): ?>
                .chp_widget_feature_box .ch-feature-box-icon,
                .chp_widget_page_title .chp-page-title-icon {
                    background-color: <?php echo $featureBoxCircleColor; ?>;
                }
            <?php endif; ?>
            /* Circle Hover Color */
            <?php if ($featureBoxCircleHoverColor): ?>
                .chp_widget_feature_box .ch-feature-box-icon:hover {
                    background-color: <?php echo $featureBoxCircleHoverColor; ?>;
                }
            <?php endif; ?>
            
            /* Footer Text Color */
            <?php if ($footerTextColor): ?>
                .ch-menu-footer a,
                .ch-footer-bottom {
                    color: <?php echo $footerTextColor; ?>;
                }
            <?php endif; ?>



        <?php
        echo '</style>';   
        ?>
        <?php if (($templateName == 'page-boxed-sidebar.php' 
                || $templateName == 'page-boxed.php'
                || $templateName == 'page-home-boxed.php')
                && get_option('cbp_global_background_image')
                ): ?>
        <script>
            var cbp_content_builder = cbp_content_builder || {};
                cbp_content_builder.data = cbp_content_builder.data || {};
                
            cbp_content_builder.data.bgImage = '<?php echo get_option('cbp_global_background_image'); ?>';            
        </script>
        <?php endif; ?>
        <?php
    }

    private function getColor($colorName)
    {
        $defaultColors = array(
            'top_bar_bg_color'              => '#272a33',
            'top_bar_text_color'            => '#7C7C7C',
            'footer_text_color'             => '#B0B0B0',
            'top_bar_search_bg_color'       => '#3D4250',
            'top_bar_search_text_color'     => '#B0B0B0',
            'top_bar_search_focus_color'    => '#535a6d',
            'header_bg_color'               => '#fdfdfd',
            'header_menu_text_color'        => '#161616',
            'header_menu_active_bg_color'   => '#ff6316',
            'header_menu_active_text_color' => '#fdfdfd',
            'header_menu_hover_bg_color'    => '#f15000',
            'header_menu_hover_text_color'  => '#fdfdfd',
            'header_border_color'           => '#ebebeb',
            'submenu_border_top_color'      => '#ff7430',
            'top_glider_background_color'   => '#f2f2f2',
            'body_bg_color'                 => '#f2f2f2',
            'widgets_bg_color'              => '#fdfdfd',
            'content_bg_color'              => '#fdfdfd',
            'footer_text_color'             => '#ff6316',
            'footer_text_hover_color'       => '#ff6316',
            // -------
            'dark'                          => '#161616',
            'orange'                        => '#ff6316',
            'dark_orange'                   => '#f15000',
            'white'                         => '#fdfdfd',
            'bg_color'                      => '#f2f2f2',
        );

        return ChtUtils::getOption($colorName) ? ChtUtils::getOption($colorName) : $defaultColors[$colorName];
    }

    public function themeJsGlobalObjectCallback()
    {
        ?>
        <script>
            var CHT = CHT ||
                { 
                themePrefix: "<?php echo CHT_APP_PREFIX; ?>",
                themeName: "<?php echo CHT_APP_NAME; ?>", 
                siteName: "<?php bloginfo('name'); ?>",  
                colorPresets: { 
                    "default": <?php echo $this->getDefaultColorPresets(); ?>, 
                    user: <?php echo $this->getUserColorPresets(); ?>
                },
                security: { 
                    ajaxOptionsNonce:"<?php echo wp_create_nonce('ajax-options-nonce'); ?>",
                    postMetaNonce:"<?php echo wp_create_nonce('post-meta-nonce'); ?>",
                    pageMetaNonce:"<?php echo wp_create_nonce('page-meta-nonce'); ?>"
                },
                data: {
                    useStickyMenu: <?php echo json_encode(filter_var(ChtUtils::getOption('header_menu_sticky_use'), FILTER_VALIDATE_BOOLEAN)); ?>
                }
            };
        </script>
        <?php
    }

    private function getDefaultColorPresets()
    {
        $colorsArr = explode(',', ChtUtils::getOption('default_color_preset_values'));
        return json_encode($colorsArr);
    }

    private function getUserColorPresets()
    {
        $colorsArr = explode(',', ChtUtils::getOption('user_color_preset_values'));
        return json_encode($colorsArr);
    }

    private function getGWFontsString()
    {
        $fontFamilies = array();
        
        $fontFamilies[] = $this->getCssOption('headings_font_family');
        $fontFamilies[] = $this->getCssOption('subtitle_font_family');
        $fontFamilies[] = $this->getCssOption('body_font_family');
        
        $arr = array_diff($fontFamilies, array(false));
        if ($arr) {            
            return implode('|', $arr);
        }
        
        return false;
    }

    private function getCssOption($optionName)
    {
        $value = ChtUtils::getOption($optionName);
        return $this->_sanitizer->disallow($value, array('default', ''));
    }

    private function printCss(array $optionNames, array $selectors, $smallScreenSize = false)
    {
        $doPrint         = false;
        $selectorsString = implode(',', $selectors);
        $result          = '';

        if ($smallScreenSize)
            $result .= '@media only screen and (max-width: ' . $smallScreenSize . ') {';

        $result .= $selectorsString . '{';

        foreach ($optionNames as $optionName) {

            switch ($optionName) {
                case 'headings_font_family':
                case 'body_font_family':
                    $value = $this->getCssOption($optionName);
                    if ($value) {
                        $result .= 'font-family:' . $value . ';';
                        $doPrint = true;
                    }
                    break;
                case 'headings_text_color':
                case 'body_text_color':
                case 'header_menu_hover_text_color':
                    $value   = $this->getCssOption($optionName);
                    if ($value) {
                        $result .= 'color:' . $value . ';';
                        $doPrint = true;
                    }
                    break;
                case 'headings_font_size':
                case 'headings_font_size_small_screens':
                case 'body_font_size':
                case 'body_font_size_small_screens':
                case 'main_menu_font_size':
                case 'main_menu_font_size_small_screens':
                case 'top_bar_font_size':
                    $value   = $this->getCssOption($optionName);
                    if ($value) {
                        $result .= 'font-size:' . $value . 'px;';
                        //$result .= 'line-height:' . $value . 'px;';
                        $doPrint = true;
                    }
                    break;

                default:
                    break;
            }
        }

        $result .= '}';

        if ($smallScreenSize)
            $result .= '}';
        if ($doPrint)
            echo $result;
    }
}
