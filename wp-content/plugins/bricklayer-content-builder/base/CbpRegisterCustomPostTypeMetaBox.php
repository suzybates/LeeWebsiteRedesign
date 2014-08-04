<?php

if (!class_exists('CbpRegisterCustomPostTypeMetaBox')):

    /**
     * This class adds Bricklayer to each Custom Post Type selected as metabox
     */
    class CbpRegisterCustomPostTypeMetaBox
    {
        protected $_postType;
        protected $_slug;
        protected $_title;

        public function __construct()
        {
            $this->setTitle('Bricklayer');
            $this->setSlug('bricklayer');
            $this->addAction();
        }

        protected function addAction()
        {
            add_action('add_meta_boxes', array($this, 'addMetaBox'));
            add_action('admin_enqueue_scripts', array($this, 'addScripts'), 10, 1);
        }

        public function addMetaBox()
        {
            $selectedCustomPostTypes = $this->getSelectedCustomPostTypes();

            if (is_array($selectedCustomPostTypes) && isset($selectedCustomPostTypes[0]) && $selectedCustomPostTypes[0]) {
                foreach ($selectedCustomPostTypes as $key => $postType) {
                    add_meta_box($this->getSlug(), $this->getTitle(), array($this, 'getView'), $postType);
                }
            }
        }

        public function getView()
        {
            CbpContentBuilder::render();
        }

        protected function getSelectedCustomPostTypes()
        {
            $enabledCustomPostTypes = CbpUtils::getOption('enabled_custom_post_types');

            if (is_array($enabledCustomPostTypes)) {
                return $enabledCustomPostTypes;
            } elseif (is_string($enabledCustomPostTypes)) {
                return array($enabledCustomPostTypes);
            }
            return array();
        }

        function addScripts($hook)
        {

            global $post;

            $selectedCustomPostTypes = $this->getSelectedCustomPostTypes();

            // do if in_array or similar
            if ($hook == 'post-new.php' || $hook == 'post.php') {
//                if ('team_member' === $post->post_type) {
//                    wp_enqueue_script('team-member-script', TMPL_PLUGIN_URL . 'extensions/portfolio-post-type/js/test.js', array('jquery-ui-sortable'));
//                    wp_enqueue_style('style-name', TMPL_PLUGIN_URL . 'extensions/portfolio-post-type/css/test.css');
//                }
            }
        }

        public function getSlug()
        {
            return $this->_slug;
        }

        public function setSlug($slug)
        {
            $this->_slug = $slug;
        }

        public function getTitle()
        {
            return $this->_title;
        }

        public function setTitle($title)
        {
            $this->_title = $title;
        }
    }

    

endif;
