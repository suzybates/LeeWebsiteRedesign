<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtSidebarGenerator
{
    private $_optionName = 'cbp_sidebars';

    public function init()
    {
        $existingSidebars = $this->getSidebars();

        $this->_generateSidebars($existingSidebars);
    }

    public function getSidebars()
    {
        return get_option(CHT_APP_PREFIX . $this->_optionName);
    }

    public function setDefaultSidebars(array $sidebars)
    {
        $this->_generateSidebars($sidebars);
    }

    public function generate($sidebars)
    {
        $this->_generateSidebars($sidebars);
        $this->_updateOption($sidebars);
    }

    private function _generateSidebars($sidebars)
    {
        if (is_array($sidebars)) {
            foreach ($sidebars as $sidebar) {
                $this->_genSidebar($sidebar);
            }
        }
    }

    private function _genSidebar($name)
    {
        $args = array(
            'name'          => $name,
            'id'            => 'sidebar-' . ChtUtils::slugify($name),
            'description'   => 'Sidebar ' . $name,
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3><hr />',
            'real_name'     => $name // not default option
        );
        register_sidebar($args);
    }

    private function _updateOption($sidebars)
    {
        update_option(CHT_APP_PREFIX . $this->_optionName, $sidebars);
    }
}
