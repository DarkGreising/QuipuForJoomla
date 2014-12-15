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
 * Quipu Controller
 */
class QuipuControllerConfig extends JControllerForm {

    public $isConfig = true;

    /**
     *
     * @param type $key
     * @param type $urlVar 
     */
    public function save($key = null, $urlVar = null) {
        $file = IWRequest::getVar('jform', null, 'files', 'array');
        $data = IWRequest::getVar('jform', array(), 'post', 'array');
        $sync = IWRequest::getVar('sync', array(), 'post', 'array');
        
        //process components synchronization
        $data['sync'] = json_encode($sync);
       
        IWRequest::setVar('jform', $data, 'post', true);
        $app = JFactory::getApplication();
        //save logo
        if (array_key_exists('logo', $file)) {
            $logo = $file['logo'];
            require_once(JPATH_COMPONENT . DS . 'services' . DS . 'image_functions.php');

            $filename = IWFile::makeSafe($logo['name']);
            $src = $logo['tmp_name'];
            $ext = strtolower(IWFile::getExt($filename));

            if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {

                $id = $data['id']?$data['id']:$data['cif'];

                $path = 'data/' . IWUtils::validFolderName(md5("$id")) . '/logo.' . $ext;

                $dest = JPATH_COMPONENT . DS . $path;

                if (IWFile::upload($src, $dest)) {                    
                    $data['logo'] = $path;
                    if($this->input){
                        $this->input->post->set('jform', $data);
                    }
                    else{                        
                        IWRequest::setVar('jform', $data, 'post', true);
                    }
                    
                } else {
                    $app->enqueueMessage(JText::_('COM_QUIPU_LOGO_ERROR'), 'error');
                }
            } else {
                $app->enqueueMessage(JText::_('COM_QUIPU_LOGO_ERROR_EXTENSION'), 'error');
            }
        }

        $ok = parent::save();
        if (!$ok) {
            /** @todo delete logo */
        }
        $this->setRedirect(JRoute::_('index.php?option=com_quipu&view=panel', false));
        return $ok;
    }

    /**
     * 
     * @param type $key
     */
    public function cancel($key = null) {
        if (parent::cancel($key)) {
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=panel' . $this->getRedirectToListAppend(), false
                    )
            );
        }
    }

}
