<?php

require_once 'Main/Row.php';

class Image_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','commented_image',$select);
      }

}