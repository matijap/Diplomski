<?php

require_once 'Main/Row.php';

class Role_Row extends Main_Row {

      public function getUserInfoList($select=null) {
          return $this->_getListOfDepObjects('UserInfo','role',$select);
      }

}