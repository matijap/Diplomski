<?php

require_once 'Zend/Db/Table/Abstract.php';

class DreamTeam_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'dream_team';
protected $_primary      = array('id');
protected $_rowClass     = 'DreamTeam';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}