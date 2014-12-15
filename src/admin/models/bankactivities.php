<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
require_once 'iwModelList.php';

/**
 * QuipuList Model
 */
class QuipuModelBankactivities extends IWModelList {

    /**
     *
     * @return <type>
     */
    public function getBankAccounts() {

        $db = $this->getDbo();
        $query = 'SELECT id,name FROM #__quipu_bank_account ORDER BY name';
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	0.12
     */
    protected function populateState($ordering = null, $direction = null) {
        $app = JFactory::getApplication();

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $banco = $this->getUserStateFromRequest($this->context . '.filter.bankaccount', 'filter_bankaccount', null, 'int');
        $this->setState('filter.bankaccount', $banco);



        parent::populateState();
    }

    /**
     * 
     * @return type
     */
    private function getBaseQuery() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('m.id,m.activity_date,m.value_date,m.description,m.amount,m.balance,m.ref,b.id as bankaccount_id,b.name as bankaccount_name, GROUP_CONCAT(CONCAT(i.invoice_number," - ",i.customer) SEPARATOR ", ") as paid_invoices');
        // From the table
        $query->from('#__quipu_bank_activity m');
        $query->innerJoin('#__quipu_bank_account b on m.bank_account_id=b.id');
        $query->leftJoin('#__quipu_invoice_bank_activity_xref x on x.activity_id=m.id');
        $query->leftJoin('#__quipu_invoice i on x.invoice_id=i.id');
        $query->group('m.id');
        $query->order('m.activity_date desc, m.id desc');
        return $query;
    }
    
    
    /**
     * 
     */
    public function getItemsByDateRange($bank_account_id, $from = false, $to = false, $limit = 100){
        $query = $this->getBaseQuery();
        $db = JFactory::getDbo();
        $query->where('b.id=' . $bank_account_id);
        if($from){
            $query->where("m.activity_date>='$from'");
        }
        if($to){
            $query->where("m.activity_date<='$to'");
        }
        $query->order('m.activity_date DESC');        
        $db->setQuery($query,0,$limit);
        $l = $db->loadObjectList();
        
        return $l;
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     */
    protected function getListQuery() {
        $db = JFactory::getDBO();
        $query = $this->getBaseQuery();

        // Aplicar filtro de búsqueda
        if ($this->getState('filter.search') !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'm.description LIKE ' . $token;
            $searches[] = 'b.name LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }

        $id_banco = $this->getState('filter.bankaccount');
        if (is_numeric($id_banco) && $id_banco) {
            $query->where('b.id=' . $id_banco);
        }


        return $query;
    }

    /**
     * 
     */
    public function getFinancials() {

        $data = new JObject();
        $bank_id = $this->getState('filter.bankaccount');

        $dates = IWDate::getLastYearRange();
        $d1 = $dates[0]->toSql(true);
        $d2 = $dates[1]->toSql(true);
        $data->last = $this->getAverages($d1, $d2, $bank_id);

        $quarters = IWDate::getQuarterRanges();
        $data->quarters = array();
        foreach ($quarters as $quarter => $dates) {
            $d1 = $dates[0]->toSql(true);
            $d2 = $dates[1]->toSql(true);
            $data->quarters[$quarter] = $this->getAverages($d1, $d2, $bank_id);
        }
        return $data;
    }

    /**
     * 
     * @param type $d1
     * @param type $d2
     */
    public function getAverages($d1 = false, $d2 = false, $bank_id = false) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('MAX(balance) as COM_QUIPU_MAX_BALANCE,MIN(balance) as COM_QUIPU_MIN_BALANCE,AVG(balance) as COM_QUIPU_AVG_BALANCE');
        $query->from('#__quipu_bank_activity');
        if ($bank_id) {
            $query->where('bank_account_id=' . $bank_id);
        }
        if ($d1) {
            $query->where("value_date >=  '$d1'");
        }
        if ($d2) {
            $query->where("value_date <=  '$d2'");
        }
        $db->setQuery($query);
        $row = $db->loadAssocList();
        //JFactory::getApplication()->enqueueMessage("$query");
        return $row[0];
    }

}
