<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemListOptionItem_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_list_option_item';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemListOptionItem';

protected $_referenceMap = array(
                  'widget_item_list_option' => array(
                    'columns'          => 'widget_item_list_option_id',
                    'refTableClass'    => 'WidgetItemListOption_Table',
                    'refColumns'       => 'id')
                    );

}