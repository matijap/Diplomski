<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemLwebOptionItemData_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_lweb_option_item_data';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemLwebOptionItemData';

protected $_referenceMap = array(
                  'widget_item_lweb_option_item' => array(
                    'columns'          => 'widget_item_lweb_option_item_id',
                    'refTableClass'    => 'WidgetItemLwebOptionItem_Table',
                    'refColumns'       => 'id')
                    );

}