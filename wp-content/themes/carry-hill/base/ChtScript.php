<?php
if (!class_exists( 'ChtScript' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtScript
{
    protected $_front   = 'front';   
    protected $_rootPath;
    protected $_extension = '';
    protected $_directory = '';
    protected $_where;
    protected $_scripts;
    protected $_dependencies;
    protected $_version;
    protected $_inFooter;

    public function __construct($where, $scripts = array(), $dependencies = array(), $version = false, $inFooter = true)
    {             
        $this->_where        = $where;
        $this->_scripts      = $scripts;
        $this->_dependencies = $dependencies;
        $this->_version      = $version;
        $this->_inFooter     = $inFooter;
                    
        $this->setRootPath();
        
        if ($this->_where == $this->_front) {                     
            add_action('wp_enqueue_scripts', array($this, 'scriptCallback'));
        } else {
            add_action('admin_print_scripts-'. $this->_where, array($this, 'scriptCallback'));
        }                         
    }
    
    private function setRootPath()
    {
        $this->_rootPath = $this->_where == $this->_front 
                            ? CHT_FRONT_PUBLIC_URI . $this->_directory 
                            : CHT_BACKEND_PUBLIC_URI . $this->_directory;
    }
    
    public function scriptCallback()
    {           
        foreach ($this->_scripts as $name) {
             
            wp_enqueue_script(
                    $name, 
                    $this->_rootPath . $name . $this->_extension, 
                    $this->_dependencies, 
                    $this->_version, 
                    $this->_inFooter);

        }              
    }
}
endif;
