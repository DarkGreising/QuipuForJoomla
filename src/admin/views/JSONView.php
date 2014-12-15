<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * JSON View
 */
class JSONView extends JViewLegacy {
    
    /**
     * 
     * @param type $items
     */
    public function processItems($items){
        return $items;
    }
    

    /**
     * Quipu view display method
     * @return void
     */
    function display($tpl = null) {

        //process input params from omnigrid
        $page = IWRequest::getInt('page', 1);
        $perpage = IWRequest::getInt('perpage', 20);
        $sorton = IWRequest::getString('sorton', null);
        $sortby = IWRequest::getString('sortby', null);
        
        $start = ($page - 1) * $perpage;
        
        //set limit and limitstart on request, or it will be overriden in JModelList::populateState:                
        IWRequest::setVar('limitstart', $start, 'get');
        IWRequest::setVar('limit', $perpage, 'get');
        
        $m = $this->getModel();
        
        $m->setState('list.limit', $perpage);
        $m->setState('list.start', $start);

        if ($sorton) {
            $m->setState('list.ordering', $sorton);
            $m->setState('list.direction', $sortby);
        }

        // Get data from the model
        $items = $this->processItems($this->get('Items'));
        $pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->params = $this->state->get('params');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        $data_param_name = QUIPU_IS_J3?'rows':'data';
        $data = array(
            'page' => $page,
            'total' => $pagination->total,
            $data_param_name => $items
        );
        
        $this->sendResponse($data);
    }

    /**
     * 
     * @param type $data
     */
    public function sendResponse($data) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');
        JResponse::setHeader('Content-Disposition', 'attachment; filename="' . time() . '.json"');

        echo json_encode($data);

        flush();
    }

}