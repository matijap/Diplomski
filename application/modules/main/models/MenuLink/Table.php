<?php

require_once 'Zend/Db/Table/Abstract.php';

class MenuLink_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'menu_link';
protected $_primary      = array('id');
protected $_rowClass     = 'MenuLink';

protected $_referenceMap = array(
                  'parent_menu_link' => array(
                    'columns'          => 'parent_menu_link_id',
                    'refTableClass'    => 'MenuLink_Table',
                    'refColumns'       => 'id')
                    );

}