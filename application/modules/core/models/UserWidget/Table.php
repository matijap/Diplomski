<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserWidget_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_widget';
protected $_primary      = array('id');
protected $_rowClass     = 'UserWidget';

protected $_referenceMap = array(
                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}