<?php

require_once 'Main/Row.php';

class Widget_Row extends Main_Row {

      public function getUserWidgetList($select=null) {
          return $this->_getListOfDepObjects('UserWidget','widget',$select);
      }

      public function getWidgetOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetOption','widget',$select);
      }

}