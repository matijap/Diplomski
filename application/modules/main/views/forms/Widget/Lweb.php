<?php

class Widget_Lweb extends Sportalize_Form_Base {

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
            array(array('b' => 'HtmlTag'), array('tag'  => 'div', 'class' => 'append-into sortable-initialize')),
            array(array('a' => 'HtmlTag'), array('tag'  => 'div', 'class' => 'widget-list-with-edit-button all-widget-types')),
        );

        $this->clearDecorators()->setDecorators($decorator);
    }

    public function createElements() {
        $data = false;
        if ($this->widgetID) {
            $widget = Main::buildObject('Widget', $this->widgetID);
            $data   = $widget->getDataForLwebWidget();
        }

        if ($data) {
            $createdElements = array();
            $count           = count($data);
            foreach ($data as $optionID => $oneOption) {
                $title = $this->createElement('text', 'title_' . $optionID, array(
                    'label' => $this->translate->_('List section title'),
                    'value' => $oneOption['title'],
                ));
                $this->addElement($title);
                $createdElements[$optionID][] = $title;
                $options = new Sportalize_Form_Element_WidgetLweb( 'options_' . $optionID, array(
                    'data' => $oneOption['options'],
                    'wiod' => $optionID,
                ));
                $this->addElement($options);
                $createdElements[$optionID][] = $options;

                $plus = new Sportalize_Form_Element_PlainHtml( 'plus_' . $optionID, array(
                'value' => '<i class="fa fa-plus m-r-5 add-new-item pull-right" data-html-template="list-with-edit-button-option-template" data-closest="one-widget-list-section"></i>'
                ));
                $this->addElement($plus);
                $style = $count > 1  ? '' : 'style="display: none;"';
                $remove = new Sportalize_Form_Element_PlainHtml( 'remove_' . $optionID, array(
                    'value' => '<i data-closest="widget-list-with-edit-button" class="fa fa-times remove-item remove-list-section" ' . $style . '></i>'
                ));
                $this->addElement($remove);
                $createdElements[$optionID][] = $plus;
                $createdElements[$optionID][] = $remove;
            }
            foreach ($createdElements as $key => $value) {
                $this->addDisplayGroup($value, 'dg_' . $key);
            }

        }

        // $this->addElement('text', 'title_1', array(
        //     'class' => 'main-label bold-text',
        //     'label' => $this->translate->_('List section title'),
        // ));
        // $el1 = new Sportalize_Form_Element_WidgetLweb( 'options_1', array(
        //     'data' => $data
        // ));
        // $this->addElement($el1);
        // $plus = new Sportalize_Form_Element_PlainHtml( 'plus_1', array(
        //     'value' => '<i class="fa fa-plus m-r-5 add-new-item pull-right" data-html-template="list-with-edit-button-option-template" data-closest="one-widget-list-section"></i>'
        // ));
        // $this->addElement($plus);
        // $remove = new Sportalize_Form_Element_PlainHtml( 'remove_1', array(
        //     'value' => '<i data-closest="widget-list-with-edit-button" class="fa fa-times remove-item remove-list-section" style="display: none;"></i>'
        // ));
        // $this->addElement($remove);
        
        // $this->addDisplayGroup(array('title_1', 'options_1', 'plus_1', 'remove_1'), 'dg1');

        parent::createElements();
    }

    public function redecorate() {
        parent::redecorate();

        $decorator = array(
            'ViewHelper',
            'Description',
            array('Errors'),
            array('HtmlTag', array('tag'  => 'div', 'class' => 'main-div')),
            array('Label', array('class' => 'main-label bold-text')),
            array(array('All' => 'HtmlTag'), array('tag'    => 'div', 'class'   => 'modal-element')),
        );
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Zend_Form_Element_Text') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
            if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetLweb') {
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