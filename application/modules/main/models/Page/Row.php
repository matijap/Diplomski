<?php

require_once 'Main/Row.php';

class Page_Row extends Main_Row {

      public function getPageWidgetList($select=null) {
          return $this->_getListOfDepObjects('PageWidget','page',$select);
      }

      public function getWidgetItemFavouritePageList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemFavouritePage','linked_page',$select);
      }

}