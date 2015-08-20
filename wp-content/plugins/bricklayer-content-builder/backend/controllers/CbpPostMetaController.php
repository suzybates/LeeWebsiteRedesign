<?php
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpPostMetaController extends CbpController
{
    protected $_type               = 'post-meta';
    protected $_title              = CBP_APP_NAME;
    protected $_viewFolder         = 'post-meta';
    protected $_scripts            = array('js/page-builder.min.js', 'js/spectrum.js', 'js/jquery.nouislider.min.js');
    protected $_scriptDependencies = array('jquery', 'jquery-ui-tabs', 'jquery-ui-draggable', 'jquery-ui-droppable');
    protected $_styles             = array('css/main.css', 'css/cbp-absolution-stripped-down.css', 'css/spectrum.css', 'css/font-awesome.min.css', 'css/jquery.nouislider.css');
    protected $_ajaxCallback       = 'cbpPostMetaAjax';

    public function init()
    {
        
    }

    public function cbpPostMetaAjax()
    {
        echo 'cbpPostMetaAjax';
        die();
    }
}
