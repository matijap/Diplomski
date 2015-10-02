<?php

class WidgetForms_ConfigureForUser extends Sportalize_Form_Modal {

    public $pid;

    public function __construct($data = array()) {
        $this->pid = $data['pageID'];
        $this->setAction(APP_URL . '/widget/configure-widget-for-user');
        parent::__construct($data);
    }

    public function createElements() {
        parent::createElements();

        $page    = Main::buildObject('Page', $this->pid);
        $title   = vsprintf($this->translate->_('Configure %s widgets'), array($page->title));
        $this->setModalTitle($title);

        $widgets = $page->gatherWidgetsForPage();
        
        foreach ($widgets as $oneWidget) {
            $this->addElement('radio', $oneWidget['id'], array(
                'multioptions' => Widget::getPlacementMultioptions(),
                'label'        => Widget::translate($oneWidget['title']),
                'separator'    => ' ',
                'class'        => 'm-l-5',
                'label_class'  => 'm-r-10',
                'value'        => $oneWidget['placement'],
                'belongsTo'    => 'widgets',
                'required'     => true,
            ));
        }
        if (!count($widgets)) {
            $message = new Sportalize_Form_Element_PlainHtml('message', array(
                'value' => '<p>' . $this->translate->_('Page does not have widgets to be configured') . '</p>'
            ));
            $this->addElement($message);
        }
        $this->addElement('hidden', 'pageID', array('value' => $this->pid));
    }
}