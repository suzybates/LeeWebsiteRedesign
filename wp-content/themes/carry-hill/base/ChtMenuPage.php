<?php
if (!class_exists( 'ChtMenuPage' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtMenuPage extends ChtPageAbstract
{
    public function __construct($menuName)
    {
        $this->menuName = $menuName;
        $this->slug     = ChtUtils::slugify($this->menuName);
                
        parent::__construct();     
    }

    public function registerPage()
    {
        $menuPageHook = add_menu_page(
                $this->menuName, 
                $this->menuName, 
                $this->capability, 
                $this->slug, 
                array($this, 'callback')
        );
        
        $this->_addScripts(array($menuPageHook));
    }
}
endif;