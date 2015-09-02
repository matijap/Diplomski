<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemLwebOptionItem_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_lweb_option_item';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemLwebOptionItem';

protected $_referenceMap = array(
                  'widget_item_lweb_option' => array(
                    'columns'          => 'widget_item_lweb_option_id',
                    'refTableClass'    => 'WidgetItemLwebOptionItem_Table',
                    'refColumns'       => 'id')
                    );

}