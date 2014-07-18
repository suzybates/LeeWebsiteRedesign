<?php
if (!class_exists( 'ChtPageAbstract' )):
    
abstract class ChtPageAbstract
{
    protected $menuName;
    protected $submenuName;
    protected $capability = CHT_ADMIN_CAPABILITY;
    protected $slug;
    protected $_scripts;
    protected $_scriptDependencies;
    protected $_version;
    protected $_inFooter;
    protected $_styles;
    protected $_styleDependencies;
    protected $_styleConditional;
    protected $_media;
    protected $_view;

    public function __construct()
    {         
        $this->addAction();      
    }

    protected function addAction()
    {
        add_action('admin_menu', array($this, 'registerPage'));
    }

    public function registerPage()
    {
        //to be overridden
    }

    public function callback($post = null)
    {
        if ($post) {
            $this->_view->post = $post;
        }
        $this->_view->render();
    }

    public function setView($view)
    {
        $this->_view = $view;
    }
    
    protected function _addScripts(array $hooks)
    {
        foreach ($hooks as $hook) {
            
            if ($this->_scripts) {
                new ChtScript($hook, $this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);        
            }  
            if ($this->_styles) {
                new ChtStyle($hook, $this->_styles, $this->_styleDependencies, $this->_version, $this->_media, $this->_styleConditional);        
            }
        }
    }
    
    public function addScripts($scripts = array(), $scriptDepandencies = array(), $version = false, $inFooter = true)
    {       
        $this->_scripts            = $scripts;
        $this->_scriptDependencies = $scriptDepandencies;
        $this->_version            = $version;
        $this->_inFooter           = $inFooter;      
    }
    
    public function addStyles($styles = array(), $styleDependencies = array(), $version = false, $media = 'screen', $styleConditional = false)
    {       
        $this->_styles            = $styles;
        $this->_styleDependencies = $styleDependencies;
        $this->_version           = $version;
        $this->_inFooter          = $media; 
        $this->_styleConditional  = $styleConditional;
    }
}
endif;