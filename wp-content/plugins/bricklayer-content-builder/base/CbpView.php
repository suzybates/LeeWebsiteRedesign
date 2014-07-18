<?php
if (!class_exists( 'CbpView' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpView
{
    private $parentClass;
    private $fileName;

    public function __construct($class, $fileName = null)
    {
        $this->parentClass = CbpUtils::toLower(CbpUtils::spaceOutCamelCase($class));
        $this->fileName    = $fileName ? $fileName : $this->parentClass;
    }
    
    private function getView()
    {
        include CBP_VIEWS_DIR . $this->parentClass . DIRECTORY_SEPARATOR . $this->fileName . CBP_VIEW_FILE_TYPE;
    }
    
    public function render()
    {
        $this->getView();
    }
    
    public function translate($string)
    {
        return CbpTranslate::translateString($string);
    }

}
endif;