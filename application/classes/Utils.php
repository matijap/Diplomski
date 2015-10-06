<?php

class Utils
{
    public static function getDateDiffInSeconds($date1, $date2) {
        return $date1->sub($date2)->toValue();
    }

    public static function encrypt($toEncrypt) {
        return hash('sha256', $toEncrypt);
    }

    public static function getRandomNumber($min = 1, $max = 100000) {
        return rand($min, $max);
    }

    public static function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public static function arrayFetch($array, $path, $default = null, $delimiter = '.') {
        $a = $array;
        if (!is_array($path)) {
            $path = explode($delimiter, $path);
        }
        foreach ($path as $key) {
            if (is_array($a)) {
              if (!array_key_exists($key, $a)) {
                return $default;
              }
              $a = $a[$key];
            } else if (is_object($a)) {
                if (!isset($a->{$key})) {
                    return $default;
                }
                $a = $a->{$key};
            } else {
                return $default;
            }
        }
        return $a;
    }

    public static function getYoutubeIdFromUrl($url) {
        preg_match("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches);
        return $matches;
    }

    public static function fileExist($path, $filename) {
        $base_file_name = explode('.',$filename);
        if(file_exists($path.$filename)){
            $add = 1;
            while (file_exists($path.$filename)){
                list($file, $ext) = explode('.',$filename);
                $filename = $base_file_name[0].'_'.$add.".".$ext;
                $add ++;
            }
        }    
        return $filename;
    }

    public static function uploadFile($fileName, $uploadFolder, $fileNamePrefix = '', $delimeter = '_') {
        if (isset($_FILES[$fileName]) && !empty($fileName)) {
            $file      = $_FILES[$fileName];
            if ($file['tmp_name'] != '') {
                if (is_uploaded_file($file['tmp_name'])) {
                    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $file['name'] =  str_replace(' ', '', $file['name']);
                    $fileName     = Utils::fileExist(WEB_ROOT_PATH . "/" . $uploadFolder . "/" , $fileNamePrefix . $delimeter . self::generateRandomString() . '.' . $ext);
                    if (!move_uploaded_file($file['tmp_name'], WEB_ROOT_PATH . "/" . $uploadFolder . "/" . $fileName)) {
                        throw new Exception("Could not upload files or images.");
                    } else {
                        return $fileName;
                    }
                }
            }
        }
    }

    public static function uploadMultiFiles($path, $uploadFolder, $belongsTo = '', $fileNamePrefix = '', $delimeter = '_') {
        $names = $tempNames = $_FILES;
        if (!empty($belongsTo)) {
            $belongsTo = explode('.', $belongsTo);
            foreach ($belongsTo as $key => $value) {
                $names     = $names[$value];
                $tempNames = $tempNames[$value];
            }
        }
        $names     = $names['name'];
        $tempNames = $tempNames['tmp_name'];

        $path = explode('.', $path);
        
        foreach ($path as $key => $value) {
            $names     = $names[$value];
            $tempNames = $tempNames[$value];
        }
        $namesArray = array();
        if (is_array($names)) {
            foreach ($names as $key => $value) {
                if (is_uploaded_file($tempNames[$key])) {
                    $ext      = pathinfo($value, PATHINFO_EXTENSION);
                    $value    = str_replace(' ', '', $value);
                    $fileName = Utils::fileExist(WEB_ROOT_PATH . "/" . $uploadFolder . "/" , $fileNamePrefix . $delimeter . self::generateRandomString() . '.' . $ext);
                    if (!move_uploaded_file($tempNames[$key], WEB_ROOT_PATH . "/" . $uploadFolder . "/" . $fileName)) {
                        throw new Exception("Could not upload files or images.");
                    } else {
                        $namesArray[$key] = $fileName;
                    }
                }
            }
        } else {
            if (is_uploaded_file($tempNames)) {
                $value    = str_replace(' ', '', $names);
                $ext      = pathinfo($names, PATHINFO_EXTENSION);
                $fileName = Utils::fileExist(WEB_ROOT_PATH . "/" . $uploadFolder . "/" , $fileNamePrefix . $delimeter . self::generateRandomString() . '.' . $ext);
                if (!move_uploaded_file($tempNames, WEB_ROOT_PATH . "/" . $uploadFolder . "/" . $fileName)) {
                    throw new Exception("Could not upload files or images.");
                } else {
                    $namesArray[1] = $fileName;
                }
            }
        }
        return $namesArray;
    }

    public static function timestampToLocale($timestamp) {
        $loc       = Zend_Registry::isRegistered('loc') ? Zend_Registry::get('loc') : 'en_US';
        $locale    = Zend_Registry::get('Zend_Locale');
        $date      = new Zend_Date($timestamp, Zend_Date::TIMESTAMP, $locale);
        $list      = Zend_Locale::getTranslationList('Date', $loc);
        return $date->get($list['short']);
    }

    public static function mergeStrings($strings, $delimeter = ' ') {
        return implode($delimeter, $strings);
    }

    public static function generateUrl($parts) {
        return Utils::mergeStrings($parts, '/');
    }
}