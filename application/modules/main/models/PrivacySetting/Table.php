<?php

require_once 'Zend/Db/Table/Abstract.php';

class PrivacySetting_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'privacy_setting';
protected $_primary      = array('id');
protected $_rowClass     = 'PrivacySetting';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}