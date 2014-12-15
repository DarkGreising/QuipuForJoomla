<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');

/**
 * Quipu Form Field class for the Quipu component
 */
class JFormFieldSynccomponents extends JFormField {

    public $type = 'SYNCCOMPONENTS';

    /**
     *
     * @return <type>
     */
    protected function getInput() {

        $html = array();        
        $html[] = '<div class="sync-components">';  
        
        $selected = json_decode($this->value);
        
        $this->comps = SynchronizationManager::getInstance()->getInstalledCompatibleComponents();
        if (count($this->comps)) {            
            $html[] = '<ul>';
            foreach ($this->comps as $component => $o) {
                $html[] = '<label class="checkbox">';
                $html[] = '<input name="sync" type="checkbox" value="' . $component . '" ' . (in_array($component, $selected)?'checked="checked"':'') . '>';
                $html[] = $o['name'];
                $html[] = '</label>';
            }
            $html[] = '</ul>';            
        } else {
            $this->noDataMSG = JText::_('COM_QUIPU_SYNC_NO_COMPATIBLE_COM');
        }

        $html[] = '</div>';

        $input = implode('', $html);
        return $input;
    }

    
}