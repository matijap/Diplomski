<?php
/**
*  Model MenuLink 
*
* @package CORE
* @subpackage Models
* @copyright Ivan Dudas, Platforma, 2011
* @license http://propulsionapp.com/core/license
* @version  1.0.0
* @since 2010
*/

require_once 'MenuLink/Row.php';

class MenuLink extends MenuLink_Row
{
    public static function fetchMenuLinks() {
        
        $links  = Main::fetchAll('MenuLink', Main::select('MenuLink')->order(array('parent_menu_link_id', 'display_order')));
        $return = array();
        foreach ($links as $oneLink) {
            if (empty($oneLink['parent_menu_link_id'])) {
                $return[$oneLink['id']] = array('url' => $oneLink['url'], 'title' => $oneLink['title']);
            } else {
                $return[$oneLink['parent_menu_link_id']]['sublinks'][$oneLink['id']] = array('url' => $oneLink['url'], 'title' => $oneLink['title']);
            }
        }
        return $return;
    }
}
