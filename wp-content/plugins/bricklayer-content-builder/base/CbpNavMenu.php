<?php
if (!class_exists( 'CbpNavMenu' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpNavMenu
{

    private $menus;

    public function __construct()
    {
        $this->menus = func_get_args();
        $this->addAction();
    }

    private function addAction()
    {
        add_action('init', array($this, 'navigation'));
    }
    
    public function navigation()
    {
        foreach ($this->menus as $value) {
            register_nav_menu(strtolower($value) . '-menu', $value . ' Menu');
        }
    }

}
endif;