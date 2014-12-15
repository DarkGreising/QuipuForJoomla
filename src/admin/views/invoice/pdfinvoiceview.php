<?php
defined('_JEXEC') or die('Restricted access');


/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
//-- No direct access

//-- Import the Joomla! view library
jimport('joomla.application.component.view');

/**
 *
 *
 * @package Quipu
 */
class PDFInvoiceView extends JViewLegacy {

    /**
     * vista HTML 
     *
     * @param $tpl
     */
    public function display($tpl = null) {
        $this->init();
        $file = $this->generate();
        $filename = JText::sprintf('INVOICE_%s.pdf', $this->item->invoice_number);
        IWUtils::downloadFile($file, $filename, 'application/pdf', true, true, true);
        unlink($file);
    }

    /**
     * 
     */
    public function init() {
        $model = JModelLegacy::getInstance('Invoice', 'QuipuModel');
        $this->item = $model->getItem();
        $this->details = $model->getDetail($this->item->id);
        $this->config = IWConfig::getInstance();
        
        if($this->item->bankaccount_id){
            $this->item->payment_account = JModelLegacy::getInstance('BankAccount','QuipuModel')->getItem($this->item->bankaccount_id);
        }
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
        $title = JText::sprintf('COM_QUIPU_INVOICE_NUMHOLDER', $this->item->invoice_number);
        
        $pdf = IWPDFService::getInstance();
        $pdf->newDocument($destFile, $title);

        $html = $this->loadTemplate('invoice');
        $pdf->addHTMLPage($html);        
        $pdf->closeDocument();
        
        return $destFile;
    }

}


