<?php

require_once 'Main/Row.php';

class User_Row extends Main_Row {

      public function getCommentList($select=null) {
          return $this->_getListOfDepObjects('Comment','commenter',$select);
      }

      public function getDreamTeamList($select=null) {
          return $this->_getListOfDepObjects('DreamTeam','user',$select);
      }

      public function getExFavoritePagesList($select=null) {
          return $this->_getListOfDepObjects('ExFavoritePages','user',$select);
      }

      public function getFavouriteItemList($select=null) {
          return $this->_getListOfDepObjects('FavouriteItem','user',$select);
      }

      public function getFriendListList($select=null) {
          return $this->_getListOfDepObjects('FriendList','user',$select);
      }

      public function getGaleryList($select=null) {
          return $this->_getListOfDepObjects('Galery','user',$select);
      }

    public function getNotificationUserList($select=null) {
        return $this->_getListOfDepObjects('Notification','user',$select);
    }

    public function getNotificationUserNotifierList($select=null) {
        return $this->_getListOfDepObjects('Notification','notifier',$select);
    }

      public function getPageList($select=null) {
          return $this->_getListOfDepObjects('Page','user',$select);
      }

    public function getPostOriginalUserList($select=null) {
        return $this->_getListOfDepObjects('Post','original_user',$select);
    }

    public function getPostOriginalUserUserList($select=null) {
        return $this->_getListOfDepObjects('Post','user',$select);
    }

      public function getPrivacySettingList($select=null) {
          return $this->_getListOfDepObjects('PrivacySetting','user',$select);
      }

      public function getUserFavoriteList($select=null) {
          return $this->_getListOfDepObjects('UserFavorite','user',$select);
      }

      public function getUserFriendListList($select=null) {
          return $this->_getListOfDepObjects('UserFriendList','friend',$select);
      }

      public function getUserInfoList($select=null) {
          return $this->_getListOfDepObjects('UserInfo','user',$select);
      }

      public function getUserLikeList($select=null) {
          return $this->_getListOfDepObjects('UserLike','user',$select);
      }

      public function getUserPageList($select=null) {
          return $this->_getListOfDepObjects('UserPage','user',$select);
      }

      public function getUserSportList($select=null) {
          return $this->_getListOfDepObjects('UserSport','user',$select);
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

      public function getUserWidgetDataList($select=null) {
          return $this->_getListOfDepObjects('UserWidgetData','user',$select);
      }

      public function getWidgetTableDataList($select=null) {
          return $this->_getListOfDepObjects('WidgetTableData','user',$select);
      }

}