<?php
defined('_JEXEC') or die('Restricted access');


/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
class IWExcelImporter extends JObject {

    private static $knownTables = array('bankaccounts', 'bankactivities', 'customers', 'suppliers', 'items', 'itemcategories', 'taxes');
    private static $excludeFields = array('_errors');
    private static $yesNoFields = array('ok','active');

    /**
     *
     * @var type 
     */
    private static $singleton;
    private $importedRows = 0;
    private $colCount = 0;
    private $cache = array();

    /**
     *
     * @return IWExcelImporter 
     */
    public static function getInstance() {
        if (!self::$singleton) {
            self::$singleton = new IWExcelImporter();
        }
        return self::$singleton;
    }

    /**
     * 
     */
    private function loadPHPExcel() {
        $path = JPATH_COMPONENT . DS . 'services';
        require_once($path . DS . 'PHPExcel.php');
        include $path . DS . 'PHPExcel/Writer/Excel2007.php';
    }

    /**
     *
     * @param type $file
     * @param type $table 
     */
    public function importFile($file, $table, $delimiter = ';') {
        if (!in_array($table, self::$knownTables)) {
            $this->setError(JText::sprintf('COM_QUIPU_UNKNOWN_TABLE ', $table));
            return false;
        }
        if (!file_exists($file)) {
            $this->setError(JText::_('COM_QUIPU_UNKNOWN_FILE'));
            return false;
        }
        $txt = file_get_contents($file);
        if (!$txt) {
            $this->setError(JText::_('COM_QUIPU_FILE_EMPTY'));
            return false;
        }

        $cols = JText::_('COM_QUIPU_IMPORT_' . strtoupper($table));
        $this->colCount = count(explode(',', $cols));

        $function = "import" . ucfirst($table);

        /* PHPExcel */
        $this->loadPHPExcel();
        $inputFileType = 'CSV';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);

        $objReader->setDelimiter($delimiter);

        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->getActiveSheet();
        $i = 0;
        foreach ($worksheet->getRowIterator() as $row) {
            $i++;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            $buffer = array();
            foreach ($cellIterator as $cell) {
                $buffer[] = $cell->getValue();
            }
            if (count($buffer) == $this->colCount) {
                if ($this->$function($buffer)) {
                    $this->importedRows++;
                }
            } else if ($delimiter == ',') {
                //If col count doesn't match using ',', wie try again using ';' as separator
                return $this->importFile(isset($orig_file) ? $orig_file : $file, $table, ';');
            } else {
                $msg = JText::sprintf('COM_QUIPU_IMPORT_FILE_FORMAT_ERROR', count($buffer), $this->colCount, $cols);
                $this->setError($msg);
                break;
            }
        }
        /* fin PHPExcel */

