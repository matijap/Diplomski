<?php

class Main_Row extends Zend_Db_Table_Row_Abstract {

    public static $translate;
    public function __construct($data = array()) {
        self::$translate = getTranslate();
        parent::__construct($data);
    }
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

    protected function _getListOfDepObjects($depClass, $refColumn = null, $select = null) {
        return $this->findDependentRowset($depClass . '_Table', $refColumn, $select);
    }
}