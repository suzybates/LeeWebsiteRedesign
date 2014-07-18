<?php
if (!class_exists( 'CbpWebFonts' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpWebFonts
{
    private $_filename;
    
    public function __construct()
    {
        $this->_filename = CBP_BACKEND_ROOT . DIRECTORY_SEPARATOR . 'fonts.json';
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