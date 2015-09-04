<?php

class Sportalize_Filter_Word_UnderscoreToCamelCase extends Zend_Filter_Word_Separator_Abstract
{
    public function filter($tableName)
    {
        $className = '';
        $stringsToProcess = explode("_", $tableName);
        foreach ($stringsToProcess as $oneString) {
            $oneString = ucfirst($oneString);
            $className.= $oneString;
        }
        return $className;
    }
}