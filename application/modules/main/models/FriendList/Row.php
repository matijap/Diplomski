<?php

require_once 'Main/Row.php';

class FriendList_Row extends Main_Row {

      public function getUserFriendListList($select=null) {
          return $this->_getListOfDepObjects('UserFriendList','friend_list',$select);
      }

}