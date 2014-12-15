<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright   Nacho Brito
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

jimport('joomla.application.component.view');

abstract class ExcelView extends JViewLegacy {

    /**
     *
     * @param type $tpl 
     */
    function display($tpl = null) {
        $rows = IWExcelImporter::cleanObjects($this->getRowObjects());
        /*
         * Col names will be derived from object properties, unless the child view
         * gives values for them.
         */
        $colNames = isset($this->colNames)?$this->colNames:IWExcelImporter::getColNames($rows);        

        $path = tempnam(sys_get_temp_dir(), 'QUIPU');
        $data = array();
        foreach ($rows as $row) {            
            $values = array();
            foreach($row as $k=>$v){
                $values[] = $v;
            }
            $data[] = $values;
        }
        
        if (IWExcelImporter::generateExcel2007($path, $data, $colNames)) {
            $fnPrefix = $this->fileNamePrefix?$this->fileNamePrefix:IWRequest::getWord('view');
            //IWUtils::downloadFile($path, $fnPrefix.'-'.time().'.xlsx', 'application/vnd.ms-excel', FALSE,FALSE);            
            IWUtils::downloadFile($path, $fnPrefix.'-'.time().'.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', FALSE,FALSE);            
            unlink($path);
            exit();
        } else {
            JError::raiseError(500, JText::_('COM_QUIPU_EXPORT_EXCEL_ERROR'));
        }
        /*
          echo '<pre>';
          print_r($rows);
          echo '</pre>';
          exit();
         */
    }


    /**
     *
     * @param type $items
     * @param type $cols
     * @return type 
     */
    protected function removeUnusedProps($items, $cols) {
        $propNames = array_keys(get_object_vars($items[0]));
        foreach ($items as $item) {
            foreach ($propNames as $p) {
                if (!in_array($p, $cols)) {
                    unset($item->$p);
                }
            }
        }
        return $items;
    }

    /**
     * 
     */
    abstract function getRowObjects();

}
