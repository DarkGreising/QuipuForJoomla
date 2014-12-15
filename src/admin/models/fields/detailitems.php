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
class JFormFieldDetailItems extends JFormField {

    public $type = 'DETAILITEMS';
    
    private $fields = array(
        'order' => 'm1',
        'invoice' => 'm3',
        'purchaseorder'=>'m4'
    );

    /**
     *
     * @return <type>
     */
    protected function getInput() {

        $id = IWRequest::getInt('id');
        $this->tipo = (string) $this->element['iw_master'];
        if ($id) {
            $this->master = JTable::getInstance($this->tipo, 'QuipuTable');
            $this->master->load($id);

            $this->isEditable = !isset($this->element['editable']) || $this->element['editable'] == 'true';
            $this->isEditable = $this->isEditable && $this->master->status < 9000;

            $this->noDataMSG = JText::_('COM_QUIPU_DYNTABLE_NOROWS');
            $html = array();
            $html[] = '<div class="detalle">';

            $this->loadDeps();
            if ($this->isEditable) {
                if(QUIPU_IS_J3){
                    $html[] = '<p><i class="icon-help"></i> ' . JText::_('COM_QUIPU_DYNTABLE_LEGEND') . '</p>';
                }
                else{
                    $html[] = '<p><span class="iw-inline-icon-16 icon-16-help">' . JText::_('COM_QUIPU_DYNTABLE_LEGEND') . '</span></p>';
                }                
            }
            $html[] = '<table class="adminlist dynamic table table-stripped" id="dynTable">';
            $html[] = $this->getTHead();
            $html[] = '<tbody>';
            $html[] = $this->getRows();
            $html[] = '</tbody>';
            $html[] = '</table>';
            $html[] = '<input type="hidden" name="noDataMessage" value="' . $this->noDataMSG . '" />';
            $html[] = $this->getJS();

            $html[] = '</div>';

            $input = implode('', $html);
            return $input;
        } else {
            $txtDetalle = JText::_($this->tipo);
            return JText::sprintf('Guardar %s primero.', $txtDetalle);
        }
    }

    /**
     * 
     */
    protected function loadDeps() {
        //Moved to JPATH_COMPONENT_ADMINISTRATOR/quipu.php        
    }

    /**
     *
     * @return type 
     */
    protected function getTHead() {
        $th = '<thead><tr><th class="edit" width="1%">' .
                JText::_('COM_QUIPU_EDIT') . '</th><th width="25%">' .
                JText::_('COM_QUIPU_DETAIL_DESCRIPTION') . '</th><th width="25%">' .
                JText::_('COM_QUIPU_MEMO') . '</th><th width="10%">' .
                JText::_('COM_QUIPU_UNITS') . '</th><th width="10%">' .
                JText::_('COM_QUIPU_UNIT_PRICE') . '</th><th width="10%">' .
                JText::_('COM_QUIPU_DISCOUNT') . '</th>';
        if ($this->isEditable) {
            $th.='<th width="2%" class="delete">&nbsp;&nbsp;&nbsp;&nbsp;</th>';
        }

        $th.='</tr></thead>';
        return $th;
    }

    /**
     *
     * @return type 
     */
    public function getLabel() {
        return false;
    }

    /**
     *
     * @return type 
     */
    protected function getURL() {
        $option = IWRequest::getCmd('option');

        //$c = ($this->tipo == 'order') ? 'm1' : 'm2';
        $c = $this->fields[$this->tipo];
        
        $controller = 'detailitem';

        $u = 'index.php?option=' . $option . '&task=' . $controller . '.dyntable&t=DetailItem&' . $c . '=' . $this->master->id;

        return JRoute::_($u, false);
    }

