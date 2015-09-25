<?php

require_once 'Main/Row.php';

class Language_Row extends Main_Row {

      public function getUserInfoList($select=null) {
          return $this->_getListOfDepObjects('UserInfo','language',$select);
      }

}