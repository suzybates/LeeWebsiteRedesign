<?php
if (!class_exists( 'ChtSubmenuPage' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtSubmenuPage extends ChtPageAbstract
{
    public function __construct($submenuName, $menuName)
    {
        $this->submenuName = $submenuName;
        $this->menuName    = $menuName;
        $this->slug        = ChtUtils::slugify($this->submenuName);
                
        parent::__construct();      
    }

    public function registerPage()
    {
        $submenuPageHook = add_submenu_page(
                $this->menuName, 
                $this->submenuName,             
                $this->submenuName, 
                $this->capability, 
                $this->slug, 
                array($this, 'callback') // defined in PageAbstract
        );
        
        $this->_addScripts(array($submenuPageHook));
    }
}
endif;
