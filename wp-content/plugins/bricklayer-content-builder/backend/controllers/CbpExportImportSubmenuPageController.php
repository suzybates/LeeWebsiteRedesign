<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpExportImportSubmenuPageController extends CbpController
{

    protected $_type         = 'submenu-page';
    protected $_parentSlug   = 'bricklayer';
    protected $_menuPriority = 10;
    protected $_ajaxCallback = 'cbpExportImportSubmenuPageAjax';
    protected $_viewFolder   = 'export-import-submenu-page';
    protected $_title        = 'Export/Import';

    public function init()
    {
        add_action('init', array($this, 'onFormSubmit'));
    }

    public function onFormSubmit()
    {
        if (isset($_POST['cbp-export-settings'])) {
            if (check_admin_referer('cbp-export-settings')) {
                $this->export();
            }
        }

        if (isset($_POST['cbp-import-settings'])) {
            if (check_admin_referer('cbp-import-settings')) {
                $overwriteTemplateLinks = isset($_POST['cbp-overwrite-template-links']) ? true : false;
                CbpSettings::setOverwriteTemplateLinks($overwriteTemplateLinks);
                $this->import();
            }
        }
    }

    protected function export()
    {
        CbpSettings::prepare();

        // Slider name
        $name = CBP_APP_NAME . ' Export ' . date('Y-m-d') . ' at ' . date('H.i.s') . '.json';

        // Send output and force download
        header('Content-type: application/force-download');
        header('Content-Disposition: attachment; filename="' . str_replace(' ', '_', $name) . '"');
        die(base64_encode(json_encode(CbpSettings::getSettingsJson())));
    }
    
    protected function import()
    {
        // Check export file if any
	if(!is_uploaded_file($_FILES['import_file']['tmp_name'])) {
		header('Location: '.$_SERVER['REQUEST_URI'].'&error=1');
		die('No data received.');
	}
        
        // Get decoded file data
	$data = base64_decode(file_get_contents($_FILES['import_file']['tmp_name']));
        
        if (CbpSettings::save($data)) {
            header('Location: '.$_SERVER['REQUEST_URI'].'&save=1');
        }
    }

    public function cbpExportImportSubmenuPageAjax()
    {
        die();
    }

}
