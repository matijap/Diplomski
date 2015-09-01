<?php

class Utils
{
    public static function getDateDiffInSeconds($date1, $date2) {
        return $date1->sub($date2)->toValue();
    }
}