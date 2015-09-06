<?php

require_once 'Main/Row.php';

class Post_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','commented_post',$select);
      }

}