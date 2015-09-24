<?php

class WidgetClass_Table extends WidgetClass_Abstract {
    public function createWidget() {
        
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
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_TABLE) {
                $return[$value['id']]['value_1'] = $value['value_1'];
                $return[$value['id']]['value_2'] = $value['value_2'];
                $listID                          = $value['id'];
            }
        }
        return $return;
    }

    public function getEmptyData() {

    }
}