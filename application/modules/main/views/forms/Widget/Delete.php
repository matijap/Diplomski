<?php

class Widget_Delete extends Sportalize_Form_Modal {

    public $wid;
    public $pid;

    public function __construct($data) {
        $this->wid      = $data['widgetID'];
        $widget         = Main::buildObject('Widget', $this->wid);
        $this->pid      = $widget->page_id;

        parent::__construct($data);
    }

    public function init() {
        $this->setAction(APP_URL . '/widget/delete-widget');
        $this->setModalTitle($this->translate->_('Delete widget'));
        parent::init();
    }

    public function createElements() {
        $this->addElement('hidden', 'pageID', array('value' => $this->pid));
        $this->addElement('hidden', 'widgetID', array('value' => $this->wid));

        $message = new Sportalize_Form_Element_PlainHtml('hr', array(
            'value' => '<p>' . $this->translate->_('Are you sure that you want to delete this widget?') . '</p>'
        ));
        $this->addElement($message);
        $this->addDeleteHiddenElement();
    }
}