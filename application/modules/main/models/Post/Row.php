<?php

require_once 'Main/Row.php';

class Post_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','commented_post',$select);
      }

      public function getNotificationList($select=null) {
          return $this->_getListOfDepObjects('Notification','post',$select);
      }

      public function getUserFavoriteList($select=null) {
          return $this->_getListOfDepObjects('UserFavorite','post',$select);
      }

      public function getUserLikeList($select=null) {
          return $this->_getListOfDepObjects('UserLike','post',$select);
      }

}