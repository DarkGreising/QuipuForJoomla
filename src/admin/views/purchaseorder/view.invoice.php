<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
//-- No direct access
defined('_JEXEC') or die('=;)');

//-- Import the Joomla! view library
jimport('joomla.application.component.view');

/**
 *
 *
 * @package Quipu
 */
class QuipuViewPurchaseorder extends JViewLegacy {

    /**
     * vista HTML 
     *
     * @param $tpl
     */
    public function display($tpl = null) {
        $this->init();
        $path = $this->item->invoice_file;
        $file = JPATH_COMPONENT . DS . $path;
        $ext = IWFile::getExt($file);
        $filename = JText::sprintf('PURCHASE_INVOICE_%s.' . $ext, $this->item->order_number);
        $contentType = IWUtils::getFileMimeType($file);
        IWUtils::downloadFile($file, $filename, $contentType, true, true, false);        
    }

    /**
     * 
     */
    public function init() {
        $model = JModelLegacy::getInstance('Purchaseorder', 'QuipuModel');
        $this->item = $model->getItem();

        $this->config = IWConfig::getInstance();
    }



}