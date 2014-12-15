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
class PDFPurchaseorderView extends JViewLegacy {

    /**
     * vista HTML 
     *
     * @param $tpl
     */
    public function display($tpl = null) {
        $this->init();
        $file = $this->generate();
        $filename = JText::sprintf('PURCHASEORDER_%s.pdf', $this->item->order_number);
        IWUtils::downloadFile($file, $filename, 'application/pdf', true, true, true);
        unlink($file);
    }

    /**
     * 
     */
    public function init() {
        $model = JModelLegacy::getInstance('Purchaseorder', 'QuipuModel');
        $this->item = $model->getItem();
        $this->details = $model->getDetail();
        $this->supplier = $model->getSupplier();

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
        $title = JText::sprintf('COM_QUIPU_INVOICE_NUMHOLDER', $this->item->order_number);
        
        $pdf = IWPDFService::getInstance();
        $pdf->newDocument($destFile, $title,'COM_QUIPU_PDF_PURCHASEORDER');

        $html = $this->loadTemplate('purchaseorder');
        $pdf->addHTMLPage($html);        
        $pdf->closeDocument();
        
        return $destFile;        
        
    }

}