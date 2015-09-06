<?php

require_once 'Main/Row.php';

class Galery_Row extends Main_Row {

      public function getImageList($select=null) {
          return $this->_getListOfDepObjects('Image','galery',$select);
      }

}