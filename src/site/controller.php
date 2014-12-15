<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class QuipuController extends JControllerLegacy {

    /**
     * 
     * @param boolean $cachable
     * @param type $urlparams
     * @return \QuipuController
     */
    public function display($cachable = false, $urlparams = false) {
        $cachable = true;

        // Get the document object.
        $document = JFactory::getDocument();

        // Set the default view name and format from the Request.
        $vName = JRequest::getCmd('view', 'customer');
        JRequest::setVar('view', $vName);

        $user = JFactory::getUser();



        $safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
            'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD');

        parent::display($cachable, $safeurlparams);

        return $this;
    }

}
