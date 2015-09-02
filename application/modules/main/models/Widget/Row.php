<?php

require_once 'Main/Row.php';

class Widget_Row extends Main_Row {

      public function getPageWidgetList($select=null) {
          return $this->_getListOfDepObjects('PageWidget','widget',$select);
      }

      public function getUserWidgetList($select=null) {
          return $this->_getListOfDepObjects('UserWidget','widget',$select);
      }

      public function getWidgetItemFavouritePageList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemFavouritePage','widget',$select);
      }

      public function getWidgetItemListOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemListOption','widget',$select);
      }

      public function getWidgetItemLwebOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemLwebOption','widget',$select);
      }

      public function getWidgetItemPlainList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemPlain','widget',$select);
      }

      public function getWidgetItemTableList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemTable','widget',$select);
      }

}