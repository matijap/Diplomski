<?php

require_once 'Zend/Db/Table/Abstract.php';

class Comment_Table extends Zend_Db_Table_Abstract {

protected $_name         = 'comment';
protected $_primary      = array('id');
protected $_rowClass     = 'Comment';

protected $_referenceMap = array(
                  'parent_comment' => array(
                    'columns'          => 'parent_comment_id',
                    'refTableClass'    => 'Comment_Table',
                    'refColumns'       => 'id'),

                  'commented_image' => array(
                    'columns'          => 'commented_image_id',
                    'refTableClass'    => 'Image_Table',
                    'refColumns'       => 'id'),

                  'commented_post' => array(
                    'columns'          => 'commented_post_id',
                    'refTableClass'    => 'Post_Table',
                    'refColumns'       => 'id'),

                  'commenter' => array(
                    'columns'          => 'commenter_id',
                    'refTableClass'    => 'User_Table',
                    'refColumns'       => 'id')
                    );

}