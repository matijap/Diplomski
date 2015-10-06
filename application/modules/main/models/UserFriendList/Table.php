<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserFriendList_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_friend_list';
protected $_primary      = array('id');
protected $_rowClass     = 'UserFriendList';

protected $_referenceMap = array(
                  'friend_list' => array(
                    'columns'          => 'friend_list_id',
                    'refTableClass'    => 'FriendList_Table',
                    'refColumns'       => 'id'),

                  'friend' => array(
                    'columns'          => 'friend_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}