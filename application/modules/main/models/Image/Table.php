<?php

require_once 'Zend/Db/Table/Abstract.php';

class Image_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'image';
protected $_primary      = array('id');
protected $_rowClass     = 'Image';

protected $_referenceMap = array(
                  'galery' => array(
                    'columns'          => 'galery_id',
                    'refTableClass'    => 'Galery_Table',
                    'refColumns'       => 'id')
                    );

}