<?php

if (!class_exists('CbpSettings')) :

    /**
     * @author Parmenides <krivinarius@gmail.com>
     */
    class CbpSettings
    {
        protected static $_settings;
        protected static $_overwriteTemplateLinks = false;

        public static function prepare()
        {
            $settings = array(
                'global_theme_override',
                'global_container_width',
                'global_container_padding_top',
                'global_container_padding_right',
                'global_container_padding_bottom',
                'global_container_padding_left',
                'global_use_background_color',
                'global_background_color',
                'global_background_image',
                'use_form_builder',
                'global_load_font_awesome',
                'recaptcha_use',
                'rechaptcha_public_key',
                'rechaptcha_private_key',
                'use_content_builder_with_pages',
                'use_content_builder_with_posts',
                'use_content_builder_layouts_and_templates',
                'custom_thumbnail_sizes',
                'custom_css',
                'use_scroll_to_top',
                'enabled_custom_post_types',
                'bricklayer_builder_skin',
                'template_links',
                'use_layout_global_settings_override',
            );

            foreach ($settings as $optionName) {
                self::$_settings[$optionName] = CbpUtils::getOption($optionName);
            }
        }

        public static function getSettings()
        {
            return self::$_settings;
        }

        public static function getSettingsJson()
        {
            return json_encode(self::$_settings);
        }

        public static function parse($settings)
        {
            $settings = json_decode($settings, true);

            if (!is_array($settings)) { // if not succesful the first time
                $settings = json_decode($settings, true);
            }

            return $settings;
        }

        public static function save($settings)
        {
            $settings = self::parse($settings);
            
            foreach ($settings as $key => $value) {

                if ($key == 'template_links') {
                    $overwriteTemplateLinks = self::getOverwriteTemplateLinks();
                    if ($overwriteTemplateLinks) {
                        update_option(CBP_APP_PREFIX . $key, $value);
                    } else {
                        $current_links = get_option(CBP_APP_PREFIX . $key);
                        if (is_array($current_links)) {
                            update_option(CBP_APP_PREFIX . $key, array_merge($current_links, $value));
                        }
                    }
                } else {
                    update_option(CBP_APP_PREFIX . $key, $value);
                }
            }

            return true;
        }

        public static function getOverwriteTemplateLinks()
        {
            return self::$_overwriteTemplateLinks;
        }

        public static function setOverwriteTemplateLinks($overwrite)
        {
            self::$_overwriteTemplateLinks = (bool) $overwrite;
        }
    }

    

endif;
