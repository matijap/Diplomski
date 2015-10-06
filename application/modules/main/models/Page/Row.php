<?php

require_once 'Main/Row.php';

class Page_Row extends Main_Row {

      public function getExFavoritePagesList($select=null) {
          return $this->_getListOfDepObjects('ExFavoritePages','page',$select);
      }

      public function getFavouriteItemList($select=null) {
          return $this->_getListOfDepObjects('FavouriteItem','page',$select);
      }

    public function getPostOriginalPageList($select=null) {
        return $this->_getListOfDepObjects('Post','original_page',$select);
    }

    public function getPostOriginalPagePageList($select=null) {
        return $this->_getListOfDepObjects('Post','page',$select);
    }

      public function getUserPageList($select=null) {
          return $this->_getListOfDepObjects('UserPage','page',$select);
      }

      public function getWidgetList($select=null) {
          return $this->_getListOfDepObjects('Widget','page',$select);
      }

      public function getWidgetOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetOption','linked_page',$select);
      }

}