<?php

require_once 'Zend/Db/Table/Abstract.php';

class Role_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'role';
protected $_primary      = array('id');
protected $_rowClass     = 'Role';

protected $_referenceMap = array(
                    );

}