        return $i == $this->importedRows;
    }

    /**
     *
     * @param type $file 
     */
    private function convertToUTF8($txt, $from_encoding = false) {
        if ($from_encoding == 'ISO-8859-1') {
            $txt = utf8_encode($txt);
        } else {
            $txt = mb_convert_encoding($txt, 'UTF-8', $from_encoding);
        }

        $file = tempnam(sys_get_temp_dir(), 'QuipuJ');
        file_put_contents($file, $txt);
        return $file;
    }

    /**
     *
     * @return type 
     */
    public function getImportedRows() {
        return $this->importedRows;
    }

    /**
     *
     * @param type $table
     * @param type $file 
     */
    public function exportTable($table, $file) {
        /**
         * @todo
         */
    }

    /**
     * 
     */
    public static function createPHPExcelObject($title = '', $description = '') {
        self::getInstance()->loadPHPExcel();
        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator('Quipu for Joomla!');
        $objPHPExcel->getProperties()->setTitle(JText::_($title));
        $objPHPExcel->getProperties()->setDescription(JText::_($description));

        return $objPHPExcel;
    }

    /**
     * 
     */
    public static function generateExcel2007($filepath, $data, $header = array()) {
        $objPHPExcel = self::createPHPExcelObject();

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $row = 1;
        $col = 0;
        if (count($header)) {
            $col = 0;
            foreach ($header as $colName) {
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $colName);
            }
            $row++;
        }
        foreach ($data as $line) {
            $col = 0;
            foreach ($line as $value) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $value);
            }
            $row++;
        }

        //No funciona :-(
        foreach ($objPHPExcel->getActiveSheet()->getColumnDimensions() as $col) {
            $col->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->calculateColumnWidths();

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($filepath);

        return true;
    }

    /**
     *
     * @param type $filepath
     * @param type $data
     * @param type $header
     * @return type 
     */
    public static function generateMSCSV($filepath, $data, $header = array(), $fieldsep = ',') {
        if ($fp = fopen($filepath, 'w')) {
            $show_header = true;
            if (empty($header)) {
                $show_header = false;
                reset($data);
                $line = current($data);
                if (!empty($line)) {
                    reset($line);
                    $first = current($line);
                    if (substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first)) {
                        array_shift($data);
                        array_shift($line);
                        if (empty($line)) {
                            fwrite($fp, "\"{$first}\"\r\n");
                        } else {
                            fwrite($fp, "\"{$first}\"$fieldsep");
                            fputcsv($fp, $line);
                            fseek($fp, -1, SEEK_CUR);
                            fwrite($fp, "\r\n");
                        }
                    }
                }
            } else {
                reset($header);
                $first = current($header);
                if (substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first)) {
                    array_shift($header);
                    if (empty($header)) {
                        $show_header = false;
                        fwrite($fp, "\"{$first}\"\r\n");
                    } else {
                        fwrite($fp, "\"{$first}\"$fieldsep");
                    }
                }
            }
            if ($show_header) {
                fputcsv($fp, $header, $fieldsep);
                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }
            foreach ($data as $line) {
                fputcsv($fp, $line, $fieldsep);
                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }
            fclose($fp);
        } else {
            return false;
        }
        return true;
    }

    /**
     * 
     */
    public static function getColNames($rows) {
        $keys = array();
        $row = $rows[0];
        if (is_object($row)) {
            $row = get_object_vars($row);
        }
        if (is_array($row)) {
            foreach (array_keys($row) as $k) {
                $keys[] = str_replace('_', ' ', $k);
            }
        }
        return $keys;
    }

    /**
     *
     * @param type $objects
     * @return array 
     */
    public static function cleanObjects($objects) {
        $result = array();
        foreach ($objects as $o) {
            $vars = get_object_vars($o);
            $defV = array();
            foreach ($vars as $k => $v) {
                //We don't export:
                /*
                 * - Fields starting with id_
                 * - Fields with name "dummy" (used to fix col count in selects with union)
                 * - Any field listed in excludedFields array
                 */
                if (strpos($k, 'id') !== 0 && strpos($k, 'dummy') !== 0 && !in_array($k, self::$excludeFields)) {
                    if (strpos($k, 'date') !== FALSE) {
                        $defV[$k] = IWDate::_($v);
                    } else if (in_array($k, self::$yesNoFields)) {
                        $defV[$k] = $v ? JText::_('COM_QUIPU_YES') : JText::_('COM_QUIPU_NO');
                    } else {
                        $defV[$k] = strip_tags($v);
                    }
                }
            }
            $result[] = $defV;
        }
        return $result;
    }

    // - START IMPORT FUNCTIONS:

    /**
     * @param type $file
     * @return type
     */
    private function importCustomers($csvRow) {
        $o = new JObject();

        $o->name = $csvRow[0];
        $o->company_name = $csvRow[1];
        $o->address = $csvRow[2];
        $o->memo = $csvRow[3];
        $o->vatno = $csvRow[4];
        $o->phone = $csvRow[5];
        $o->email = $csvRow[6];
        $o->contact = $csvRow[7];

        $db = JFactory::getDbo();
        if ($db->insertObject('#__quipu_customer', $o)) {
            return true;
        } else {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }
    /**
     * @param type $file
     * @return type
     */
    private function importSuppliers($csvRow) {
        $o = new JObject();

        $o->name = $csvRow[0];
        $o->company_name = $csvRow[1];
        $o->address = $csvRow[2];
        $o->memo = $csvRow[3];
        $o->vatno = $csvRow[4];
        $o->phone = $csvRow[5];
        $o->email = $csvRow[6];
        $o->contact = $csvRow[7];

        $db = JFactory::getDbo();
        if ($db->insertObject('#__quipu_supplier', $o)) {
            return true;
        } else {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }

    /**
     * @param type $file
     * @return type
     */
    private function importItemcategories($csvRow) {
        $o = new JObject();

        $o->name = $csvRow[0];


        $db = JFactory::getDbo();
        if ($db->insertObject('#__quipu_item_category', $o)) {
            return true;
        } else {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }

    /**
     * @param type $file
     * @return type
     */
    private function importItems($csvRow) {
        $o = new JObject();

        $token = 'category_' . $csvRow[0];
        $cat_id = $this->cache[$token];
        if (!$cat_id) {
            $cat_id = JTable::getInstance('ItemCategory', 'QuipuTable')->getCategoryIdByName($csvRow[0]);
            $this->cache[$token] = $cat_id;
        }
        if(!$cat_id){
            $this->setError(JText::sprintf('COM_QUIPU_CATEGORY_NOT_FOUND', $csvRow[0]));
            return false;
        }        
        $token = 'tax_' . $csvRow[0];
        $tax_id = $this->cache[$token];
        if (!$tax_id) {
            $tax_id = JTable::getInstance('Tax', 'QuipuTable')->getTaxIdByFactor(IWUtils::parseFloat($csvRow[1]));
            $this->cache[$token] = $tax_id;
        }
        if(!$tax_id){
            $this->setError(JText::sprintf('COM_QUIPU_TAX_NOT_FOUND', $csvRow[1]));
            return false;
        }

        $o->category_id = $cat_id;
        $o->tax_id = $tax_id;
        $o->name = $csvRow[2];
        $o->cost_price_wotax = IWUtils::parseFloat($csvRow[3]);
        $o->last_sell_price_wotax = IWUtils::parseFloat($csvRow[4]);

        $db = JFactory::getDbo();
        if ($db->insertObject('#__quipu_item', $o)) {
            return true;
        } else {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }
    
    /**
     * 
     * @param type $csvRow
     * @return boolean
     */
    private function importBankactivities($csvRow) {
        $o = new JObject();

        $token = 'bank_account_' . $csvRow[0];
        $bank_id = $this->cache[$token];
        if (!$bank_id) {
            $bank_id = JTable::getInstance('BankAccount', 'QuipuTable')->getBankIdByName($csvRow[0]);
            $this->cache[$token] = $bank_id;
        }

        $o->bank_account_id = $bank_id;
        
        $o->activity_date = IWDate::parseToMySQL($csvRow[1]);
        $o->value_date = IWDate::parseToMySQL($csvRow[2]);
        $o->description = $csvRow[3];
        $o->amount = IWUtils::parseFloat($csvRow[4]);
        $o->balance = IWUtils::parseFloat($csvRow[5]);

        $db = JFactory::getDbo();
        if(JTable::getInstance('BankActivity','QuipuTable')->activityIsRegistered($o->getProperties())){
            $this->setError(JText::sprintf('COM_QUIPU_IMPORT_DUPLICATED_REGISTRY',implode(',',$csvRow)));
            return false;
        }
        else if ($db->insertObject('#__quipu_bank_activity', $o)) {
            return true;
        } else {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }

}
