<?php

require_once 'Main/Row.php';

class WidgetItemLwebOptionItem_Row extends Main_Row {

      public function getWidgetItemLwebOptionItemList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemLwebOptionItem','widget_item_lweb_option',$select);
      }

      public function getWidgetItemLwebOptionItemDataList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemLwebOptionItemData','widget_item_lweb_option_item',$select);
      }

}