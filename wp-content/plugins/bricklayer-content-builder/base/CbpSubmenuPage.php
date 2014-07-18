<?php
if (!class_exists( 'CbpSubmenuPage' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpSubmenuPage extends CbpPageAbstract
{
    public function __construct($submenuName, $menuName, $menuPriority)
    {
        $this->submenuName = $submenuName;
        $this->menuName    = $menuName;
        $this->slug        = CbpUtils::slugify(CBP_APP_PREFIX . $this->submenuName);
        $this->setMenuPriority($menuPriority);
                
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