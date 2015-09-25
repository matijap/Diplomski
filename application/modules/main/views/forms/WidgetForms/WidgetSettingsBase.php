<?php

class WidgetForms_WidgetSettingsBase extends Sportalize_Form_Base {

    public $widgetID        = false;
    public $dgClass         = '';
    public $containerClass  = '';
    public $widgetBuilt     = '';
    public $defaultWidget   = Widget::WIDGET_TYPE_TABLE;
    public $data            = array();

    public function __construct($data = array()) {

        $widgetClass = Widget::factory(array('type' => $this->widgetBuilt));
        if (isset($data['widgetID']) && !empty($data['widgetID'])) {
            $this->widgetID = $data['widgetID'];
            $widget         = Main::buildObject('Widget', $this->widgetID);
            if ($widget->type != $this->widgetBuilt) {
                $this->dgClass .= ' display-none';
                $this->data     = $widgetClass->getEmptyData();
            } else {
                $widgetClass = Widget::factory(array('type' => $widget->type, 'widgetID' => $this->widgetID));
                $this->data  = $widgetClass->getData();
            }
        } else {
            if ($this->defaultWidget != $this->widgetBuilt) {
                $this->dgClass .= ' display-none';
            }
            $this->data  = $widgetClass->getEmptyData();
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

        $boldedLabelDecorator   = $this->getDefaultModalDecorators('bold-text');
        $defaultDecorator       = $this->getDefaultModalDecorators();

        $viewHelperOnlyElements = array('Sportalize_Form_Element_WidgetLweb', 'Sportalize_Form_Element_WidgetList');
        $boldedLabelElements    = array('Zend_Form_Element_Text');
        $regularElements        = array('Sportalize_Form_Element_WidgetTableData', 'Sportalize_Form_Element_WidgetTableNewData');
        foreach ($this->getElements() as $key => $oneElement) {
            if (in_array($oneElement->getType(), $boldedLabelElements)) {
                $this->clearDecoratorsAndSetDecorator($oneElement, $boldedLabelDecorator);
            }
            if (in_array($oneElement->getType(), $regularElements)) {
                $this->clearDecoratorsAndSetDecorator($oneElement, $defaultDecorator);   
            }
            if (in_array($oneElement->getType(), $viewHelperOnlyElements)) {
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