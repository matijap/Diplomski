<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserInfo_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_info';
protected $_primary      = array('id');
protected $_rowClass     = 'UserInfo';

protected $_referenceMap = array(
                  'country' => array(
                    'columns'          => 'country_id',
                    'refTableClass'    => 'Country_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}