<?php

require_once 'Zend/Db/Table/Abstract.php';

class Sport_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'sport';
protected $_primary      = array('id');
protected $_rowClass     = 'Sport';

protected $_referenceMap = array(
                    );

}