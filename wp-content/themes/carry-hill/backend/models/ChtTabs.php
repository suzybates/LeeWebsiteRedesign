<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtTabs
{
    private $_tabs = array();
    private $_relations;

    public function setRelations(array $relations)
    {
        $this->_relations = $relations;
    }
    
    public function getTabs($tabs, $dir)
    {        
        foreach ($tabs as $tabArr) {
            
            $filename = $tabArr['filename'];
            
            $tab = new ChtTab($tabArr, $dir);
            
            if (isset($this->_relations[$filename])) {
                $tab->template = $this->_relations[$filename];
                $tab->templateClass = preg_replace('/page-|.php/', '', $this->_relations[$filename]) . '-show';
            }
            
            $this->_tabs[] = $tab;
        }
        return $this->_tabs;
    }
}
