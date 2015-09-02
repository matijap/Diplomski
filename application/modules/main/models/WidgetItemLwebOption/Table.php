<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemLwebOption_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_lweb_option';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemLwebOption';

protected $_referenceMap = array(
                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id')
                    );

}