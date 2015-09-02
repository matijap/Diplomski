<?php

require_once 'Zend/Db/Table/Abstract.php';

class PageWidget_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'page_widget';
protected $_primary      = array('id');
protected $_rowClass     = 'PageWidget';

protected $_referenceMap = array(
                  'page' => array(
                    'columns'          => 'page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'widget' => array(
                    'columns'          => 'widget_id',
                    'refTableClass'    => 'Widget_Table',
                    'refColumns'       => 'id')
                    );

}