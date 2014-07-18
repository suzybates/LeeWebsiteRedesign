<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtTab
{
    private $_dir;
    
    public $filename;
    public $title;
    public $slug;

    public function __construct($tab, $dir)
    {
        $this->_dir     = $dir;
        $this->filename = $tab['filename'];
        $this->title    = $tab['title'];
        $this->slug     = ChtUtils::slugify($this->title);
        $this->icon     = '<i class="' . $tab['icon'] . '"></i>';
    }
    
    public function render()
    {
        include $this->_dir . DIRECTORY_SEPARATOR . $this->filename . CHT_VIEW_FILE_TYPE;
    }
    
    public function translate($string)
    {
        return ChtTranslate::translateString($string);
    }
}
