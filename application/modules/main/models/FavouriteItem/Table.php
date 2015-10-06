<?php

require_once 'Zend/Db/Table/Abstract.php';

class FavouriteItem_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'favourite_item';
protected $_primary      = array('id');
protected $_rowClass     = 'FavouriteItem';

protected $_referenceMap = array(
                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id'),

                  'page' => array(
                    'columns'          => 'page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id')
                    );

}