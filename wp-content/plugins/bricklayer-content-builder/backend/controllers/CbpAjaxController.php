<?php

/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpAjaxController
{

    public function __construct()
    {
        // only backend
        $ajax = new CbpAjax();
        $ajax->setAjaxCallback(array($this, 'cbpAjaxGetTemplate'));
        $ajax->run();

        // only backend
        $ajax = new CbpAjax();
        $ajax->setAjaxCallback(array($this, 'cbpAjaxSaveTemplate'));
        $ajax->run();
        
        // only backend
        $ajax = new CbpAjax();
        $ajax->setAjaxCallback(array($this, 'cbpGetAttachmentImg'));
        $ajax->run();
    }

    public function cbpAjaxGetTemplate()
    {

        $templateId = $_POST['templateId'];

        if (isset($templateId) && $templateId) {

            $template = get_post($templateId);

            echo json_encode($template);
        }
        die();
    }

    public function cbpAjaxSaveTemplate()
    {


        $templateName    = $_POST['templateName'];
        $templateContent = $_POST['temeplateContent'];


        if (isset($templateName) && $templateName && isset($templateContent) && $templateContent) {

            $template = array(
                'post_title'   => wp_strip_all_tags($templateName),
                'post_content' => stripcslashes($templateContent),
                'post_type'    => 'templates',
                'post_status'  => 'publish'
            );
            $templateId = wp_insert_post($template);
            
            if ($templateId) {
                echo json_encode(array('status' => 'ok'));
            } else {
                echo json_encode(array('status' => 'error'));
            }
        }
        die();
    }
    
    public function cbpGetAttachmentImg($param)
    {
        $attachmentId    = $_POST['id'];
        
        echo json_encode(wp_prepare_attachment_for_js($attachmentId));
//        echo json_encode(wp_upload_dir());
        
        die();
    }

}
