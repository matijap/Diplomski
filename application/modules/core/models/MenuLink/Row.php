<?php

require_once 'Main/Row.php';

class MenuLink_Row extends Main_Row {

      public function getMenuLinkList($select=null) {
          return $this->_getListOfDepObjects('MenuLink','parent_menu_link',$select);
      }

}