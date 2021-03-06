<?php

require_once 'Zend/Db/Table/Abstract.php';

class Post_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'post';
protected $_primary      = array('id');
protected $_rowClass     = 'Post';

protected $_referenceMap = array(
                  'original_page' => array(
                    'columns'          => 'original_page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'original_user' => array(
                    'columns'          => 'original_user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id'),

                  'page' => array(
                    'columns'          => 'page_id',
                    'refTableClass'    => 'Page_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}