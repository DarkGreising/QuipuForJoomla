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
class PDFOrderView extends JViewLegacy {

    /**
     * vista HTML 
     *
     * @param $tpl
     */
    public function display($tpl = null) {
        $this->init();
        $file = $this->generate();
        $filename = JText::sprintf('ORDER_%s.pdf', $this->item->order_number);
        IWUtils::downloadFile($file, $filename, 'application/pdf', true, true, true);
        unlink($file);
    }

    /**
     * 
     */
    public function init() {
        $this->asQuotation = IWRequest::getBool('quotation');
        $model = JModelLegacy::getInstance('Order', 'QuipuModel');
        $this->item = $model->getItem();
        $this->details = $model->getDetail($this->item->id);
        $this->customer = $model->getCustomer();

        $this->config = IWConfig::getInstance();
    }

    /**
     * 
     */
    public function generate($destFileName = false) {                
        if (!$destFileName) {
            $destFileName = 'QUIPU_' . time() . '_' . rand() . '.pdf';
        }
        //$config = JFactory::getConfig();
        //$destFile = $config->getValue('config.tmp_path') . DS . $destFileName;
        
        $destFile = IWConfig::getJConfigValue('tmp_path') . DS . $destFileName;        
        $title = JText::sprintf('COM_QUIPU_ORDER_NUMHOLDER', $this->item->order_number);
        
        $pdf = IWPDFService::getInstance();
        $pdf->newDocument($destFile, $title,$this->asQuotation?'COM_QUIPU_PDF_QUOTATION':'COM_QUIPU_PDF_ORDER');

        $html = $this->loadTemplate('order');
        $pdf->addHTMLPage($html);        
        $pdf->closeDocument();
        
        return $destFile;        
        
    }

}