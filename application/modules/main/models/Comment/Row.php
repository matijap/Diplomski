<?php

require_once 'Main/Row.php';

class Comment_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','parent_comment',$select);
      }

      public function getUserFavoriteList($select=null) {
          return $this->_getListOfDepObjects('UserFavorite','comment',$select);
      }

      public function getUserLikeList($select=null) {
          return $this->_getListOfDepObjects('UserLike','comment',$select);
      }

}