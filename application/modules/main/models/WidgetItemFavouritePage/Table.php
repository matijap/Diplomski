<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetItemFavouritePage_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_item_favourite_page';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetItemFavouritePage';

protected $_referenceMap = array(
                  'linked_page' => array(
                    'columns'          => 'linked_page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id')
                    );

}