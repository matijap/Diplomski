<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserPage_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_page';
protected $_primary      = array('id');
protected $_rowClass     = 'UserPage';

protected $_referenceMap = array(
                  'page' => array(
                    'columns'          => 'page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}