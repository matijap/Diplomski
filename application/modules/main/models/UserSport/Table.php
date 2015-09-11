<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserSport_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_sport';
protected $_primary      = array('id');
protected $_rowClass     = 'UserSport';

protected $_referenceMap = array(
                  'sport' => array(
                    'columns'          => 'sport_id',
                    'refTableClass'    => 'Sport_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}