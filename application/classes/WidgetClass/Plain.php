<?php

class WidgetClass_Plain extends WidgetClass_Abstract {
    public function createWidget() {
        $text = $this->params['plain']['value_1'];
        $data = array(
            'value_1'   => $text,
            'widget_id' => $this->widget->id,
            'type'      => Widget::WIDGET_OPTION_TYPE_PLAIN,
        );
        WidgetOption::create($data);
    }

    public function getData() {
        if (empty($this->widget)) {
            return $this->getEmptyData();
        }
        $data   = $this->widget->getWidgetOptionList();
        $data   = $data->toArray();
        return $data[0]['value_1'];
    }

    public function getEmptyData() {
        return '';
    }
}