<?php

require_once 'Country/Row.php';

class Country extends Country_Row
{
    public static function getMultioptions() {
        $countries = Main::select()
                    ->from(array('CO' => 'country'), '')
                    ->columns(array('CO.id', 'CO.country_name'))
                    ->query()->fetchAll();

        $return = array();
        foreach ($countries as $key => $value) {
            $return[$value['id']] = $value['country_name'];
        }
        return $return;
    }
}