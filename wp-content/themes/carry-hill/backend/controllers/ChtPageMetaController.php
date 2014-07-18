<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtPageMetaController extends ChtController
{
    protected $_type               = 'page-meta';
    protected $_title              = 'Page Settings';
    protected $_viewFolder         = 'page-meta';
    protected $_scripts            = array('js/spectrum.js');
    protected $_scriptDependencies = array('jquery', 'jquery-ui-tabs', 'jquery-ui-draggable', 'jquery-ui-droppable');
    protected $_styles             = array('css/main.css', 'css/absolution-stripped-down.css', 'css/spectrum.css', 'css/font-awesome.min.css');
    protected $_ajaxCallback       = 'chtPageMetaAjax';

    public function init()
    {
        
    }

    public function chtPageMetaAjax()
    {
        
    }
    
}
