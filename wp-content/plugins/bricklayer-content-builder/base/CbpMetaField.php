<?php
if (!class_exists( 'CbpMetaField' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpMetaField extends CbpPageAbstract
{
    static $_firstTime = true;
    protected $_postType;
    protected $_slug;
    protected $_title;

    public function __construct($title, $postType)
    {
        $this->_title    = $title;
        $this->_slug     = CbpUtils::slugify($this->_title);
        $this->_postType = $postType;
        
        parent::__construct();
    }
    
    protected function addAction()
    {
        add_action('add_meta_boxes', array($this, 'metaField'));
        
        if (self::$_firstTime) {
            add_action('save_post', array($this, 'saveMeta'));
            self::$_firstTime = false;
        }
    }

    public function metaField()
    {
        add_meta_box($this->_slug, $this->_title, array($this, 'callback'), $this->_postType);
        
        $this->_addScripts(array('post.php', 'post-new.php'));
    }

    /**
     * Saving meta field for every post separately
     */
    public function saveMeta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id))
            return $post_id;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times

//        if (!wp_verify_nonce($_POST['myplugin_noncename'], plugin_basename(__FILE__)))
//            return;

        // For All Post Types
        // if checkbox is not checked it doesn't get send via POST so we need to set it to false here
        // CHECKBOX
//        if (isset($_POST[CBP_APP_PREFIX . 'bricklayer_enabled'])) {
//            update_post_meta($post_id, CBP_APP_PREFIX . 'bricklayer_enabled' , filter_var($_POST[CBP_APP_PREFIX . 'bricklayer_enabled'], FILTER_VALIDATE_BOOLEAN));
//        } else {
//            update_post_meta($post_id, CBP_APP_PREFIX . 'bricklayer_enabled' , false);
//        }
//
//        if (isset($_POST[CBP_APP_PREFIX . 'bricklayer_original_post_content'])) {
//            update_post_meta($post_id, CBP_APP_PREFIX . 'bricklayer_original_post_content' , $_POST[CBP_APP_PREFIX . 'bricklayer_original_post_content']);
//        }
//
//        if (isset($_POST[CBP_APP_PREFIX . 'bricklayer_post_shortcodes'])) {
//            update_post_meta($post_id, CBP_APP_PREFIX . 'bricklayer_post_shortcodes' , $_POST[CBP_APP_PREFIX . 'bricklayer_post_shortcodes']);
//        }

        // Check permissions
        if (isset($_POST['post_type']) && $_POST['post_type'] == 'page') {

//            if (!current_user_can('edit_page', $post_id)) return;


            update_post_meta($post_id, CBP_APP_PREFIX . 'layout' , $_POST[CBP_APP_PREFIX . 'layout']);

            // if checkbox is not checked it doesn't get send via POST so we need to set it to false here
            if (isset($_POST[CBP_APP_PREFIX . 'use_layout'])) {
                update_post_meta($post_id, CBP_APP_PREFIX . 'use_layout' , $_POST[CBP_APP_PREFIX . 'use_layout']);
            } else {
                update_post_meta($post_id, CBP_APP_PREFIX . 'use_layout' , false);
            }

        } elseif (isset($_POST['post_type']) && $_POST['post_type'] == 'post'){
//            if (!current_user_can('edit_post', $post_id)) return;

        }

    }
}
endif;