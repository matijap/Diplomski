<?php

class WidgetClass_List extends WidgetClass_Abstract {
    public function createWidget() {
        foreach ($this->params['list'] as $key => $oneSection) {
            $imageKey = 'image_' . $key;
            $title    = $oneSection['title_' . $key];
            $section  = WidgetOption::create(array('type'      => Widget::WIDGET_OPTION_TYPE_LIST,
                                                  'title'     => $title,
                                                  'widget_id' => $this->widget->id,
                                                ));
            if (isset($oneSection[$imageKey])) {
              $section->edit(array('image_1' => $oneSection[$imageKey]));
            }
            if (isset($_FILES['list']['name'][$key]) && !empty(isset($_FILES['list']['name'][$key]))) {
              $fileName = Utils::uploadMultiFiles($key . '.' . $imageKey, Widget::WIDGET_IMAGES_FOLDER, 'list', $this->widget->id . '_' . $section->id);
              $section->edit(array('image_1' => $fileName[1]));
            }
            foreach ($oneSection as $item => $oneOption) {
                if ($item != 'title_' . $key && $item != $imageKey) {
                    $option = WidgetOption::create(array('type'                    => Widget::WIDGET_OPTION_TYPE_LIST_OPTION,
                                                         'value_1'                 => $oneOption['value_1'],
                                                         'value_2'                 => $oneOption['value_2'],
                                                         'widget_id'               => $this->widget->id,
                                                         'parent_widget_option_id' => $section->id,
                                                    ));
                }
                
            }
        }
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
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_LIST) {
                $return[$value['id']]['title'] = $value['title'];
                $return[$value['id']]['image'] = $value['image_1'];
                $listID                        = $value['id'];
            }
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_LIST_OPTION) {
                $return[$listID]['options'][$value['id']]['value_1'] = $value['value_1'];
                $return[$listID]['options'][$value['id']]['value_2'] = $value['value_2'];
            }
        }
        return $return;
    }

    public function getEmptyData() {
        $data                   = array();
        $rand                   = Utils::getRandomNumber();
        $rand2                  = Utils::getRandomNumber();
        $data[$rand]['title']   = '';
        $data[$rand]['image']   = '';
        $data[$rand]['options'] = array($rand2 => array('value_1' => '',
                                                        'value_2' => ''));
        return $data;
    }
}