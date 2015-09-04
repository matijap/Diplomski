<?php

require_once 'Zend/Db/Table/Abstract.php';

class User_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user';
protected $_primary      = array('id');
protected $_rowClass     = 'User';

protected $_referenceMap = array(
                    );

}