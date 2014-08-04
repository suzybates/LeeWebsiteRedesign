<?php
if (!class_exists( 'ChtMetaField' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtMetaField extends ChtPageAbstract
{
    static $_firstTime = true;
    protected $_postType;
    protected $_slug;
    protected $_title;

    public function __construct($title, $postType)
    {
        $this->_title    = $title;
        $this->_slug     = ChtUtils::slugify($this->_title);
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

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times

//        if (!wp_verify_nonce($_POST['myplugin_noncename'], plugin_basename(__FILE__)))
//            return;


        // Check permissions
        if (isset($_POST['post_type']) && $_POST['post_type'] == 'page') {
            
            if (!current_user_can('edit_page', $post_id)) return;

            update_post_meta($post_id, CHT_APP_PREFIX . 'page_icon'     , $_POST[CHT_APP_PREFIX . 'page_icon']);

            
        } elseif (isset($_POST['post_type']) && $_POST['post_type'] == 'post'){
            if (!current_user_can('edit_post', $post_id)) return;
            
//            update_post_meta($post_id, BSP_APP_PREFIX . 'example-post-meta-1', $_POST['example-post-meta-1']);
        }

    }
}
endif;
