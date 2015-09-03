<?php

require_once 'Zend/Db/Table/Abstract.php';

class WidgetOption_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'widget_option';
protected $_primary      = array('id');
protected $_rowClass     = 'WidgetOption';

protected $_referenceMap = array(
                  'linked_page' => array(
                    'columns'          => 'linked_page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id'),

                  'parent_widget_option' => array(
                    'columns'          => 'parent_widget_option_id',
                    'refTableClass'    => 'WidgetOption_Table',
                    'refColumns'       => 'id')
                    );

}