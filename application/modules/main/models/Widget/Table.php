<?php

require_once 'Zend/Db/Table/Abstract.php';

class Widget_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget';
protected $_primary      = array('id');
protected $_rowClass     = 'Widget';

protected $_referenceMap = array(
                    );

}