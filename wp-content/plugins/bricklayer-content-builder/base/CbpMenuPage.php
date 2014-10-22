<?php

if (!class_exists('CbpMenuPage')):

    /**
     * @author Parmenides <krivinarius@gmail.com>
     */
    class CbpMenuPage extends CbpPageAbstract
    {

        public function __construct($menuName, $icon, $menuPriority)
        {
            $this->menuName = $menuName;
            $this->slug     = CbpUtils::slugify($this->menuName);
            $this->icon     = $icon;
            $this->setMenuPriority($menuPriority);

            parent::__construct();
        }

        public function registerPage()
        {
            $menuPageHook = add_menu_page(
                    $this->menuName, $this->menuName, $this->capability, $this->slug, array($this, 'callback'), $this->icon
            );

            $this->_addScripts(array($menuPageHook));
        }
    }

endif;
