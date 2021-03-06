<?php

class WidgetForms_Lweb extends WidgetForms_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = 'append-into sortable-initialize';
        $this->dgClass        = 'widget-list-with-edit-button';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_LIST_WEB;
        
        parent::__construct($data);
    }

    public function createElements() {
        $createdElements = array();
        $count           = count($this->data);
        foreach ($this->data as $optionID => $oneOption) {
            $title = $this->createElement('text', 'title_' . $optionID, array(
                'label'     => $this->translate->_('List section title'),
                'value'     => $oneOption['title'],
                'belongsTo' => 'lweb[' . $optionID . ']',
                'data-id'   => $optionID,
            ));
            $this->addElement($title);
            $createdElements[$optionID][] = $title;
            $options = new Sportalize_Form_Element_WidgetLweb('options_' . $optionID, array(
                'data'     => $oneOption['options'],
                'wiod'     => $optionID,
            ));
            $this->addElement($options);
            $createdElements[$optionID][] = $options;

            $plus = new Sportalize_Form_Element_PlainHtml( 'plus_' . $optionID, array(
            'value' => '<i class="fa fa-plus m-r-5 add-new-item pull-right" data-html-template="list-with-edit-button-option-template" data-closest="div.one-widget-list-section"></i>'
            ));
            $this->addElement($plus);
            $style = $count > 1  ? '' : 'style="display: none;"';
            $remove = new Sportalize_Form_Element_PlainHtml( 'remove_' . $optionID, array(
                'value' => '<i data-closest="div.widget-list-with-edit-button" class="fa fa-times remove-item remove-list-section" ' . $style . '></i>'
            ));
            $this->addElement($remove);
            $createdElements[$optionID][] = $plus;
            $createdElements[$optionID][] = $remove;
        }
        foreach ($createdElements as $key => $value) {
            $this->addDisplayGroup($value, 'dg_' . $key);
        }

        parent::createElements();
    }

    public function redecorate() {
        parent::redecorate();

        $decorator = array(
                       'FormElements',
                        array('HtmlTag', array('tag'   =>'div','class'  => 'one-widget-list-section to-be-removed'))
                    );
        foreach ($this->getDisplayGroups() as $key => $oneDisplayGroup) {
            $this->clearDecoratorsAndSetDecorator($oneDisplayGroup, $decorator);
        }
    }
}