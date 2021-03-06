<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserUser_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_user';
protected $_primary      = array('id');
protected $_rowClass     = 'UserUser';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id'),

                  'friend' => array(
                    'columns'          => 'friend_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}