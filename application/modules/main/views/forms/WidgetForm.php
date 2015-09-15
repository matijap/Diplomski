<?php

class WidgetForm extends Sportalize_Form_Modal {

    public $widgetID = false;

    public function __construct($data = array()) {
        if (isset($data['widgetID'])) {
            $this->widgetID = $data['widgetID'];
        }
        parent::__construct($data = array());
    }
    public function init() {
        $this->setAction(APP_URL . '/widget/new-widget');
        $this->setModalTitle($this->translate->_('Create New Widget'));
        parent::init();
    }

    public function createElements() {
        parent::createElements();
        
        $openingTag   = new Sportalize_Form_Element_PlainHtml('tag_1', array(
            'value' => '<div class="widget-settings">'
            ));
        $this->addElement($openingTag);

        $lwebForm     = new Widget_Lweb(array('widgetID' => $this->widgetID));
        $this->addSubForm($lwebForm, 'lweb');


        $closingTag   = new Sportalize_Form_Element_PlainHtml('tag_2', array(
            'value' => '</div>'
            ));
        $this->addElement($closingTag);
    }

    public function redecorate() {
        parent::redecorate();

        $el = $this->getElement('tag_1');
        $this->clearDecoratorsAndSetViewHelperOnly($el);
        $el = $this->getElement('tag_2');
        $this->clearDecoratorsAndSetViewHelperOnly($el);
    }
}