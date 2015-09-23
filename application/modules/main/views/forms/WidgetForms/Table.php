<?php

class WidgetForms_Table extends WidgetForms_WidgetSettingsBase {

    public function __construct($data = array()) {
        $this->containerClass = '';
        $this->dgClass        = 'widget-table';
        $this->widgetBuilt    = Widget::WIDGET_TYPE_TABLE;
        
        parent::__construct($data);
    }

    public function createElements() {

        $option = new Sportalize_Form_Element_WidgetTableOption( 'a', array(
            'value' => '',
            'label' => $this->translate->_('Option'),
        ));
        $this->addElement($option);
        $this->addDisplayGroup(array('a'), 'dg1');

        $plus = new Sportalize_Form_Element_PlainHtml( 'plus', array(
            'value' => '<i class="fa fa-plus m-r-5 add-new-item pull-right" data-html-template="table-option-template" data-closest="div.widget-table"></i>'
        ));
        $this->addElement($plus);
    }

    public function redecorate() {
        parent::redecorate();

        $decorator   = $this->getDefaultModalDecorators('', '', 'to-be-removed');
        foreach ($this->getElements() as $key => $oneElement) {
            if ($oneElement->getType() == 'Sportalize_Form_Element_WidgetTableOption') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
                $decorator = array(
                       'FormElements',
                        array('HtmlTag', array('tag'   =>'div','class'  => 'append-into'))
                    );
        foreach ($this->getDisplayGroups() as $key => $oneDg) {
            $this->clearDecoratorsAndSetDecorator($oneDg, $decorator);
        }
    }
}