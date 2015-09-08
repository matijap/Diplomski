<?php

require_once 'Widget/Row.php';

class Widget extends Widget_Row
{
    const WIDGET_TYPE_PAGE                   = 'PAGE';
    const WIDGET_TYPE_LIST                   = 'LIST';
    const WIDGET_TYPE_LIST_WEB               = 'LIST_WEB';
    const WIDGET_TYPE_TABLE                  = 'TABLE';
    const WIDGET_TYPE_PLAIN                  = 'PLAIN';

    const WIDGET_OPTION_TYPE_PAGE            = 'SUB_PAGE';
    const WIDGET_OPTION_TYPE_LIST            = 'SUB_LIST';
    const WIDGET_OPTION_TYPE_LIST_OPTION     = 'SUB_LIST_OPTION';
    const WIDGET_OPTION_TYPE_TABLE           = 'SUB_TABLE';
    const WIDGET_OPTION_TYPE_PLAIN           = 'SUB_PLAIN';
    const WIDGET_OPTION_TYPE_LIST_WEB        = 'SUB_LIST_WEB';
    const WIDGET_OPTION_TYPE_LIST_WEB_OPTION = 'SUB_LIST_WEB_OPTION';
    const WIDGET_OPTION_TYPE_LIST_WEB_DATA   = 'SUB_LIST_WEB_DATA';

    const WIDGET_PLACEMENT_LEFT              = 'LEFT';
    const WIDGET_PLACEMENT_RIGHT             = 'RIGHT';

    const WIDGET_LWEB_PLACEMENT_MAIN         = 'MAIN';
    const WIDGET_LWEB_PLACEMENT_ADDITIONAL   = 'ADDITIONAL';

    public static function gatherAllWidgetsForUser($userID) {
        try {
            $widgets = Main::select()
                   ->from(array('WI' => 'widget'), '')
                   ->join(array('UW' => 'user_widget'), 'UW.widget_id = WI.id', '')
                   ->join(array('WO' => 'widget_option'), 'WO.widget_id = WI.id', '')
                   ->where('UW.user_id = ?', $userID)
                   ->columns(array( 'WI.id as widget_id', 'WI.display_order as widget_display_order', 'WI.placement as widget_placement', 'WI.type',
                                    'WI.title as widget_title',
                                    'WO.type as widget_option_type', 'WO.parent_widget_option_id', 'WO.title as widget_option_title', 'WO.image_1', 'WO.image_2',
                                    'WO.value_1', 'WO.value_2', 'WO.id as widget_option_id', 'WO.linked_page_id'
                            ))
                   ->order(array('WI.placement ASC', 'WI.display_order'))
                   ->query()->fetchAll();
                   // fb($widgets);
            $return = array();
            foreach ($widgets as $key => $oneWidget) {
                $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['type']  = $oneWidget['type'];
                $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['title'] = $oneWidget['widget_title'];
                if ($oneWidget['type'] == self::WIDGET_TYPE_PLAIN) {
                    $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['value'] = $oneWidget['value_1'];
                }
                if ($oneWidget['type'] == self::WIDGET_TYPE_PAGE) {
                    $page = Main::buildObject('Page', $oneWidget['linked_page_id']);
                    $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['linked_page_id']]['title'] = $page->title;
                    $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['linked_page_id']]['image'] = $page->logo;
                }
                if ($oneWidget['type'] == self::WIDGET_TYPE_LIST) {
                    if ($oneWidget['widget_option_type'] == self::WIDGET_OPTION_TYPE_LIST) {
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['type']  = $oneWidget['widget_option_type'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['title'] = $oneWidget['widget_option_title'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['image'] = $oneWidget['image_1'];
                    }
                    if ($oneWidget['widget_option_type'] == self::WIDGET_OPTION_TYPE_LIST_OPTION) {
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['type']    = $oneWidget['widget_option_type'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['value_1'] = $oneWidget['value_1'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['value_2'] = $oneWidget['value_2'];
                    }
                    
                }
                if ($oneWidget['type'] == self::WIDGET_TYPE_LIST_WEB) {
                    if ($oneWidget['widget_option_type'] == self::WIDGET_OPTION_TYPE_LIST_WEB) {
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['type'] = $oneWidget['widget_option_type'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['title'] = $oneWidget['widget_option_title'];
                    }
                    if ($oneWidget['widget_option_type'] == self::WIDGET_OPTION_TYPE_LIST_WEB_OPTION) {
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['type']    = $oneWidget['widget_option_type'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['image_1'] = $oneWidget['image_1'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['parent_widget_option_id']]['options'][$oneWidget['widget_option_id']]['image_2'] = $oneWidget['image_2'];
                    }
                }
                if ($oneWidget['type'] == self::WIDGET_TYPE_TABLE) {
                    if ($oneWidget['widget_option_type'] == self::WIDGET_OPTION_TYPE_TABLE) {
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['value_1'] = $oneWidget['value_1'];
                        $return[$oneWidget['widget_placement']][$oneWidget['widget_id']]['options'][$oneWidget['widget_option_id']]['value_2'] = $oneWidget['value_2'];
                    }
                }
            }
            // fb($return, 'ws');

            return $return;
        } catch(Exception $e) {
            fb($e->getMessage());
        }
    }

    public static function getFileNameToRender($type) {
        switch($type) {
            case self::WIDGET_TYPE_LIST_WEB:
                return 'lweb';
                break;
            case self::WIDGET_TYPE_PAGE:
                return 'page';
                break;
            case self::WIDGET_TYPE_LIST:
                return 'list';
                break;
            case self::WIDGET_TYPE_TABLE:
                return 'table';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getWidgetBodyClass($type) {
        switch($type) {
            case self::WIDGET_TYPE_LIST_WEB:
                return 'upcoming-games';
                break;
            case self::WIDGET_TYPE_PAGE:
                return 'favourite-pages';
                break;
            case self::WIDGET_TYPE_LIST:
                return 'best-scorers';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getLwebOptionData($lwebOptionDataID) {
        try {
            $data = Main::select()
                       ->from(array('WO' => 'widget_option'), '')
                       ->join(array('WO1' => 'widget_option'), 'WO.id = WO1.parent_widget_option_id', '')
                       ->columns(array('WO.image_1', 'WO.image_2', 'WO1.title', 'WO.id as widget_option_id', 'WO1.value_1', 'WO1.value_2', 'WO1.placement', 'WO1.id as widget_option_data_id'))
                       ->where('WO.id = ?', $lwebOptionDataID)
                       ->order(array('WO.display_order', 'WO1.display_order'))
                       ->query()->fetchAll();

            $return = array();
            foreach ($data as $key => $value) {
                $return[$value['placement']][] = $data[$key];
            }
            return $return;
        } catch(Exception $e) {
            fb($e->getMessage());
        }
    }
}