<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpStyleEditorSubmenuPageController extends CbpController
{
    protected $_type         = 'submenu-page';
    protected $_parentSlug   = 'bricklayer';
    protected $_menuPriority = 10;
    protected $_scripts      = array(
        'js/codemirror/lib/codemirror.js',
        'js/codemirror/mode/css/css.js',
        'js/codemirror/addon/fold/foldcode.js',
        'js/codemirror/addon/fold/foldgutter.js',
        'js/codemirror/addon/fold/brace-fold.js',
        'js/codemirror/addon/selection/active-line.js',
        'js/style-editor.js',
    );
    protected $_scriptDependencies = array('jquery');
    protected $_styles = array(
        'js/codemirror/lib/codemirror.css',
        'js/codemirror/theme/ambiance.css',
        'css/style-editor.css',
    );
    protected $_ajaxCallback = 'cbpStyleEditorPageAjax';
    protected $_viewFolder   = 'style-editor-submenu-page';
    protected $_title        = 'Style Editor';

    public function init()
    {
        add_action('init', array($this, 'onFormSubmit'));

        $skinsPath = CBP_BACKEND_ROOT . '/public/css/bricklayer-skins/';

        $skins  = array_map('basename', glob($skinsPath . '*', GLOB_ONLYDIR));
        
        $skin   = !empty($_GET['skin']) ? $_GET['skin'] : 'default';
        $folder = $skinsPath . $skin;
        $file   = $skinsPath . $skin . '/skin.css';


        // Get uploads dir
        $upload_dir = wp_upload_dir();
        $customFile = $upload_dir['basedir'] . '/bricklayer-custom.css';

        // Get contents
        $customContents = file_exists($customFile) ? file_get_contents($customFile) : '';



        $this->view->skins          = $skins;
        $this->view->skin           = $skin;
        $this->view->folder         = $folder;
        $this->view->file           = $file;
        $this->view->customContents = $customContents;
        $this->view->uploadDir      = $upload_dir;
    }

    public function onFormSubmit()
    {
        if (isset($_POST['cbp-skin'])) {
            if (check_admin_referer('cbp-save-skin')) {
                $this->saveFile();
            }
        }
        if (isset($_POST['cbp-skin-custom'])) {
            if (check_admin_referer('cbp-save-skin-custom')) {
                $this->saveCustomFile();
            }
        }
    }

    protected function saveFile()
    {
        // Error checking
        if (empty($_POST['skin'])) {
            wp_die($this->translate("It looks like you haven't selected any skin to edit."), $this->translate('No skin selected.'), array('back_link' => true));
        }

        // Get skin file and contents
        $skin      = $_POST['skin'];
        $skinsPath = CBP_BACKEND_ROOT . '/public/css/bricklayer-skins/';
        $folder    = $skinsPath . $skin;
        $file      = $skinsPath . $skin . '/skin.css';
        $content   = $_POST['contents'];

        // Attempt to write the file
        if (is_writable($folder)) {
            file_put_contents($file, $content);
            header('Location: admin.php?page=cbp-style-editor&skin=' . $skin . '&edited=1');
        } else {
            wp_die($this->translate("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD)."), $this->translate('Cannot write to file'), array('back_link' => true));
        }
    }

    protected function saveCustomFile()
    {
        // Get target file and content
        $upload_dir = wp_upload_dir();
        $file       = $upload_dir['basedir'] . '/bricklayer-custom.css';
        $content    = stripslashes($_POST['contents']);


        // Attempt to save changes
        if (is_writable($upload_dir['basedir'])) {
            file_put_contents($file, $content);
            header('Location: admin.php?page=cbp-style-editor&edited=1');
            die();

            // File isn't writable
        } else {
            wp_die($this->translate("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD)."), $this->translate('Cannot write to file'), array('back_link' => true));
        }
    }

    public function cbpStyleEditorPageAjax()
    {
        die();
    }
}
