<?php

require_once 'Main/Row.php';

class Country_Row extends Main_Row {

      public function getUserInfoList($select=null) {
          return $this->_getListOfDepObjects('UserInfo','country',$select);
      }

}