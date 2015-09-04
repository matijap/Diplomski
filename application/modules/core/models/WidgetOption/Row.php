<?php

require_once 'Main/Row.php';

class WidgetOption_Row extends Main_Row {

      public function getWidgetOptionList($select=null) {
          return $this->_getListOfDepObjects('WidgetOption','parent_widget_option',$select);
      }

}