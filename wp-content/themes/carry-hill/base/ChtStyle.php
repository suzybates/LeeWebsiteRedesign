<?php
if (!class_exists( 'ChtStyle' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtStyle
{  
    protected $_front   = 'front';   
    protected $_rootPath;
    protected $_extension = '';
    protected $_directory = '';
    protected $_where;
    protected $_styles;
    protected $_dependencies;
    protected $_version;
    protected $_media;
    protected $_conditional = false;

    public function __construct($where, $styles = array(), $dependencies = array(), $version = false, $media = 'screen', $conditional = false)
    {
        $this->_where        = $where;
        $this->_styles       = $styles;
        $this->_dependencies = $dependencies;
        $this->_version      = $version;
        $this->_media        = $media;
        $this->_conditional  = $conditional;
                    
        $this->setRootPath();
        
        if ($this->_where == $this->_front) {
            add_action('wp_enqueue_scripts', array($this, 'styleCallback'));
        } else {
            add_action('admin_print_styles-'. $this->_where, array($this, 'styleCallback'));
        }                         
    }
    
    private function setRootPath()
    {
        $this->_rootPath = $this->_where == $this->_front 
                            ? CHT_FRONT_PUBLIC_URI . $this->_directory 
                            : CHT_BACKEND_PUBLIC_URI . $this->_directory;
    }
    
    public function styleCallback()
    {           
        global $wp_styles;
        
        foreach ($this->_styles as $name) {
            
            if ($this->_conditional) {
                
                wp_register_style(
                    $name, 
                    $this->_rootPath . $name . $this->_extension, 
                    $this->_dependencies, 
                    $this->_version, 
                    $this->_media
                );
                wp_enqueue_style($name);
                $wp_styles->registered[$name]->add_data('conditional', $this->_conditional);
                
            } else {
                
                wp_enqueue_style(
                        $name, 
                        $this->_rootPath . $name . $this->_extension, 
                        $this->_dependencies, 
                        $this->_version, 
                        $this->_media
                );
            }   
        }              
    }
}
endif;