<?php

/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class CbpBootstrap
{

    public function __construct()
    {
        if (is_admin()) {
            $this->initControllers();
        }
        $this->addAction();
        $this->inject();
        $this->addFilters();
        $this->registerShortcodes();
        $this->loadTextDomain();

        $this->initExtensions();
    }

    private function addAction()
    {
        // this is hooked to init so it's late enough that filters can be hooked to it
        add_action('init', array($this, 'initSidebar'));
        
        if (is_admin()) {
        
            if (CbpUtils::getOption('use_content_builder_layouts_and_templates')) {
                add_action('init', array($this, 'registerPostTypeLayouts'));
                add_action('init', array($this, 'registerPostTypeTemplates'));
            }

            add_action('save_post', array($this, 'saveCustomPostTypeMeta'), 1, 2);
            add_action('admin_print_scripts-post-new.php', array($this, 'cptAdminScript'), 11);
            add_action('admin_print_scripts-post.php', array($this, 'cptAdminScript'), 11);
            add_action('admin_print_styles', array($this, 'bricklayerSkinStyles'), 999);
        }
    }

    public function cptAdminScript()
    {
        global $post_type;
        if ('layouts' == $post_type) {

            wp_enqueue_script('spectrum-script', CBP_PLUGIN_URL . 'backend/public/js/spectrum.js', array('jquery', 'media-upload'));
            wp_enqueue_style('spectrum-style', CBP_PLUGIN_URL . 'backend/public/css/spectrum.css', array('thickbox'));
        }
    }

    function bricklayerSkinStyles()
    {
        $bricklayerSkin    = CbpUtils::getOption('bricklayer_builder_skin');
        $bricklayerSkinUrl = null;
        if ($bricklayerSkin == 'custom') {
            $upload_dir        = wp_upload_dir();
            if (is_file($upload_dir['basedir'] . '/bricklayer-custom.css')) {                
                $bricklayerSkinUrl = $upload_dir['baseurl'] . '/bricklayer-custom.css';
            }
        } else {
            if (is_file(CBP_PLUGIN_PATH . 'backend/public/css/bricklayer-skins/' . $bricklayerSkin . '/skin.css')) {    
                $bricklayerSkinUrl = CBP_PLUGIN_URL . 'backend/public/css/bricklayer-skins/' . $bricklayerSkin . '/skin.css';
            }
        }
        if ($bricklayerSkinUrl) {
            wp_enqueue_style('bricklayer-skin', $bricklayerSkinUrl);
        }
    }

    private function initControllers()
    {
        new CbpMenuPageController();
        new CbpAjaxController();
        
        if (CbpUtils::getOption('use_content_builder_layouts_and_templates')) {
            new CbpContentTemplatesSubmenuPageController();
        }

        if (CbpUtils::getOption('use_content_builder_with_pages')) {
            new CbpPageMetaController();
        }
        if (CbpUtils::getOption('use_content_builder_with_posts')) {
            new CbpPostMetaController();
        }
        new CbpRegisterCustomPostTypeMetaBox();
        new CbpStyleEditorSubmenuPageController();
        new CbpExportImportSubmenuPageController();
    }

    private function initExtensions()
    {
        if (CbpUtils::getOption('use_form_builder')) {
            require_once(CBP_PLUGIN_PATH . '/extensions/contact-form/grunion-contact-form.php');
        }
        require_once(CBP_PLUGIN_PATH . '/extensions/get-the-image.php');
        require_once(CBP_PLUGIN_PATH . '/extensions/cbp-get-the-image.php');
        require_once(CBP_PLUGIN_PATH . '/extensions/breadcrumb-trail/breadcrumb-trail.php');
    }

    public function initSidebar()
    {
        $initBricklayerSidebar = apply_filters('cbp_filter_init_bricklayer_sidebar', true);
        
        if ($initBricklayerSidebar !== false) {

            $args = array(
                'name'          => CbpTranslate::translateString('Bricklayer Sidebar'),
                'id'            => CBP_APP_PREFIX . 'sidebar',
                'description'   => CbpTranslate::translateString('Default Bricklayer Sidebar'),
                'class'         => '',
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>');

            register_sidebar($args);
        } else {
            CbpWidgets::unregisterWidget('CbpWidgetWpWidget');
        }
    }

    private function inject()
    {
        $injector = new CbpInjector();
        $injector->inject('backendJsGlobalObject', array('backend'));
        $injector->inject('frontJsGlobalObject', array('front'));
        $injector->inject('customCss', array('front'));
    }

    private function registerShortcodes()
    {
        new CbpShortcodes();
    }

    private function addFilters()
    {
        add_filter('widget_text', 'do_shortcode');
    }

    private function loadTextDomain()
    {
        load_plugin_textdomain(CBP_APP_TEXT_DOMAIN, false, CBP_PLUGIN_SLUG . DIRECTORY_SEPARATOR . 'languages/');
    }

    public function registerPostTypeLayouts()
    {
        $textdomain = 'default';

        $labels = array(
            'name'               => __('Layouts', $textdomain),
            'singular_name'      => __('Layout', $textdomain),
            'add_new'            => __('Add New Layout', $textdomain),
            'add_new_item'       => __('Add New Layout', $textdomain),
            'edit_item'          => __('Edit Layout', $textdomain),
            'new_item'           => __('Add New Layout', $textdomain),
            'view_item'          => __('View Layout', $textdomain),
            'search_items'       => __('Search Layouts', $textdomain),
            'not_found'          => __('No Layouts found', $textdomain),
            'not_found_in_trash' => __('No Layouts found in trash', $textdomain),
        );

        $args = array(
            'labels'       => $labels,
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'bricklayer',
            'supports'     => array(
                'title',
                'editor'
            ),
            'capability_type' => 'post',
            'rewrite'         => array('slug'                 => 'layouts'),
            'menu_position'        => 100,
            'register_meta_box_cb' => array($this, 'addLayoutsMetaBoxes'),
            'menu_icon' => 'dashicons-align-none',
        );

        register_post_type('layouts', $args);
    }

    public function addLayoutsMetaBoxes()
    {
        $metaboxView = new CbpView('Layouts', 'index');

        add_meta_box('cbp-layouts', 'Layout Builder', array($metaboxView, 'render'), 'layouts', 'advanced', 'high');
    }

    public function registerPostTypeTemplates()
    {
        $textdomain = 'default';

        $labels = array(
            'name'               => __('Templates', $textdomain),
            'singular_name'      => __('Template', $textdomain),
            'add_new'            => __('Add New Template', $textdomain),
            'add_new_item'       => __('Add New Template', $textdomain),
            'edit_item'          => __('Edit Template', $textdomain),
            'new_item'           => __('Add New Template', $textdomain),
            'view_item'          => __('View Template', $textdomain),
            'search_items'       => __('Search Templates', $textdomain),
            'not_found'          => __('No Templates found', $textdomain),
            'not_found_in_trash' => __('No Templates found in trash', $textdomain),
        );

        $args = array(
            'labels'       => $labels,
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'bricklayer',
            'supports'     => array(
                'title',
                'editor'
            ),
            'capability_type' => 'post',
            'rewrite'         => array('slug'                 => 'templates'),
            'menu_position'        => 100,
            'register_meta_box_cb' => array($this, 'addTemplatesMetaBoxes'),
            'menu_icon' => 'dashicons-screenoptions',
        );

        register_post_type('templates', $args);
    }

    public function addTemplatesMetaBoxes()
    {
        $metaboxView = new CbpView('Templates', 'index');

        add_meta_box('cbp-templates', 'Template Builder', array($metaboxView, 'render'), 'templates', 'advanced', 'high');
    }

    public function saveCustomPostTypeMeta($post_id, $post)
    {
        if ($post->post_type == 'layouts') {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            // Is the user allowed to edit the post or page?
            if (!current_user_can('edit_post', $post->ID))
                return $post->ID;
            // OK, we're authenticated: we need to find and save the data
            // We'll put it into an array to make it easier to loop though.
            $metaFields = array();

            // if checkbox is not checked it doesn't get send via POST so we need to set it to false here
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_theme_override'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_theme_override'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_theme_override'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_theme_override'] = false;
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_theme_override'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_theme_override'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_theme_override'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_theme_override'] = false;
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_container_width'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_container_width'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_container_width'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_container_width'] = false;
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_container_width'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_container_width'] = (int) $_POST[CBP_APP_PREFIX . 'layout_container_width'];
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_container_padding'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_container_padding'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_container_padding'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_container_padding'] = false;
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_container_padding_top'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_container_padding_top'] = (int) $_POST[CBP_APP_PREFIX . 'layout_container_padding_top'];
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_container_padding_right'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_container_padding_right'] = (int) $_POST[CBP_APP_PREFIX . 'layout_container_padding_right'];
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_container_padding_bottom'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_container_padding_bottom'] = (int) $_POST[CBP_APP_PREFIX . 'layout_container_padding_bottom'];
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_container_padding_left'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_container_padding_left'] = (int) $_POST[CBP_APP_PREFIX . 'layout_container_padding_left'];
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_body_background_color'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_body_background_color'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_body_background_color'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_body_background_color'] = false;
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_use_body_background_color'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_use_body_background_color'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_use_body_background_color'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_use_body_background_color'] = false;
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_body_background_color'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_body_background_color'] = $_POST[CBP_APP_PREFIX . 'layout_body_background_color'];
            }
            // CHECKBOX
            if (isset($_POST[CBP_APP_PREFIX . 'layout_override_global_body_background_image'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_body_background_image'] = filter_var($_POST[CBP_APP_PREFIX . 'layout_override_global_body_background_image'], FILTER_VALIDATE_BOOLEAN);
            } else {
                $metaFields[CBP_APP_PREFIX . 'layout_override_global_body_background_image'] = false;
            }
            if (isset($_POST[CBP_APP_PREFIX . 'layout_body_background_image'])) {
                $metaFields[CBP_APP_PREFIX . 'layout_body_background_image'] = $_POST[CBP_APP_PREFIX . 'layout_body_background_image'];
            }

            // Add values of $events_meta as custom fields
            foreach ($metaFields as $key => $value) { // Cycle through the $events_meta array!
                if ($post->post_type == 'revision') {
                    return; // Don't store custom data twice
                }
                update_post_meta($post_id, $key, $value);
                if (!$value) {
                    delete_post_meta($post->ID, $key); // Delete if blank
                }
            }
        }
    }
}
