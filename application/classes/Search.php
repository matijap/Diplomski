<?php

class Search
{
    public static function findPeople($search, $excludeID, $onlyFriendsWithID = false) {
        $users = Main::select()
                ->from(array('US' => 'user'), '')
                ->join(array('UI' => 'user_info'), 'US.id = UI.user_id', '')
                ->where('UI.first_name LIKE "' . '%' . $search . '%" OR UI.last_name LIKE "%' . $search . '%"')
                ->where('UI.user_id != ?', $excludeID)
                ->columns(array('CONCAT(UI.first_name, " ", UI.last_name) as name', 'UI.avatar', 'US.id'));
        if ($onlyFriendsWithID) {
            $users->join(array('USUS' => 'user_user'), 'USUS.user_id = US.id OR USUS.friend_id = US.id', '')
                  ->where('USUS.user_id = ' . $onlyFriendsWithID . ' OR USUS.friend_id = ' . $onlyFriendsWithID);
        }
                $users = $users->query()->fetchAll();
        return $users;
    }

    public static function findPages($search, $excludeID) {
        $pages = Main::select()
                ->from(array('PA' => 'page'), '')
                ->where('PA.title LIKE ?', '%' . $search  . '%')
                ->columns(array('PA.title', 'PA.logo', 'PA.id'))
                ->query()->fetchAll();
        return $pages;
    }
}