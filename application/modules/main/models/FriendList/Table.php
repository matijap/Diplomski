<?php

require_once 'Zend/Db/Table/Abstract.php';

class FriendList_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'friend_list';
protected $_primary      = array('id');
protected $_rowClass     = 'FriendList';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}