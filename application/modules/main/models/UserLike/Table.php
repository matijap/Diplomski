<?php

require_once 'Zend/Db/Table/Abstract.php';

class UserLike_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'user_like';
protected $_primary      = array('id');
protected $_rowClass     = 'UserLike';

protected $_referenceMap = array(
                  'comment' => array(
                    'columns'          => 'comment_id',
                    'refTableClass'    => 'Comment_Table',
                    'refColumns'       => 'id'),

                  'post' => array(
                    'columns'          => 'post_id',
                    'refTableClass'    => 'Post_Table',
                    'refColumns'       => 'id'),

                  'user' => array(
                    'columns'          => 'user_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}