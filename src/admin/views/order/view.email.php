<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once('pdforderview.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'JSONView.php');

/**
 * Quipu View
 */
class QuipuViewOrder extends JSONView {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        $this->data = new stdClass();
        $this->order = $this->get('Item');
        $this->customer = $this->get('Customer');

        $mailer = JFactory::getMailer();
        //$config = JFactory::getConfig();
        
        $mailer->IsHTML(true);
        
        $sender = array(
            IWConfig::getJConfigValue('mailfrom'),
            IWConfig::getJConfigValue('fromname'));

        $mailer->setSender($sender);

        $email = $this->customer->email;
        
        $mailer->addRecipient($email);
        $body = $this->loadTemplate('email');

        $mailer->setSubject(JText::sprintf('COM_QUIPU_EMAIL_ORDER_SUBJECT', IWConfig::getInstance()->company_name, $this->order->order_number));
        $mailer->setBody($body);

        $pdf = $this->generatePDF();
        $mailer->addAttachment($pdf);

        $result = $mailer->Send();
        if ($result !== true) {
            $this->data->ok = false;
            $this->data->responseText = JText::sprintf('COM_QUIPU_EMAIL_SENT_KO', $email, "$result");
        } else {
            $this->data->ok = true;
            $this->data->responseText = JText::sprintf('COM_QUIPU_EMAIL_SENT_OK', $email);
        }
        unlink($pdf);
        $this->sendResponse($this->data);
    }
    
    /**
     * 
     */
    private function generatePDF(){
        $pdfView = new PDFOrderView();
        foreach($this->_path['template'] as $p){
            $pdfView->_addPath('template', $p);
        }        
        $pdfView->init();
        $file = $pdfView->generate($this->order->order_number.'.pdf');
        
        return $file;
    }

}