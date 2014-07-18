<?php
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpPageMetaController extends CbpController
{
    protected $_type               = 'page-meta';
    protected $_title              = CBP_APP_NAME;
    protected $_viewFolder         = 'page-meta';
    protected $_scripts            = array('js/page-builder.min.js', 'js/spectrum.js');
    protected $_scriptDependencies = array('jquery', 'jquery-ui-tabs', 'jquery-ui-draggable', 'jquery-ui-droppable');
    protected $_styles             = array('css/main.css', 'css/cbp-absolution-stripped-down.css', 'css/spectrum.css', 'css/font-awesome.min.css');
    protected $_ajaxCallback       = 'cbpPageMetaAjax';

    public function init()
    {
        
    }

    public function cbpPageMetaAjax()
    {
        echo 'pageMetaAjax';
        die();
    }
}
