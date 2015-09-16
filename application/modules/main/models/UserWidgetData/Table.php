<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserWidgetData_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_widget_data';
protected $_primary      = array('id');
protected $_rowClass     = 'UserWidgetData';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}