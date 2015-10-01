<?php

require_once 'Zend/Db/Table/Abstract.php';

class Notification_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'notification';
protected $_primary      = array('id');
protected $_rowClass     = 'Notification';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}