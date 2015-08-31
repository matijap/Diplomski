<?php

require_once 'Zend/Db/Table.php';

class Main {
    protected static $_tables = array();

    public static function createNewObject($objectName, $objData = array()) {
        //Include object table class if not included
        $objectTable    = self::includeObjectTable($objectName);
        //Get all columns for object
        $objectColumns  = $objectTable->info(Zend_Db_Table_Abstract::COLS);

        $dataToInsert   = array_intersect_key($objData, array_fill_keys($objectColumns,1));
        self::includeObject($objectName);
        return $objectTable->createRow($objectName::getDefaultValues($dataToInsert));
    }

    public static function getTableFor($modelClass) {
        if (array_key_exists($modelClass, self::$_tables)) {
          return self::$_tables[$modelClass];
        }
        $tableClassName = $modelClass . "_Table";
        if (!class_exists($tableClassName)) {
          require_once "$modelClass/Table.php";
        }
        self::$_tables[$modelClass] = new $tableClassName();
        return self::$_tables[$modelClass];
    }

    public static function buildObject($object, $objectID) {
        $objectTable = self::includeObjectTable($object);
        return $objectTable->find($objectID)->current();
    }

    public static function execQuery($querySql, $params = array()) {
        $dbAdapter  = Zend_Db_Table::getDefaultAdapter();
        $query      = $dbAdapter->query($querySql, $params);
        $query->closeCursor();
        return $query;
    }

    public static function getObjectSelect($object) {
        $objectTable = self::includeObjectTable($object);
        return $objectTable->select();
    }

    public static function beginTransaction() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
    }

    public static function commitTransaction() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->commit();
    }


    public static function select($className = null, $integrityCheck = true) {
        //
        //return Model::select()
        //->from('', '')
        // ->where(' = ?', $this->id)
        //->columns(array(
        //'id', 'name'
        //))
        //->query()->fetchAll();

        if (is_null($className)) {
          $db = Zend_Db_Table::getDefaultAdapter();
          return $db->select();
        }
        $table  = self::getTableFor($className);
        $select = NULL;
        if ($integrityCheck === false) {
          $select = $table->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false);
        } else {
          $select = $table->select();
        }
        return $select;
    }

    public static function fetchRow($className, $select = null) {
        if ($className instanceof Zend_Db_Table_Select) {
          $table  = $className->getTable();
          $select = $className;
        } else {
          $table = self::getTableFor($className);
        }
        return $table->fetchRow($select);
    }

    public static function fetchAll($className, $select = null) {
        if ($className instanceof Zend_Db_Table_Select) {
          $table  = $className->getTable();
          $select = $className;
        } else {
          $table = self::getTableFor($className);
        }
        return $table->fetchAll($select);
    }

    public static function debug($toDebug, $toArray = true) {
        // echo '<pre>';
        if ($toArray) {
            Zend_Debug::dump($toDebug->toArray());
        } else {
            Zend_Debug::dump($toDebug);
        }
    }
}