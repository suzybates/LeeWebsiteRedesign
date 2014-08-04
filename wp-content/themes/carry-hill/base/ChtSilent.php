<?php
if (!class_exists( 'ChtSilent' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtSilent
{
    protected $menuName;
    protected $submenuName;
    protected $capability = CHT_ADMIN_CAPABILITY;
    protected $slug;
    protected $_where;
    protected $_scripts;
    protected $_scriptDependencies;
    protected $_version;
    protected $_inFooter;
    protected $_styles;
    protected $_styleDependencies;
    protected $_styleConditional;
    protected $_media;
    protected $_view;

    public function __construct(array $where)
    {
        $this->_where = $where;
    }
    public function run()
    {
        $this->_addScripts($this->_where);
    }
    
    protected function _addScripts(array $hooks)
    {
        foreach ($hooks as $hook) {
           
            if ($this->_scripts) {
                new ChtScript( $hook, $this->_scripts, $this->_scriptDependencies, $this->_version, $this->_inFooter);        
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
    
    public function addStyles($styles = array(), $styleDepandencies = array(), $version = false, $media = 'screen', $styleConditional = false)
    {       
        $this->_styles            = $styles;
        $this->_styleDepandencies = $styleDepandencies;
        $this->_version           = $version;
        $this->_media             = $media;
        $this->_styleConditional  = $styleConditional;
    }
}
endif;
