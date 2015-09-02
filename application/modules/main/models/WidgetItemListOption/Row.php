<?php

require_once 'Main/Row.php';

class WidgetItemListOption_Row extends Main_Row {

      public function getWidgetItemListOptionItemList($select=null) {
          return $this->_getListOfDepObjects('WidgetItemListOptionItem','widget_item_list_option',$select);
      }

}