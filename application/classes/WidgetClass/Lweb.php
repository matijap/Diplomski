<?php


class WidgetClass_Lweb extends WidgetClass_Abstract {

    public function createWidget() {
        $data     = $this->params;
        $widgetID = $this->widget->id;

        $data = $this->sortDataArray($data);
        foreach ($data['lweb'] as $elementKey => $elementData) {
            //title is refering to one lweb_list
            $title = $elementData['title_' . $elementKey];
            // checking if there are sent images. they will represent one lweb_list_option
            $lwebListArray = array('widget_id' => $widgetID,
                                   'title'     => $title,
                                   'type'      => Widget::WIDGET_OPTION_TYPE_LIST_WEB);
            $lwebList = WidgetOption::create($lwebListArray);
            if (isset($_FILES['lweb']['name'][$elementKey])) {
                $processedOptionKeys = array();
                foreach ($_FILES['lweb']['name'][$elementKey] as $optionKey => $optionData) {
                    $fileNames = Utils::uploadMultiFiles($elementKey . '.' . $optionKey . '.images', Widget::WIDGET_IMAGES_FOLDER, 'lweb', $widgetID . '_' . $lwebList->id);
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
                    foreach ($ar as $key => $value) {
                        if (isset($fileNames[$value])) {
                            $lwebListOptionArray['image_' . $value] = $fileNames[$value];
                        }
                    }
                    $lwebListOption = WidgetOption::create($lwebListOptionArray);
                    
                    //now check if this option has any data attached to it
                    if (isset($elementData[$optionKey]['data'])) {
                        $decoded = Zend_Json::decode($elementData[$optionKey]['data']);
                        $this->createWidgetListOptionData($decoded, $lwebListOption->id, $widgetID);
                    }
                }
                $processedOptionKeys[] = $optionKey;
                //if we are editing existing option, that already contain images, we need to recreate them
                $this->createWidgetListOption($elementData, $elementKey, $widgetID, $lwebList, $processedOptionKeys);
            } else {
                // if no images are sent, we still need to check if there are some data sent
                $this->createWidgetListOption($elementData, $elementKey, $widgetID, $lwebList);
            }
        }
    }

    public function createWidgetListOptionData($decoded, $widgetListOptionID, $widgetID) {
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

    public function createWidgetListOption($elementData, $elementKey, $widgetID, $lwebList, $processedOptionKeys = array()) {
        foreach ($elementData as $optionKey => $optionData) {
            if (!in_array($optionKey, $processedOptionKeys)) {
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
                        $this->createWidgetListOptionData($decoded, $lwebListOption->id, $widgetID);
                    }
                }
            }
        }
    }

    public function sortDataArray($data) {
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

    public function getData() {
        if (empty($this->widget)) {
            return $this->getEmptyData();
        }
        $data   = $this->widget->getWidgetOptionList();
        $data   = $data->toArray();
        $return = array();
        $listID = false;

        foreach ($data as $key => $value) {
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_LIST_WEB) {
                $return[$value['id']]['title'] = $value['title'];
                $listID = $value['id'];
            }
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_LIST_WEB_OPTION) {
                $return[$listID]['options'][$value['id']]['image_1'] = $value['image_1'];
                $return[$listID]['options'][$value['id']]['image_2'] = $value['image_2'];
            }
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_LIST_WEB_DATA) {
                $parent = $value['parent_widget_option_id'];
                $temp   = Main::buildObject('WidgetOption', $parent);
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['title']   = $value['title'];
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['value_1'] = $value['value_1'];
                $return[$temp->parent_widget_option_id]['options'][$value['parent_widget_option_id']]['data'][$value['placement']][$value['id']]['value_2'] = $value['value_2'];
            }
        }
        return $return;
    }

    public function getEmptyData() {
        return array(Utils::getRandomNumber() => array('title' => '', 'options' => array()));
    }
}