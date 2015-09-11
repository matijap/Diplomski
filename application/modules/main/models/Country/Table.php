<?php

require_once 'Zend/Db/Table/Abstract.php';

class Country_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'country';
protected $_primary      = array('id');
protected $_rowClass     = 'Country';

protected $_referenceMap = array(
                    );

}