<?php

require_once 'Main/Row.php';

class Page_Row extends Main_Row {

      public function getPageWidgetList($select=null) {
          return $this->_getListOfDepObjects('PageWidget','page',$select);
      }

      public function getWidgetOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetOption','linked_page',$select);
      }

}