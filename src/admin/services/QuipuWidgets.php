<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 *
 */
class QuipuWidgets {

    private static $_filter = false;

    private static $widgets = array(
        /*
        'last_customers' => array(
            'admin' => true,
            'group' => 'customers',
            'type' => 'view',
            'tmpl' => 'usuarios_online',
            'class' => 'w1x',
            'title' => '',
            'info' => '',
            'width' => '1x',
            'height' => '1x'
        ),*/
        'last_customers' => array(
            'group' => 'none',
            'type' => 'table',
            'title' => 'COM_QUIPU_WIDGET_LAST_CUSTOMERS',
            'info' => '',
            'cols' => '[{header:"_NAME",dataIndex:"name",dataType:"string",width:"200"},{header:"_CONTACT",dataIndex:"contact",dataType:"string",width:"250"},{header:"_PHONE",dataIndex:"phone",dataType:"string",width:"98"},{header:"_EMAIL",dataIndex:"email",dataType:"string",width:"250"}]',
            'cols_j3' => '[{display:"_NAME",name:"name",dataType:"string",width:"200"},{display:"_CONTACT",name:"contact",dataType:"string",width:"250"},{display:"_PHONE",name:"phone",dataType:"string",width:"98"},{display:"_EMAIL",name:"email",dataType:"string",width:"250"}]',
            'dataurl' => 'index.php?option=com_quipu&task=paneltable.data&w=last_customers',
            'detailurl' => 'index.php?option=com_quipu&task=customer.edit&id=',
            'query' => "SELECT id,name,contact,phone,CONCAT(\"<a href='mailto:\",email,\"'>\",email,\"</a>\") as email FROM #__quipu_customer ORDER BY id desc",
            'width' => '2x',
            'height' => '1x'
        ),
        'pending_orders' => array(
            'group' => 'none',
            'type' => 'table',
            'title' => 'COM_QUIPU_WIDGET_PENDING_ORDERS',
            'info' => '',
            'cols' => '[{header:"_NUMBER",dataIndex:"order_number",dataType:"string",width:"100"},{header:"_NAME",dataIndex:"name",dataType:"string",width:"138"},{header:"_DATE",dataIndex:"order_date",dataType:"date",width:"100"},{header:"_TOTAL",dataIndex:"total",dataType:"string",width:"50"}]',
            'cols_j3' => '[{display:"_NUMBER",name:"order_number",dataType:"string",width:"100"},{display:"_NAME",name:"name",dataType:"string",width:"138"},{display:"_DATE",name:"order_date",dataType:"date",width:"100"},{display:"_TOTAL",name:"total",dataType:"string",width:"50"}]',
            'dataurl' => 'index.php?option=com_quipu&task=paneltable.data&w=pending_orders',
            'detailurl' => 'index.php?option=com_quipu&task=order.edit&id=',
            'query' => "SELECT o . * , c.name FROM #__quipu_order o INNER JOIN #__quipu_customer c ON o.customer_id = c.id WHERE STATUS = '1001' ORDER BY id",
            'width' => '1x',
            'height' => '1x'
        ),
        'order_statuses' => array(
            'group' => 'none',
            'type' => 'table',
            'title' => 'COM_QUIPU_ORDER_STATUSES',
            'info' => '',
            'cols' => '[{header:"_STATUS",dataIndex:"status",dataType:"string",width:"200"},{header:"_ORDERS",dataIndex:"orders",dataType:"string",width:"100"},{header:"_TOTAL",dataIndex:"total",dataType:"string",width:"89"}]',
            'cols_j3' => '[{display:"_STATUS",name:"status",dataType:"string",width:"200"},{display:"_ORDERS",name:"orders",dataType:"string",width:"100"},{display:"_TOTAL",name:"total",dataType:"string",width:"89"}]',
            'dataurl' => 'index.php?option=com_quipu&task=paneltable.data&w=order_statuses',            
            'query' => "SELECT CONCAT('COM_QUIPU_ORDER_STATUS_',status) as status, COUNT(id) as orders, SUM(total) as total FROM #__quipu_order WHERE MONTH(order_date)=MONTH(NOW()) GROUP BY status",
            'width' => '1x',
            'height' => '1x'
        ),
        'pending_invoices' => array(
            'group' => 'none',
            'type' => 'table',
            'title' => 'COM_QUIPU_WIDGET_PENDING_INVOICES',
            'info' => '',
            'cols' => '[{header:"_NUMBER",dataIndex:"invoice_number",dataType:"string",width:"100"},{header:"_NAME",dataIndex:"name",dataType:"string",width:"138"},{header:"_DUEDATE",dataIndex:"due_date",dataType:"date",width:"100"},{header:"_TOTAL",dataIndex:"total",dataType:"string",width:"50"}]',
            'cols_j3' => '[{display:"_NUMBER",name:"invoice_number",dataType:"string",width:"100"},{display:"_NAME",name:"name",dataType:"string",width:"138"},{display:"_DUEDATE",name:"due_date",dataType:"date",width:"100"},{display:"_TOTAL",name:"total",dataType:"string",width:"50"}]',
            'dataurl' => 'index.php?option=com_quipu&task=paneltable.data&w=pending_invoices',
            'detailurl' => 'index.php?option=com_quipu&task=invoice.edit&id=',
            'query' => "SELECT o . * , c.name FROM #__quipu_invoice o INNER JOIN #__quipu_customer c ON o.customer_id = c.id WHERE STATUS = '1001' ORDER BY id",
            'width' => '1x',
            'height' => '1x'
        ),
        'monthly_invoicing' => array(
            'group' => 'doc',
            'type' => 'chart',
            'chart-type' => 'bar',
            'title' => 'COM_QUIPU_WIDGET_MONTHLY_INVOICING',
            'info' => '',
            'dataurl' => 'index.php?option=com_quipu&task=paneltable.data&w=monthly_invoicing',
            'series' => array(
                'income' => array(
                    'title' => 'COM_QUIPU_WIDGET_MONTHLY_INVOICING',
                    'query' => "SELECT CONCAT(UPPER(MONTHNAME(invoice_date)),'_SHORT') as label, ROUND(SUM(total)) as value FROM #__quipu_invoice WHERE YEAR(invoice_date)=YEAR(NOW()) GROUP BY MONTH(invoice_date) ORDER BY MONTH(invoice_date)",
                    'color' => '#025A8D'
                ),
                'profit' => array(
                    'title' => 'COM_QUIPU_WIDGET_MONTHLY_PROFIT',
                    'query' => "SELECT t.label,t.value FROM (SELECT CONCAT(UPPER(MONTHNAME(invoice_date)),'_SHORT') as label,ROUND(SUM(item.profit_wotax)) as value FROM #__quipu_detail_item item INNER JOIN #__quipu_invoice invoice ON item.invoice_id=invoice_id WHERE YEAR(invoice.invoice_date)=YEAR(NOW()) GROUP BY invoice.id ORDER BY MONTH(invoice.invoice_date)) t GROUP BY t.label",
                    'color' => '#579B1E'
                )
            ),
            'x_legend' => '',
            'y_legend' => '',
            'width' => '2x',
            'height' => '1x'            
        )
    );

    /**
     * 
     */
    public static function getWidgetList() {
        $list = array();
        foreach (self::$widgets as $k => $w) {
            $list[$w['group']][] = $k;
        }

        return $list;
    }

    /**
     * Este método se llamará varias veces, en peticiones sucesivas, para definir la lista de widgets,
     * para pintar el widget, y finalmente para leer los datos. Sólo en éste último caso queremos
     * procesar la query, porque puede ser un trabajo pesado que requiera muchas consultas y agregaciones.
     * 
     * @param type $token     
     * @return type 
     */
    public static function getWidget($token) {
        $w = self::$widgets[$token];
        $w['id'] = $token;
        $w['title'] = JText::_($w['title']);
        if(!array_key_exists('class', $w)){
            $w['class'] = '';
        }
        return $w;
    }

}