<?php

class Widget_List extends Widget_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = 'append-into sortable-initialize';
        $this->dgClass        = 'widget-list append-into sortable-initialize';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_LIST;
        
        parent::__construct($data);
    }

    public function createElements() {

    }
}