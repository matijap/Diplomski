<?php

class WidgetForms_Delete extends DeleteItemForm {

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

        $this->addWarningMessage('Are you sure that you want to delete this widget?');
    }
}