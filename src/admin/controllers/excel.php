<?php

//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * 
 */
class QuipuControllerExcel extends JControllerLegacy {

    /**
     * @todo: permisos
     */
    public function import() {
        $this->table = IWRequest::getWord('t', false);
        
        if (!$this->table) {
            JError::raiseError(500, JTExt::_('COM_QUIPU_PARAMETERS_MISSING'));
        }
        $this->data = IWRequest::getVar('file', array(), 'files', 'array');        

        $url = 'index.php?option='.IWRequest::getCmd('option').'&view=import&tmpl=component&t=' . $this->table;
        if ($this->loadCSV()) {
            JFactory::getApplication()->setUserState('import.csv.result', $this->rowCount);
        } else {
            JFactory::getApplication()->setUserState('import.csv.result', $this->getError());
        }

        $this->setRedirect($url);
    }


    /**
     *
     * @return type 
     */
    private function loadCSV() {
        //$msg = print_r($this->data, true);
        if (!$this->data['error'] && $this->data['size']) {
            $csv = IWExcelImporter::getInstance();
            $res = $csv->importFile($this->data['tmp_name'], $this->table);
            $e = $csv->getErrors();
            if (!$res) {
                //Si no se cargaron todos, informo de cuantos pasaron.
                if($csv->getImportedRows()){
                    array_push($e, JText::sprintf('COM_QUIPU_IMPORT_INCOMPLETE', $csv->getImportedRows()));
                }
                else{
                    array_push($e, JText::sprintf('COM_QUIPU_IMPORT_NOROW'));
                }
                
            }
            $this->setError($e);
            $this->rowCount = $csv->getImportedRows();
            return $res;
        } else {
            $this->setError(JText::_('COM_QUIPU_IMPORT_MISSING_FILE'));
        }

        return false;
    }

}