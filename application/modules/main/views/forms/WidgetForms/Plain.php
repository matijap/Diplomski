<?php

class WidgetForms_Plain extends WidgetForms_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = '';
        $this->dgClass        = 'widget-plain';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_PLAIN;
        
        parent::__construct($data);
    }

    public function createElements() {
        $this->addElement('textarea', 'value_1', array(
            'placeholder' => $this->translate->_('Enter your widget text here'),
            'maxlength'   => 1024,
            'belongsTo'   => 'plain',
            'value'       => $this->data,
        ));
    }
}