<?php
if (!class_exists( 'ChtWebFonts' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtWebFonts
{
    private $_filename;
    
    public function __construct()
    {
        $this->_filename = CHT_BACKEND_ROOT . DIRECTORY_SEPARATOR . 'fonts.json';
    }

    public function getFonts()
    {
        $familyNames[] = 'default';
        if (file_exists($this->_filename) ) {
            
            $fileContents = file_get_contents($this->_filename);
            $arrOfObjs    = json_decode($fileContents);
            
            foreach ($arrOfObjs->items as $obj) {
                $familyNames[] = $obj->family;
            }
        }
        return $familyNames;
    }
}
endif;
