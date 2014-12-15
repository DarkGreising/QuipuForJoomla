<?php defined('_JEXEC') or die('Restricted access');?>
<form action="<?php echo JRoute::_($formAction); ?>" method="post" id="addNewRow_dynTable" class="newRow" style="display:none">
    <h3><?php echo  JText::_('COM_QUIPU_ADD_ROW') ?></h3>
    <table>
        <thead>
            <tr>
                <th class="edit"><?php echo  JText::_('COM_QUIPU_EDIT') ?></th>
                <th><span><?php echo  JText::_('COM_QUIPU_DESCRIPCION') ?></span><span class="carat"></span></th>
                <th><span><?php echo  JText::_('COM_QUIPU_MEMO') ?></span><span class="carat"></span></th>
                <th><span><?php echo  JText::_('COM_QUIPU_UNITS') ?></span><span class="carat"></span></th>
                <th><span><?php echo  JText::_('COM_QUIPU_UNIT_PRICE') ?></span><span class="carat"></span></th>
                <th><span><?php echo  JText::_('COM_QUIPU_DISCOUNT') ?></span><span class="carat"></span></th>
                <th class="delete"></th>
            </tr>
        </thead>
        <tbody>
            <tr id="newDataRow_dynTable" class="newRow even">
                <td class="edit">
                    <input class="inputbox" type="checkbox" name="edit[]" value="" />
                </td>
                <td class="editable">
                    <span></span>                    
                    <?php echo QuipuHelper::getItemsSelect('data[item_id]');?>
                    <div class="dyntable_messages"></div>
                </td>
                
                <td class="editable">
                    <span></span>
                    <textarea class="inputbox" name="data[memo]" ></textarea>
                </td>
                
                <td class="editable">
                    <span></span>
                    <input class="inputbox" type="text" name="data[units]" />
                </td>
                
                <td class="editable">
                    <span></span>
                    <input class="inputbox" type="text" name="data[unit_price]" />
                </td>
                
                <td class="editable">
                    <span></span>
                    <input class="inputbox" type="text" name="data[discount]" />
                </td>
                
                
                
                <td class="delete">
                    <input type="checkbox" name="delete[]" value="" id="deleteNULL_STRING" />
                    <label for="deleteNULL_STRING">                         
                    </label>                    
                </td>
            </tr>
        </tbody>
    </table>

    <div class="submit">
        <input type="hidden" name="insert" value="true" />
        <input type="submit" value="Update" />
    </div>
</form>