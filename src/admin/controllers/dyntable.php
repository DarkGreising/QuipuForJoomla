<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 *
 * @package		iwERP
 * 
 * 
 */
abstract class QuipuControllerDyntable extends JControllerLegacy {

    protected $json = array();
    protected $_tableName = false;

    /**
     * 
     */
    protected function edit() {
        if (count($this->rowIDs)) {
            $rowID = $this->rowIDs[0];
            $this->row->load($rowID);
            $field = array_keys($this->data[$rowID]);
            $field = $field[0];
            $this->preProcessInput($this->data[$rowID]);
            if (!$this->row->bind($this->data[$rowID])) {
                return JError::raiseWarning(500, $row->getError());
            }

            if ($this->tableHasField('last_update')) {
                $hoy = new IWDate();
                $this->row->ultima_modificacion = $hoy->toSql();
            }

            $this->afterBind();

            if (!$this->row->store()) {
                JError::raiseError(500, $row->getError());
            }
            $this->json['affected'] = 1;
            $this->json['field'] = $field;
            $this->json['value'] = $this->data[$rowID][$field];
            $this->json['formatted'] = $this->getFormatted($field, $this->data[$rowID][$field]);
            $this->json['auto'] = 'id';
            $this->json['key'] = $rowID;            
        }
    }

    protected abstract function afterBind();
    protected abstract function afterStore();

    /**
     *
     * @param type $field
     * @param type $value 
     */
    protected function getFormatted($field, $value) {
        if (in_array($field, $this->dateFields)) {
            $txt = IWDate::_($value);
        } 
        $txt = isset($txt) ? $txt : $value;

        return IWUtils::sumarize($txt,25);
    }

    /**
     *
     * @return type 
     */
    protected function insert() {
        $field = array_keys($this->data);
        $field = $field[0];
        $this->preProcessInput($this->data);
        if (!$this->row->bind($this->data)) {
            return JError::raiseWarning(500, $row->getError());
        }
        
        $hoy = new IWDate();
        if ($this->tableHasField('last_update')) {
            $this->row->last_update = $hoy->toSql();
        }
        if ($this->tableHasField('created')) {
            $this->row->created = $hoy->toSql();
        }

        if (!$this->row->store()) {
            JError::raiseError(500, $row->getError());
        }


        $this->json['affected'] = 1;
        $this->json['field'] = $field;
        $this->json['value'] = $this->data[$field];
        if(array_key_exists('ord', $this->data)){
            $this->json['ord'] = $this->data['ord'];
        }        
        $this->json['formatted'] = $this->getFormatted($field, $this->data[$field]);
        $this->json['auto'] = 'id';
        $this->json['key'] = $this->row->getDbo()->insertid();
    }

    /**
     * 
     */
    protected function delete() {
        $id = $this->delete[0];
        $this->row->delete($id);
        $this->json['key'] = $id;
    }

    /**
     * 
     */
    protected function getTableName() {
        if (!$this->_tableName) {
            $this->_tableName = IWRequest::getString('t');
            if (!$this->_tableName) {
                JError::raiseError(500, 'Faltan datos...');
            }
        }
        return $this->_tableName;
    }

    /**
     * Generic data, like FKs should come in http params named
     * m1, m2, etc folowing the same order as table colums.
     * 
     */
    protected function loadMasterData() {
        $db = JFactory::getDbo();
        $db->setQuery('SHOW FULL COLUMNS FROM ' . $db->quoteName($db->escape($this->row->getTableName())));
        $fields = $db->loadObjectList();

        $datetypes = array('date', 'datetime', 'timestamp');
        $this->dateFields = array();

        for ($i = (count($fields) - 1); $i >= 0; $i--) {
            $fld = $fields[$i];
            if (in_array($fld->Type, $datetypes)) {
                $this->dateFields[] = $fld->Field;
            }
            $p = "m$i";
            $v = IWRequest::getString($p);
            if ($v) {
                $this->data[$fld->Field] = $v;
            }
        }
    }

    /**
     * 
     */
    private function preProcessInput(&$values) {
        foreach ($values as $field => $value) {
            //Fechas
            if (in_array($field, $this->dateFields)) {
                $v = IWDate::parseToMySQL($value);
                $values[$field] = $v;
            }
        }
    }

   /**
    *
    * @param <type> $fName
    */
    private function tableHasField($fName){
        return array_key_exists($fName, $this->fieldNames);
    }

    /**
     * 
     */
    public function dyntable() {
        //edit | delete | insert

        $this->row = JTable::getInstance($this->getTableName(), 'QuipuTable');
        $this->fieldNames = $this->row->getFields();
        $this->data = IWRequest::getVar('data', array(), 'post', 'array');
        $this->delete = IWRequest::getVar('delete', array(), 'post', 'array');
        $this->rowIDs = IWRequest::getVar('edit', array(), 'post', 'array');
        $this->loadMasterData();

        if (IWRequest::getBool('edit', false)) {
            $this->json['action'] = 'edit';
            $this->edit();
        }
        if (IWRequest::getBool('insert', false)) {
            $this->json['action'] = 'insert';
            $this->insert();
        }
        if (count($this->delete)) {
            $this->json['action'] = 'delete';
            $this->delete();
        }

        $this->afterStore();
        
        echo json_encode($this->json);
        exit();
    }

}