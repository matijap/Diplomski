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
                    $fileName = Utils::fileExist(WEB_ROOT_PATH . "/" . $uploadFolder . "/" , $fileNamePrefix . $delimeter .$file['name']);
                    if (!move_uploaded_file($file['tmp_name'], WEB_ROOT_PATH . "/" . $uploadFolder . "/" . $fileName)) {
                        throw new Exception("Could not upload files or images.");
                    } else {
                        return $fileName;
                    }
                }
            }
        }
    }
}