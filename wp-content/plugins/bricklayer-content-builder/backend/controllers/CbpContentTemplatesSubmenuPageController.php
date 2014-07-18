<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpContentTemplatesSubmenuPageController extends CbpController
{
    protected $_type         = 'submenu-page';
    protected $_parentSlug   = 'bricklayer';
    protected $_menuPriority = 10;
    protected $_scripts      = array('js/content-template-layout-assignment.js');
    protected $_scriptDependencies = array('jquery', 'media-views'); // media-views is needed for wp-widgets script to work properly
    protected $_styles             = array('css/font-awesome.min.css');
//    protected $_styleDependencies  = array('thickbox');
    protected $_ajaxCallback = 'cbpContentTemplatesSubmenuPageAjax';
    protected $_viewFolder   = 'content-templates-submenu-page';
    protected $_title        = 'Layout Assignment';

    public function init()
    {
        $contentTemplates = array(
            'single'     => 'Single',
            'category'   => 'Category',
            'archive'    => 'Archive',
            '404'        => '404',
            'author'     => 'Author',
            'attachment' => 'Attachment',
            'search'     => 'Search',
            'tags'       => 'Tags',
        );

        $this->view->contentTemplates = $contentTemplates;
    }

    public function cbpContentTemplatesSubmenuPageAjax()
    {

        $doRender = isset($_POST['doRender']) ? $_POST['doRender'] : false;
        $data     = isset($_POST['data']) ? $_POST['data'] : false;


        // this is done because of Firefox caching problem
        if ($doRender) {
            $contentTemplates = array(
                'single'     => 'Single',
                'category'   => 'Category',
                'archive'    => 'Archive',
                '404'        => '404',
                'author'     => 'Author',
                'attachment' => 'Attachment',
                'search'     => 'Search',
                'tags'       => 'Tags',
            );

            $view                   = new CbpView('content-templates-submenu-page', 'render-links');
            $view->templateLinks    = CbpUtils::getOption('template_links');
            $view->contentTemplates = $contentTemplates;
            $view->render();
            
        } elseif ($data) {
            update_option(CBP_APP_PREFIX . 'template_links', $data);
        }



        die();
    }
}
