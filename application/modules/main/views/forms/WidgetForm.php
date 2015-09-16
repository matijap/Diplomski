<?php

class WidgetForm extends Sportalize_Form_Modal {

    public $wid = false;

    public function __construct($data = array()) {
        if (isset($data['widgetID'])) {
            $this->wid = $data['widgetID'];
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
        $widget = false;
        if ($this->wid) {
            $widget = Main::buildObject('Widget', $this->wid);
        }
        $widgetTypeMultioptions = Widget::getWidgetTypeMultioptions();
        $widgetType = $this->createElement('radio', 'type', array(
            'multioptions'     => $widgetTypeMultioptions,
            'separator'        => ' ',
            'label'            => $this->translate->_('Widget Type'),
            'label_class'      => 'checkbox-holder',
            'class'            => 'widget-type-selector',
        ));
        if ($widget) {
            $widgetType->setValue($widget->type);
        }
        $this->addElement($widgetType);

        $widgetTitle = $this->createElement('text', 'title', array(
            'label' => $this->translate->_('Title'),
        ));
        if ($widget) {
            $widgetTitle->setValue($widget->title);
        }
        $this->addElement($widgetTitle);

        $header =  new Sportalize_Form_Element_PlainHtml('header', array(
            'value' => '<h3 class="m-b-10">' . $this->translate->_('Widget Customization') . '</h3>'
        ));
        $this->addElement($header);

        $displayNone = 'style="display: none;"';
        if ($widget && $widget->type == Widget::WIDGET_TYPE_LIST) {
            $displayNone = 'style="display: inline-block;"';
        }
        $button1 = new Sportalize_Form_Element_PlainHtml('button_1', array(
            'value' => '<button data-html-template="list-section-template"
                            class="add-new-item m-t-10  widget-list all-widget-types blue-button add-list-section"
                            data-closest="new-widget-modal" ' . $displayNone . '>
                            ' . $this->translate->_('Add new list section') . '
                        </button>'
        ));
        $this->addElement($button1);
        
        $displayNone = 'display-none';
        if ($widget && $widget->type == Widget::WIDGET_TYPE_LIST_WEB) {
            $displayNone = '';
        }
        $button2 = new Sportalize_Form_Element_PlainHtml('button_2', array(
            'value' => '<button data-html-template="list-with-edit-button-section-template"
                            class="add-new-item m-t-10 widget-list-with-edit-button all-widget-types blue-button add-list-section"
                            data-closest="new-widget-modal" data-find="widget-list-with-edit-button" ' . $displayNone . '>
                            ' . $this->translate->_('Add new list section') . '
                        </button>'
        ));
        $this->addElement($button2);

        $hr = new Sportalize_Form_Element_PlainHtml('hr', array(
            'value' => '<hr>'
        ));
        $this->addElement($hr);
        
        $openingTag   = new Sportalize_Form_Element_PlainHtml('tag_1', array(
            'value' => '<div class="widget-settings">'
        ));
        $this->addElement($openingTag);

        $lwebForm     = new Widget_Lweb(array('widgetID' => $this->wid));
        $this->addSubForm($lwebForm, 'lweb');


        $closingTag   = new Sportalize_Form_Element_PlainHtml('tag_2', array(
            'value' => '</div>'
            ));
        $this->addElement($closingTag);

        if ($widget) {
            $this->addElement('hidden', 'widgetID', array('value' => $this->wid));
        }
        $this->addElement('hidden', 'userID', array('value' => $this->user->id));
    }

    public function redecorate() {
        parent::redecorate();

        $el = $this->getElement('tag_1');
        $this->clearDecoratorsAndSetViewHelperOnly($el);
        $el = $this->getElement('tag_2');
        $this->clearDecoratorsAndSetViewHelperOnly($el);
    }
}