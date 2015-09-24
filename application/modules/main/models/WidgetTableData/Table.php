<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetTableData_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_table_data';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetTableData';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}