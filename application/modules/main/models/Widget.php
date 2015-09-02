<?php

require_once 'Widget/Row.php';

class Widget extends Widget_Row
{
    public static function gatherAllWidgetsForUser($userID) {
        $widgets = Main::select()
                   ->from(array('WI' => 'widget'), '')
                   ->join(array('UW' => 'user_widget'), 'UW.widget_id = WI.id', '')
                   
                   ->where('UW.user_id = ?', $userID)
                   ->query()->fetchAll();
                   fb($widgets, 'ws');
                   return $widgets;
    }
}