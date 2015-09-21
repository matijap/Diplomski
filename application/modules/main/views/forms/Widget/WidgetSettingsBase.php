<?php

class Widget_WidgetSettingsBase extends Sportalize_Form_Base {

    public $widgetID        = false;
    public $dgClass         = '';
    public $containerClass  = '';
    public $widgetBuilt     = '';
    public $defaultWidget   = Widget::WIDGET_TYPE_LIST;

    public function __construct($data = array()) {
        if (isset($data['widgetID'])) {
            $this->widgetID = $data['widgetID'];
            if ($this->widgetID) {
                $widget = Main::buildObject('Widget', $this->widgetID);
                if ($widget->type != $this->widgetBuilt) {
                    $this->dgClass .= ' display-none';
                }
            } else {
                if ($this->defaultWidget != $this->widgetBuilt) {
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

    public function redecorate() {
        parent::redecorate();

        $decorator     = $this->getDefaultModalDecorators('bold-text');
        $customElements = array('Sportalize_Form_Element_WidgetLweb', 'Sportalize_Form_Element_WidgetList');
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Zend_Form_Element_Text') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
            if (in_array($oneElement->getType(), $customElements)) {
                $this->clearDecoratorsAndSetViewHelperOnly($oneElement);
            }
        }

        $decorator = array(
               'FormElements',
                array('HtmlTag', array('tag'   =>'div','class'  => 'one-widget-list-section to-be-removed'))
            );
        foreach ($this->getDisplayGroups() as $key => $oneDisplayGroup) {
            $this->clearDecoratorsAndSetDecorator($oneDisplayGroup, $decorator);
        }
    }
}