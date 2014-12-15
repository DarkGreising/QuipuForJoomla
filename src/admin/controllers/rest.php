<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 */
class RestController extends JControllerLegacy
{

    protected $table = false;
    protected $table_prefix = 'JTable';
    protected $response_object;

    /**
     * 
     * @param string $method
     */
    protected function before($method)
    {
        $fName = 'before' . ucfirst($this->table) . strtoupper($method);
        if (method_exists($this, $fName))
        {
            $this->$fName();
        }
    }

    /**
     * 
     * @param string $method
     */
    protected function after($method)
    {
        $fName = 'after' . ucfirst($this->table) . strtoupper($method);
        if (method_exists($this, $fName))
        {
            $this->$fName();
        }
    }

    /**
     * 
     * @return JTable
     */
    protected function getJTable()
    {
        return JTable::getInstance($this->table, $this->table_prefix);
    }

    /**
     * 
     */
    protected function processRequest()
    {
        $this->response_object = new stdClass();
        $this->txt_prefix = strtoupper(IWRequest::getCmd('option') . '_');

        if ($this->table)
        {
            //Will have to be set to true in the processXXXX() method.
            $this->response_object->ok = false;
            $method = IWRequest::getMethod();
            $this->before($method);
            switch ($method)
            {
                case 'GET':
                    $this->processGet();
                    break;
                case 'POST':
                    $this->processPost();
                    break;
                case 'PUT':
                    $this->processPut();
                    break;
                case 'DELETE':
                    $this->processDelete();
                    break;
                default:
                    JError::raiseError(500, 'Unknown method: ' . $method);
            }
            $this->after($method);

            $document = JFactory::getDocument();
            $document->setMimeEncoding('application/json');
            JResponse::setHeader('Content-Disposition', 'attachment; filename="' . time() . '.json"');

            echo json_encode($this->response_object);
            flush();

            JFactory::getApplication()->close();
        } else
        {
            JError::raiseError(500, 'Request incomplete.');
        }
    }

    /**
     * 
     */
    private function processGet()
    {
        
    }

    /**
     * 
     */
    private function processPost()
    {
        $params = IWRequest::getVar($this->table);
        $o = $this->getJTable();
        $pkName = $o->getKeyName();
        $pk = $params[$pkName];
        if (is_numeric($pk) && $pk && $o->load($pk))
        {
            $filter = JFilterInput::getInstance(null, null, 1, 1);
            $props = $o->getFields();
            $changed = false;
            foreach ($props as $prop)
            {
                $pName = $prop->Field;
                if ($pName == $pkName)
                {
                    continue;
                }
                if (isset($params[$pName]))
                {
                    $var = $filter->clean($params[$pName], 'string');
                    if ($var)
                    {
                        $o->$pName = $var;
                        $changed = true;
                    }
                }
            }
            if ($changed)
            {
                if (!$o->store())
                {
                    JError::raiseError(500, JText::_($this->txt_prefix . 'REST_500_PROPS'));
                }
                $this->response_object->ok = true;
            } else
            {
                //No changes => it's ok
                $this->response_object->ok = true;
            }
        } else
        {
            JError::raiseError(404, JText::_($this->txt_prefix . 'REST_404'));
        }
    }

    /**
     * 
     */
    private function processPut()
    {
        
    }

    /**
     * 
     */
    private function processDelete()
    {
        
    }

}
