<?php
if (!class_exists( 'ChtView' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtView
{
    private $parentClass;
    private $fileName;

    public function __construct($class, $fileName = null)
    {
        $this->parentClass = ChtUtils::toLower(ChtUtils::spaceOutCamelCase($class));
        $this->fileName    = $fileName ? $fileName : $this->parentClass;
    }
    
    private function getView()
    {
        include CHT_VIEWS_DIR . $this->parentClass . DIRECTORY_SEPARATOR . $this->fileName . CHT_VIEW_FILE_TYPE;
    }
    
    public function render()
    {
        $this->getView();
    }
    
    public function translate($string)
    {
        return Translate::translateString($string);
    }

}
endif;
