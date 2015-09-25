<?php

class WidgetClass_Table extends WidgetClass_Abstract {
    public function createWidget() {
        $displayOrder = 1;
        foreach ($this->params['table'] as $key => $oneTableData) {
            $data = array(
                'widget_id'     => $this->widget->id,
                'value_1'       => $oneTableData['value_1'],
                'value_2'       => $oneTableData['value_2'],
                'display_order' => $displayOrder,
                'type'          => Widget::WIDGET_OPTION_TYPE_TABLE,
            );
            WidgetOption::create($data);
            $displayOrder++;
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
            if ($value['type'] == Widget::WIDGET_OPTION_TYPE_TABLE) {
                $return[$value['id']]['value_1'] = $value['value_1'];
                $return[$value['id']]['value_2'] = $value['value_2'];
            }
        }
        return $return;
    }

    public function getEmptyData() {
        $return = array();
        $rand   = Utils::getRandomNumber();
        $return[$rand]['value_1'] = '';
        $return[$rand]['value_2'] = '';
        return $return;
    }
}