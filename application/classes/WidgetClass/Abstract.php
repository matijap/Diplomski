<?php

require_once 'Main.php';

class WidgetClass_Abstract {

    public $params;
    public $widget = false;

    public function __construct($params) {
        $this->params = $params;
        $widgetID     = Utils::arrayFetch($params, 'widgetID', false);
        if ($widgetID) {
            $this->widget = Main::buildObject('Widget', $widgetID);
        }
    }

    public function process() {
        if ($this->widget) {
            $this->widget = $this->widget->edit($this->params);
        } else {
            $this->widget = Widget::create($this->params);
        }
        $this->createWidget();
    }

    public function createWidget(){}
}