<?php

require_once 'Zend/Db/Table/Abstract.php';

class Language_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'language';
protected $_primary      = array('id');
protected $_rowClass     = 'Language';

protected $_referenceMap = array(
                    );

}