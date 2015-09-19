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

    const WIDGET_IMAGES_FOLDER               = 'user_images/widget_images';

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

    public function getDataForLwebWidget() {
        $data   = $this->getWidgetOptionList();
        $data   = $data->toArray();
        $return = array();
        $listID = false;

        foreach ($data as $key => $value) {
            if ($value['type'] == self::WIDGET_OPTION_TYPE_LIST_WEB) {
                $return[$value['id']]['title'] = $value['title'];
                $listID = $value['id'];
            }
            if ($value['type'] == self::WIDGET_OPTION_TYPE_LIST_WEB_OPTION) {
                $return[$listID]['options'][$value['id']]['image_1'] = $value['image_1'];
                $return[$listID]['options'][$value['id']]['image_2'] = $value['image_2'];
            }
            if ($value['type'] == self::WIDGET_OPTION_TYPE_LIST_WEB_DATA) {
                $parent = $value['parent_widget_option_id'];
                $temp = Main::buildObject('WidgetOption', $parent);
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['title']   = $value['title'];
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['value_1'] = $value['value_1'];
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['value_2'] = $value['value_2'];
            }
        }
        // fb($return);
        return $return;
    }

    public static function getWidgetTypeMultioptions($includePage = false) {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $return    = array(self::WIDGET_TYPE_PLAIN    => $translate->_('Plain'),
                           self::WIDGET_TYPE_LIST     => $translate->_('List'),
                           self::WIDGET_TYPE_LIST_WEB => $translate->_('List with edit button'),
                           self::WIDGET_TYPE_TABLE    => $translate->_('Table'),
                     );
        if ($includePage) {
            $return[self::WIDGET_TYPE_PAGE] = $translate->_('Page');
        }
        return $return;
    }

    public static function getSelectorForWidgetType($type) {
        switch ($type) {
            case self::WIDGET_TYPE_PLAIN:
                $return = 'widget-plain';
                break;
            case self::WIDGET_TYPE_LIST:
                $return = 'widget-list';
                break;
            case self::WIDGET_TYPE_TABLE:
                $return = 'widget-table';
                break;
            case self::WIDGET_TYPE_LIST_WEB:
                $return = 'widget-list-with-edit-button';
                break;
        }
        return $return;
    }

    public static function create($data, $tableName = null) {
        $widgetData = array('type'    => $data['type'],
                            'title'   => $data['title'],
                            'page_id' => $data['pageID']);
        $widget = parent::create($widgetData);
        $widget->createWidgetOptions($data);
        return false;
    }

    public function createWidgetOptions($data) {
        switch ($data['type']) {
            case self::WIDGET_TYPE_LIST_WEB:
                Widget::createLwebWidget($data, $this->id);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function edit($data) {
        $data = Widget::sortDataArray($data);
        parent::edit($data);
        $this->purgeWidgetOptions();
        $this->createWidgetOptions($data);
    }

    public static function createLwebWidget($data, $widgetID) {

        $data = Widget::sortDataArray($data);
        fb($data);
        fb($_FILES);
        foreach ($data['lweb'] as $elementKey => $elementData) {
            //title is refering to one lweb_list
            $title = $elementData['title_' . $elementKey];
            // checking if there are sent images. they will represent one lweb_list_option
            $lwebListArray = array('widget_id' => $widgetID,
                                   'title'     => $title,
                                   'type'      => Widget::WIDGET_OPTION_TYPE_LIST_WEB);
            $lwebList = WidgetOption::create($lwebListArray);
            if (isset($_FILES['lweb']['name'][$elementKey])) {
                foreach ($_FILES['lweb']['name'][$elementKey] as $optionKey => $optionData) {
                    $fileNames = Utils::uploadMultiFiles($elementKey . '.' . $optionKey . '.images', Widget::WIDGET_IMAGES_FOLDER, 'lweb', $widgetID);
                    $lwebListOptionArray = array('widget_id'               => $widgetID,
                                                 'parent_widget_option_id' => $lwebList->id,
                                                 'image_1'                 => 'widget_football.png',
                                                 'image_2'                 => 'widget_football.png',
                                                 'type'                    => Widget::WIDGET_OPTION_TYPE_LIST_WEB_OPTION,
                                                );

                    $ar = array(1, 2);
                    if (isset($elementData[$optionKey]['temp_images'])) {
                        foreach ($ar as $key => $value) {
                            if (isset($elementData[$optionKey]['temp_images'][$value])) {
                                $lwebListOptionArray['image_' . $value] = $elementData[$optionKey]['temp_images'][$value];
                            }
                        }
                    }
                    fb('filenames', $fileNames);
                    foreach ($ar as $key => $value) {
                        if (isset($fileNames[$value])) {
                            $lwebListOptionArray['image_' . $value] = $fileNames[$value];
                        }
                    }
                    $lwebListOption = WidgetOption::create($lwebListOptionArray);
                    
                    //now check if this option has any data attached to it
                    if (isset($elementData[$optionKey]['data'])) {
                        $decoded = Zend_Json::decode($elementData[$optionKey]['data']);
                        Widget::createWidgetListOptionData($decoded, $lwebListOption->id, $widgetID);
                    }
                }
            } else {
                // if no images are sent, we still need to check if there are some data sent
                foreach ($elementData as $optionKey => $optionData) {
                    if ($optionKey != 'title_' . $elementKey) {
                        $lwebListOptionArray = array('widget_id'               => $widgetID,
                                                     'parent_widget_option_id' => $lwebList->id,
                                                     'image_1'                 => 'widget_football.png',
                                                     'image_2'                 => 'widget_football.png',
                                                     'type'                    => Widget::WIDGET_OPTION_TYPE_LIST_WEB_OPTION,
                                                    );
                        if (isset($optionData['temp_images'])) {
                            $lwebListOptionArray['image_1'] = $optionData['temp_images'][1];
                            $lwebListOptionArray['image_2'] = $optionData['temp_images'][2];
                        }    
                        $lwebListOption = WidgetOption::create($lwebListOptionArray);
                    
                        if (isset($optionData['data'])) {
                            $decoded = Zend_Json::decode($elementData[$optionKey]['data']);
                            Widget::createWidgetListOptionData($decoded, $lwebListOption->id, $widgetID);
                        }
                    }
                }
            }
        }
    }

    public static function createWidgetListOptionData($decoded, $widgetListOptionID, $widgetID) {
        foreach ($decoded as $placement => $items) {
            $placement = strtoupper($placement);
            $lwebListOptionDataArray = array('widget_id'               => $widgetID,
                                             'type'                    => Widget::WIDGET_OPTION_TYPE_LIST_WEB_DATA,
                                             'parent_widget_option_id' => $widgetListOptionID,
                                             'placement'               => $placement,
                                        );
            if ($placement == Widget::WIDGET_LWEB_PLACEMENT_MAIN) {
                if (isset($items['left'])) {
                    foreach ($items['left'] as $dataID => $dataValues) {
                        $lwebListOptionDataArray['value_1'] = $dataValues['value'];
                        $lwebListOptionDataArray['value_2'] = $items['right'][$dataID]['value'];
                        $lwebListOptionDataArray['title']   = $dataValues['label'];
                        $lwebListOptionData                 = WidgetOption::create($lwebListOptionDataArray);
                    }
                }
            } else {
                foreach ($items as $oneAdditional) {
                    $lwebListOptionDataArray['value_1'] = $oneAdditional['value'];
                    $lwebListOptionDataArray['title']   = $oneAdditional['label'];
                    $lwebListOptionData                 = WidgetOption::create($lwebListOptionDataArray);
                }
            }
        }
    }

    public static function sortDataArray($data) {
        $return = false;
        if (isset($data['lweb']['data'])) {
            foreach ($data['lweb']['data'] as $key => $oneOption) {
                foreach ($data['lweb'] as $k1 => $v1) {
                    if ($k1 != 'data') {
                        foreach ($v1 as $k2 => $v2) {
                            if ($key == $k2) {
                                $data['lweb'][$k1][$k2]['data'] = $oneOption;
                            }
                        }

                    }
                }
            }
            unset($data['lweb']['data']);
        }
        return $data;
    }

    public function purgeWidgetOptions() {
        Main::execQuery("DELETE FROM widget_option WHERE type = '" . Widget::WIDGET_OPTION_TYPE_LIST_WEB_DATA . "' and widget_id = '" . $this->id . "'");
        Main::execQuery("DELETE FROM widget_option WHERE type = '" . Widget::WIDGET_OPTION_TYPE_LIST_WEB_OPTION . "' and widget_id = '" . $this->id . "'");
        Main::execQuery("DELETE FROM widget_option WHERE type = '" . Widget::WIDGET_OPTION_TYPE_LIST_WEB . "' and widget_id = '" . $this->id . "'");
    }
}