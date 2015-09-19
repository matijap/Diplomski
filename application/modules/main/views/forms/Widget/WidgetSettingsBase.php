<?php

class Widget_WidgetSettingsBase extends Sportalize_Form_Base {

    public $widgetID        = false;
    public $dgClass         = '';
    public $containerClass  = '';
    public $widgetBuilt     = '';

    public function __construct($data = array()) {
        if (isset($data['widgetID'])) {
            $this->widgetID = $data['widgetID'];
            if ($this->widgetID) {
                $widget = Main::buildObject('Widget', $this->widgetID);
                if ($widget->type != $this->widgetBuilt) {
                    $this->dgClass .= ' display-none';
                }
            } else {
                if (Widget::WIDGET_TYPE_PLAIN != $this->widgetBuilt) {
                    $this->dgClass .= ' display-none';
                }
            }
        }
        parent::__construct($data = array());
    }

    public function init() {
        parent::init();

        $this->setDisableLoadDefaultDecorators(true);
        
        $decorator = array(
            'FormElements',
            array(array('b' => 'HtmlTag'), array('tag'  => 'div', 'class' => $this->containerClass)),
            array(array('a' => 'HtmlTag'), array('tag'  => 'div', 'class' => 'all-widget-types ' . $this->dgClass)),
        );

        $this->clearDecorators()->setDecorators($decorator);
    }
}