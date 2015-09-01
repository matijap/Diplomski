<?php

class Main_Row extends Zend_Db_Table_Row_Abstract {

    public static function create($data, $tableName = false) {
        $tableName = $tableName ? $tableName : get_called_class();
        $object    = Main::createNewObject($tableName, $data);
        $object->save();
        return $object;
    }

    public function edit($data) {
        $this->setFromArray($data);
        $this->save();
        return $this;
    }

    public static function getDefaultValues($columns = array()) {
        return $columns;
    }
}