<?php

class WidgetForms_WidgetSettings extends Sportalize_Form_Base {

    public $widgetID = false;

    public function __construct($data = array()) {
        if (isset($data['widgetID'])) {
            $this->widgetID = $data['widgetID'];
        }
        parent::__construct($data = array());
    }

    public function init() {
        parent::init();

        $this->setDisableLoadDefaultDecorators(true);

        $decorator = array(
            'FormElements',
            array('HtmlTag', array('tag'  => 'div', 'class' => 'widget-settings')),
        );

        $this->clearDecorators()->setDecorators($decorator);
    }

    public function createElements() {
        parent::createElements();

        $plainForm     = new WidgetForms_Plain(array('widgetID' => $this->widgetID));
        $this->addSubForm($plainForm, 'plainForm');

        $listForm     = new WidgetForms_List(array('widgetID' => $this->widgetID));
        $this->addSubForm($listForm, 'listForm');

        $tableForm     = new WidgetForms_Table(array('widgetID' => $this->widgetID));
        $this->addSubForm($tableForm, 'tableForm');

        $lwebForm     = new WidgetForms_Lweb(array('widgetID' => $this->widgetID));
        $this->addSubForm($lwebForm, 'lweb');
    }
}