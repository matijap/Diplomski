<?php

require_once 'Main/Row.php';

class User_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','commenter',$select);
      }

      public function getGaleryList($select=null) {
          return $this->_getListOfDepObjects('Galery','user',$select);
      }

    public function getPostUserList($select=null) {
        return $this->_getListOfDepObjects('Post','user',$select);
    }

    public function getPostUserUserList($select=null) {
        return $this->_getListOfDepObjects('Post','user',$select);
    }

      public function getUserPageList($select=null) {
          return $this->_getListOfDepObjects('UserPage','user',$select);
      }

    public function getUserUserUserList($select=null) {
        return $this->_getListOfDepObjects('UserUser','user',$select);
    }

    public function getUserUserUserFriendList($select=null) {
        return $this->_getListOfDepObjects('UserUser','friend',$select);
    }

      public function getUserWidgetList($select=null) {
          return $this->_getListOfDepObjects('UserWidget','user',$select);
      }

}