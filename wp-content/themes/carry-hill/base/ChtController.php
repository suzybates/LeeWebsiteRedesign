<?php
if (!class_exists( 'ChtController' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtController
{
    protected $_type;
    protected $_parentSlug;
    protected $_where      = array();
    protected $_page       = null;
    protected $_title      = null;
    protected $_viewName   = 'index';
    protected $_viewFolder;
    
    protected $_hook               = 'front';
    protected $_scripts            = array();
    protected $_scriptDependencies = array();
    protected $_version            = false;
    protected $_inFooter           = true;
    
    protected $_styles            = array();
    protected $_styleDependencies = array();
    protected $_media             = 'screen';
    protected $_styleConditional  = false;
    protected $_ajaxCallback;
    protected $view;

    public function __construct()
    {
        $this->initView();
        $this->initChild();
        $this->initAjax();
        $this->renderPage();
    }

    private function initView()
    {
        $viewFolder = $this->_viewFolder ? $this->_viewFolder : $this->getName();

        $this->view = new ChtView($viewFolder, $this->_viewName);
    }

    private function initChild()
    {
        $this->init();
    }

    private function initAjax()
    {
        if ($this->_ajaxCallback) {
            $ajax = new ChtAjax();
            $ajax->setAjaxCallback(array($this, $this->_ajaxCallback));
            $ajax->run();
        }
    }

//    private function renderPage()
//    {
//        if ($this->_type) {
//            
//            if ($this->_type == 'menu-page') {
//                $this->_page = new MenuPage($this->getPrettyName());
//            } else if ($this->_type == 'submenu-page') {
//                $this->_page = new SubmenuPage($this->getPrettyName(), $this->_parentSlug);
//            } else if ($this->_type == 'post-meta') {
//                $this->_page = new MetaField($this->getPrettyName(), 'post');
//            } else if ($this->_type == 'page-meta') {
//                $this->_page = new MetaField($this->getPrettyName(), 'page');
//            } 
//            $this->_page->setView($this->view);
//            $this->_page->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
//            $this->_page->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media);
//        }
//    }

    private function renderPage()
    {
        if ($this->_type == 'menu-page') {
            $this->_page = new ChtMenuPage($this->getPrettyName());
            $this->_page->setView($this->view);
            $this->_page->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
            $this->_page->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);
        } else if ($this->_type == 'submenu-page') {
            $this->_page = new ChtSubmenuPage($this->getPrettyName(), $this->_parentSlug);
            $this->_page->setView($this->view);
            $this->_page->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
            $this->_page->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);
        } else if ($this->_type == 'post-meta') {
            $this->_page = new ChtMetaField($this->getPrettyName(), 'post');
            $this->_page->setView($this->view);
            $this->_page->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
            $this->_page->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);
        } else if ($this->_type == 'page-meta') {
            $this->_page = new ChtMetaField($this->getPrettyName(), 'page');
            $this->_page->setView($this->view);
            $this->_page->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
            $this->_page->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);
        } else if ($this->_type == 'silent') {
            $silent = new ChtSilent($this->_where);
            $silent->addScripts($this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);
            $silent->addStyles($this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);
            $silent->run();
        } 
//        else {
//            $this->view->render();
//        }
    }

    protected function getName()
    {
        if ($this->_title) {
            return $this->_title;
        }
        return str_replace('Controller', '', get_class($this));
    }

    protected function getPrettyName()
    {
        return ChtUtils::spaceOutCamelCase($this->getName(), ' ');
    }

    protected function getSlug()
    {
        return ChtUtils::slugify($this->getName());
    }
}
endif;
