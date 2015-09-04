<?php

require_once 'Main/Row.php';

class User_Row extends Main_Row {

      public function getUserWidgetList($select=null) {
          return $this->_getListOfDepObjects('UserWidget','user',$select);
      }

}