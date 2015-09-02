<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemPlain_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_plain';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemPlain';

protected $_referenceMap = array(
                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id')
                    );

}