<?php

require_once 'Page/Row.php';

class Page extends Page_Row
{
    const PAGE_TYPE_PLAYER = 'PLAYER';
    const PAGE_TYPE_TEAM   = 'TEAM';
    const PAGE_TYPE_SPORT  = 'SPORT';
    const PAGE_TYPE_OTHER  = 'OTHER';
    const PAGE_TYPE_LEAGUE = 'LEAGUE';

    public static function getAvailablePlayers() {
        return Page::getAvailablePages(Page::PAGE_TYPE_PLAYER);
    }
    public static function getAvailableTeams() {
        return Page::getAvailablePages(Page::PAGE_TYPE_TEAM);
    }

    public static function getAvailablePages($type) {
        return Main::select()
        ->from(array('PA' => 'page'), '')
        ->where('PA.type = ?', $type)
        ->columns(array('PA.id', 'PA.title'))
        ->query()->fetchAll();
    }
    
}