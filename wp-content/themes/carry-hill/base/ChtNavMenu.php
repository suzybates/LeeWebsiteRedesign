<?php
if (!class_exists( 'ChtNavMenu' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtNavMenu
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