    /**
     * 
     */
    protected function getJS() {
        $url = $this->getURL();
        $domID = 'dynTable';

        $js = array();
        $addNewRows = $this->master->status < 9000 ? 'true' : 'false';

        $js[] = '<script type="text/javascript">';
        if (QUIPU_IS_J3) {
            $js[] = 'jQuery(document).on("ready",function(){';
            $js[] = '   jQuery("#' . $domID . '").dyntable({addNewRows:' . $addNewRows . ',url:"' . $url . '",noDataMessage:"' . $this->noDataMSG . '",addRowLabel:"' . JText::_('COM_QUIPU_ADD_ROW') . '",deletePrompt:"' . JText::_('COM_QUIPU_CONFIRM_DELETE_ROW') . '"});';
            $js[] = '});';
        } else {
            $js[] = 'window.addEvent("domready", function() {';
            $js[] = '   new DynTable("' . $domID . '",{addNewRows:' . $addNewRows . ',url:"' . $url . '",noDataMessage:"' . $this->noDataMSG . '",addRowLabel:"' . JText::_('COM_QUIPU_ADD_ROW') . '",deletePrompt:"' . JText::_('COM_QUIPU_CONFIRM_DELETE_ROW') . '"});';
            $js[] = '});';
        }
        $js[] = '</script>';

        return implode("\n", $js);
    }

    /**
     *
     * @param type $hito 
     */
    protected function applyFilters($accion) {
        return true;
    }

    /**
     * 
     */
    protected function getRows() {
        $rows = $this->master->getDetail();
        $html = array();
        if (count($rows)) {
            $i = 0;
            foreach ($rows as $row) {
                $row->resumen = IWUtils::sumarize($row->memo);

                $html[] = '<tr class="' . ((++$i % 2) ? 'row0' : 'row1') . '">';
                $html[] = '<td class="edit"><input type="checkbox" name="edit[]" value="' . $row->id . '" /></td>';

                $html[] = '<td ' . ($this->isEditable ? 'class="editable"' : '') . '>';
                $html[] = '<span class="' . (($row->profit_wotax < 0) ? 'invalid' : '') . '">';
                $html[] = $row->item;
                $html[] = '</span>';
                if ($this->isEditable) {
                    $select = QuipuHelper::getItemsSelect('data[' . $row->id . '][item_id]', $row->item_id);
                    $html[] = $select;
                }
                $html[] = '<div class="dyntable_messages"></div>';
                $html[] = '</td>';


                $html[] = '<td ' . ($this->isEditable ? 'class="editable"' : '') . '>';
                $html[] = '<span>';
                $html[] = IWUtils::sumarize($row->memo, 25);
                $html[] = '</span>';
                if ($this->isEditable) {
                    $html[] = '<textarea class="inputbox" name="data[' . $row->id . '][memo]">' . $row->memo . '</textarea>';
                }
                $html[] = '</td>';

                $html[] = '<td class="number' . ($this->isEditable ? ' editable' : '') . '">';
                $html[] = '<span>';
                $html[] = $row->units;
                $html[] = '</span>';
                if ($this->isEditable) {
                    $html[] = '<input type="text" class="inputbox" name="data[' . $row->id . '][units]" value="' . $row->units . '" />';
                }
                $html[] = '</td>';

                $html[] = '<td class="number' . ($this->isEditable ? ' editable' : '') . '">';
                $html[] = '<span>';
                $html[] = $row->unit_price;
                $html[] = '</span>';
                if ($this->isEditable) {
                    $html[] = '<input type="text" class="inputbox" name="data[' . $row->id . '][unit_price]" value="' . $row->unit_price . '" />';
                }
                $html[] = '</td>';


                $html[] = '<td class="number' . ($this->isEditable ? ' editable' : '') . '">';
                $html[] = '<span>';
                $html[] = $row->discount;
                $html[] = '</span>';
                if ($this->isEditable) {
                    $html[] = '<input type="text" class="inputbox" name="data[' . $row->id . '][discount]" value="' . $row->discount . '" />';
                }
                $html[] = '</td>';
                if ($this->isEditable) {
                    $html[] = '<td class="delete"><input type="checkbox" name="delete[]" value="' . $row->id . '" id="delete' . $row->id . '"/>';
                }
                $html[] = '</td>';

                $html[] = '</tr>';
            }
        } else {
            $html[] = '<tr class="noDataRow odd"><td colspan="7">' . $this->noDataMSG . '</td></tr>';
        }
        return implode("", $html);
    }

}