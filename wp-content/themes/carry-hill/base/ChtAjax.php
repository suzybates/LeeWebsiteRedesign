<?php
if (!class_exists( 'ChtAjax' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtAjax
{
    private $_ajaxCallbackArray;
    private $_area;

    public function run()
    {
        $this->addAction();
    }
    
    private function addAction()
    {
        $prefix   = isset($this->_area) && $this->_area == 'front' ? 'wp_ajax_nopriv_' : 'wp_ajax_';
        $callback = $this->getAjaxCallback();
        
        add_action($prefix . $callback[1], $callback);
    }
    
    public function setAjaxCallback(array $callback)
    {
        $this->_ajaxCallbackArray = $callback;
    }
    
    public function setArea($area)
    {
        $this->_area = $area;
    }
        
    public function getAjaxCallback()
    {
        return $this->_ajaxCallbackArray;
    }
}
endif;