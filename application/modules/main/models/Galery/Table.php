<?php

require_once 'Zend/Db/Table/Abstract.php';

class Galery_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'galery';
protected $_primary      = array('id');
protected $_rowClass     = 'Galery';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}