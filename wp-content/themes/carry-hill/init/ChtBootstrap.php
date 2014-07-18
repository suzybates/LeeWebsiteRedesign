<?php

/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtBootstrap
{

    public function __construct()
    {
        if (is_admin()) {
            $this->initControllers();
        }

        $this->inject();
        $this->addFilters();
        $this->addThemeSupport();
        $this->addMenus();
        $this->registerShortcodes();
        $this->registerSidebars();

        add_filter('locale', array($this, 'setLocale'));
        add_action('after_setup_theme', array($this, 'loadTextDomain'));
    }

    private function initControllers()
    {
        new ChtThemeOptionsController();
        new ChtPageMetaController();
    }

    private function inject()
    {
        $injector = new ChtInjector();
        $injector->inject('themeJsGlobalObject', array('front', 'backend'));
        $injector->inject('googleAnalytics', array('front'));
        $injector->inject('googleWebFonts', array('front'));
        $injector->inject('favicon', array('front', 'backend'));
        $injector->inject('internalStyleSheet', array('front'));
        $injector->inject('customCss', array('front'));
        $injector->inject('adminBarCompensate', array('front'));
    }

    private function addMenus()
    {
        new ChtNavMenu('Primary', 'Secondary');
    }

    private function registerShortcodes()
    {
        new ChtShortcodes();
    }

    private function registerSidebars()
    {
        register_sidebar(array(
            'name'          => __('Regular Sidebar', CHT_APP_TEXT_DOMAIN),
            'id'            => 'ch-regular-sidebar',
            'description'   => __('Widgets in this area will be shown on the right-hand side.', CHT_APP_TEXT_DOMAIN),
            'before_title'  => '<h3 class="widget_title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
        ));

        register_sidebar(array(
            'name'          => __('Footer Top Sidebar', CHT_APP_TEXT_DOMAIN),
            'id'            => 'ch-footer-top-sidebar',
            'description'   => __('Widgets in this area will be shown abpve the footer.', CHT_APP_TEXT_DOMAIN),
            'before_title'  => '<h3 class="widget_title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div id="%1$s" class="widget one fourth double-padded %2$s">',
            'after_widget'  => '</div>',
        ));
    }

    public function getComments()
    {
        return new ChtComments();
    }

    private function addFilters()
    {
        add_filter('widget_text', 'do_shortcode');
    }

    public function setLocale($locale)
    {
        if (isset($_GET['l'])) {
            return esc_attr($_GET['l']);
        } 
        return $locale;
    }

    public function loadTextDomain()
    {
        load_theme_textdomain(CHT_APP_TEXT_DOMAIN, get_template_directory() . '/languages');
    }

    private function addThemeSupport()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_image_size('ch-featured-image', 613, 320, true);
        add_image_size('ch-gallery-thumb-third', 296, 216, true);
        add_image_size('ch-gallery-thumb-fourth', 235, 150, true);
    }
}
