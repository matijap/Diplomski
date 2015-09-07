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
}