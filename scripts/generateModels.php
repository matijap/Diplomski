<?php

  error_reporting(E_ALL);
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  ini_set('memory_limit', -1);

  require "bootstrap.php";
  
  $dbAdapter = Zend_Registry::getInstance()->dbAdapter;
  $config = Zend_Registry::get('config');
  $config = $config->toArray();
  $dbName = $config['resources']['db']['params']['dbname'];
  
  if(isset($argv[1])){
    $tableName =$argv[1];
  } else {
    $tableName = NULL;
  }
  
  try {
      $rows        = array();
      $rowHasModul = array();
      $describedTables = array();
      if(is_null($tableName)) {
          $describeDb = $dbAdapter->listTables();
          foreach ($describeDb as $key => $table) {
              $describedTables[$key]["constraints"]   = $dbAdapter->fetchAll("select * from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA = ? AND TABLE_NAME = ?", array($dbName, $table));
              $describedTables[$key]["realTableName"] = $table;
              $describedTables[$key]["description"]   = $dbAdapter->describeTable($table);
          }
      } else if(is_string($tableName)) {
          $describedTables[0]["constraints"]   = $dbAdapter->fetchAll("select * from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA = ? AND TABLE_NAME = ?", array($dbName, $tableName));
          $describedTables[0]["realTableName"] = $tableName;
          $describedTables[0]["description"]   = $dbAdapter->describeTable($tableName);
      } else if(is_array($tableName)) {
          foreach($tableName as $genTabel) {
              $describedTables[]["constraints"] = $dbAdapter->fetchAll("select * from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA = ? AND TABLE_NAME = ?", array($dbName, $genTabel));
              $describedTables[]["description"] = $dbAdapter->describeTable($genTabel);
              $describedTables[]["realTableName"] = $genTabel;
          }    
      }

      foreach ($describedTables as $describedTable) {
          foreach ($describedTable["constraints"] as $constraint) {
              if ($constraint["REFERENCED_TABLE_NAME"] != "") {
                  $rows[$constraint["REFERENCED_TABLE_NAME"]][$constraint["TABLE_NAME"]][] = substr($constraint["COLUMN_NAME"], 0, strlen($constraint["COLUMN_NAME"]) - 3);
                  if ($constraint['COLUMN_NAME'] == 'parent_id') {
                      $rowHasModul[$constraint["TABLE_NAME"]] = 'core';
                  }
              } else {
                  if (!isset($rows[$constraint["TABLE_NAME"]])) {
                      $rows[$constraint["TABLE_NAME"]] = "";
                  }
              }
          }

          $strTable = "<?php";

          foreach ($describedTable["description"] as $desc) {

              $name = $desc["COLUMN_NAME"];
              $type = $desc["DATA_TYPE"];
              $nulable = $desc["NULLABLE"];
              $key = $desc["PRIMARY"];
              $default = $desc["DEFAULT"];

          }
          foreach ($describedTable["constraints"] as $const) {
              if ($const["CONSTRAINT_NAME"] == "PRIMARY") {
                  $primary = $const["COLUMN_NAME"];
              }
          }

          $tableNameArray = explode("_",$desc["TABLE_NAME"]);
          $tableName = "";
          foreach($tableNameArray as $key=>$tableString){
              $tableName .= ucfirst($tableString);
          } 


          $strTable .= "

require_once 'Zend/Db/Table/Abstract.php';

class " . $tableName . "_Table extends Zend_Db_Table_Abstract {

protected \$_name         = '" . $desc["TABLE_NAME"] . "';
protected \$_primary      = array('" . $primary . "');
protected \$_rowClass     = '" . $tableName . "';

protected \$_referenceMap = array(";
          $i = count($describedTable["constraints"]);
          foreach ($describedTable["constraints"] as $const) {

              $reftableNameArray = explode("_",$const["REFERENCED_TABLE_NAME"]);
          $reftableName = "";
          foreach($reftableNameArray as $key=>$reftableString){
              $reftableName .= ucfirst($reftableString);
          }

              if ($const["CONSTRAINT_NAME"] == "PRIMARY") {
                  $primary = $const["COLUMN_NAME"];
              } else {
                  $strTable .= "
                  '" . substr($const["COLUMN_NAME"], 0, strlen($const["COLUMN_NAME"]) - 3) . "' => array(
                    'columns'          => '" . $const["COLUMN_NAME"] . "',
                    'refTableClass'    => '" . $reftableName . "_Table',
                    'refColumns'       => '" . $const["REFERENCED_COLUMN_NAME"] . "')";
                  if ($i > 1) {
                      $strTable .=",\n";
                  }
              }
              $i--;
          }

          $strTable .= "\n                    );\n";
          $i = count($describedTable["constraints"]);

          $strTable .="\n}";
          $tableNameArray = explode("_",$const["TABLE_NAME"]);
          $tableName = "";
          foreach($tableNameArray as $key=>$tableString){
              $tableName .= ucfirst($tableString);
          }
          
          $modul = 'core';
          $myFolderPath = APPLICATION_PATH . "/modules/$modul/models/" . $tableName;
          $myFileTable  = APPLICATION_PATH . "/modules/$modul/models/" . $tableName . "/Table.php";
          if(!is_dir($myFolderPath)) {
            mkdir($myFolderPath, 0777, true);
          }
          $fh = fopen($myFileTable, 'w') or die("can't open file 1: $myFileTable");
          chmod($myFileTable, 0777);
          fwrite($fh, $strTable);
          fclose($fh);
      }

      foreach ($rows as $key => $row) {
          $includePath = 'require_once \'Main/Row.php\';';
          $modul       = 'core'; 

          $tableNameArray = explode("_",$key);
          $tableName = "";
          foreach($tableNameArray as $keyaaa=>$tableString){
              $tableName .= ucfirst($tableString);
          } 
$strRow = "<?php

$includePath

class " . $tableName . "_Row extends Main_Row {\n";
if ($row != "") {
foreach ($row as  $tblRef => $table) {
  $tableNameArray2 = explode("_", $tblRef);
  $tableName2      = "";
  foreach ($tableNameArray2 as $tableString2) {
      $tableName2 .= ucfirst($tableString2);
  }
  $functionName = $tableName2;

  if (count($table) > 1) {
      foreach ($table as $oneFunction) {
          $filter        = new Platforma_Filter_Word_UnderscoreToCamelCase();
          $refFiltered   = $filter->filter($oneFunction);
          $functionName .= ucfirst($refFiltered);
          $strRow       .= "
    public function get" . $functionName . "List(\$select=null) {
        return \$this->_getListOfDepObjects('" . $tableName2 . "','" . $oneFunction . "',\$select);
    }\n";
      }
  } else {
      $strRow .= "
      public function get" . $functionName . "List(\$select=null) {
          return \$this->_getListOfDepObjects('" . $tableName2 . "','" . $table[0] . "',\$select);
      }\n";
  }
}
}
$strRow .="\n}";

          $myFileRow = APPLICATION_PATH . "/modules/$modul/models/" . $tableName . "/Row.php";
          $fh = fopen($myFileRow, 'w') or die("can't open file 2: $myFileRow");
          chmod($myFileRow, 0777);
          fwrite($fh, $strRow);
          fclose($fh);
          
          $modelStr = "<?php

require_once '$tableName/Row.php';

class $tableName extends ".$tableName."_Row
{
}";

              $myFileRow = APPLICATION_PATH . "/modules/$modul/models/" . $tableName . ".php";
              if(!is_file($myFileRow)) {
              $fh = fopen($myFileRow, 'w') or die("can't open file 3: $myFileRow");
              chmod($myFileRow, 0777);
              fwrite($fh, $modelStr);
              fclose($fh);
              }
      }
  } catch (Exception $e) {
      throw new Exception($translate->_("Could not retrive error text.") . $e->getMessage());
  }
  
  
?>