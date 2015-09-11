<?php

require_once 'Main/Row.php';

class Sport_Row extends Main_Row {

      public function getUserSportList($select=null) {
          return $this->_getListOfDepObjects('UserSport','sport',$select);
      }

}